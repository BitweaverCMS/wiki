<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/BitPage.php,v 1.119 2009/02/21 17:23:00 tekimaki_admin Exp $
 * @package wiki
 *
 * @author spider <spider@steelsun.com>
 *
 * @version $Revision: 1.119 $ $Date: 2009/02/21 17:23:00 $ $Author: tekimaki_admin $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: BitPage.php,v 1.119 2009/02/21 17:23:00 tekimaki_admin Exp $
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyMime.php' );

/**
 * @package wiki
 */
class BitPage extends LibertyMime {
	var $mPageId;
	var $mPageName;

	function BitPage( $pPageId=NULL, $pContentId=NULL ) {
		LibertyMime::LibertyMime();
		$this->registerContentType( BITPAGE_CONTENT_TYPE_GUID, array(
				'content_type_guid' => BITPAGE_CONTENT_TYPE_GUID,
				'content_description' => 'Wiki Page',
				'handler_class' => 'BitPage',
				'handler_package' => 'wiki',
				'handler_file' => 'BitPage.php',
				'maintainer_url' => 'http://www.bitweaver.org'
			) );
		$this->mPageId = (int)$pPageId;
		$this->mContentId = (int)$pContentId;
		$this->mContentTypeGuid = BITPAGE_CONTENT_TYPE_GUID;

		// Permission setup
		$this->mViewContentPerm  = 'p_wiki_view_page';
		$this->mCreateContentPerm = 'p_wiki_create_page';
		$this->mUpdateContentPerm  = 'p_wiki_update_page';
		$this->mAdminContentPerm = 'p_wiki_admin';
	}

	function findByPageName( $pPageName, $pUserId=NULL ) {
		$userWhere = '';
		$bindVars = array( $pPageName, $this->mContentTypeGuid );
		if( @BitBase::verifyId( $pUserId ) ) {
			$userWhere = " AND lc.`user_id`=?";
			array_push( $bindVars, $pUserId );
		}
		$ret = $this->mDb->getOne("select `page_id` from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) where lc.`title`=? AND lc.`content_type_guid`=? $userWhere", $bindVars );
		return $ret;
	}

	/**
	 * load 
	 * 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function load( $pParse = TRUE ) {
		if( $this->verifyId( $this->mPageId ) || $this->verifyId( $this->mContentId ) ) {
			global $gBitSystem;

			$lookupColumn = @BitBase::verifyId( $this->mPageId ) ? 'page_id' : 'content_id';

			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mPageId )? $this->mPageId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "
				SELECT wp.*, lc.*, lcds.`data` AS `summary`,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) $joinSql
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_data` lcds ON (lc.`content_id` = lcds.`content_id` AND lcds.`data_type`='summary')
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				WHERE wp.`$lookupColumn`=? $whereSql";
			if( $this->mInfo = $this->mDb->getRow( $query, $bindVars ) ) {
				$this->mContentId = $this->mInfo['content_id'];
				$this->mPageId = $this->mInfo['page_id'];
				$this->mPageName = $this->mInfo['title'];
				$this->mInfo['display_url'] = $this->getDisplayUrl();

				// TODO: this is a bad habbit and should not be done BitUser::getDisplayName sorts out what name to display
				$this->mInfo['creator'] = (isset( $this->mInfo['creator_real_name'] ) ? $this->mInfo['creator_real_name'] : $this->mInfo['creator_user'] );
				$this->mInfo['editor'] = (isset( $this->mInfo['modifier_real_name'] ) ? $this->mInfo['modifier_real_name'] : $this->mInfo['modifier_user'] );

				// Save some work if wiki_attachments are not active
				// get prefs before we parse the data that we know how to parse the data
				if( $gBitSystem->isFeatureActive( 'wiki_attachments' ) ) {
					LibertyMime::load();
				} else {
					LibertyContent::load();
				}

				if ( $pParse ) {
					$this->mInfo['parsed_data'] = $this->parseData();
				}
			} else {
				$this->mPageId = NULL;
			}
		}
		return( count( $this->mInfo ) );
	}

	/**
	* This is the ONLY method that should be called in order to store (create or update) a wiki page!
	* It is very smart and will figure out what to do for you. It should be considered a black box.
	*
	* @param array pParams hash of values that will be used to store the page
	*
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	*
	* @access public
	**/
	function store( &$pParamHash ) {
		$this->mDb->StartTrans();

		if( $this->verify( $pParamHash ) && LibertyMime::store( $pParamHash ) ) {
			$pParamHash['page_store']['wiki_page_size'] = !empty( $pParamHash['edit'] ) ? strlen( $pParamHash['edit'] ) : 0;

			$table = BIT_DB_PREFIX."wiki_pages";
			if( $this->verifyId( $this->mPageId ) ) {
				$result = $this->mDb->associateUpdate( $table, $pParamHash['page_store'], array( "page_id" => $this->mPageId ) );
			} else {
				$pParamHash['page_store']['content_id'] = $pParamHash['content_id'];
				if( @$this->verifyId( $pParamHash['page_id'] ) ) {
					// if pParamHash['page_id'] is set, some is requesting a particular page_id. Use with caution!
					$pParamHash['page_store']['page_id'] = $pParamHash['page_id'];
				} else {
					$pParamHash['page_store']['page_id'] = $this->mDb->GenID( 'wiki_pages_page_id_seq');
				}
				$this->mPageId = $pParamHash['page_store']['page_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['page_store'] );
			}
			// Access new data for notifications
			$this->load();

			if( isset( $mailEvents ) ) {
				global $notificationlib, $gBitUser, $gBitSystem, $gBitSmarty;
				include_once( KERNEL_PKG_PATH.'notification_lib.php' );
				$notificationlib->post_content_event($this->mContentId, $this->mInfo['content_type_guid'], 'wiki', $this->mInfo['title'], $this->mInfo['modifier_user'], $this->mInfo['edit_comment'], $this->mInfo['data']);

				if( $gBitSystem->isFeatureActive( 'users_watches') ) {
					$nots = $gBitUser->get_event_watches( 'wiki_page_changed', $this->mPageId );

					foreach ($nots as $not) {
#						if ($wiki_watch_editor != 'y' && $not['user_id'] == $user)
#							break;
						$gBitSmarty->assign('mail_site', $_SERVER["SERVER_NAME"]);

						$gBitSmarty->assign('mail_page', $this->mInfo['title']);
						$gBitSmarty->assign('mail_date', $gBitSystem->getUTCTime());
						$gBitSmarty->assign('mail_user', $this->mInfo['modifier_user']);
						$gBitSmarty->assign('mail_comment', $this->mInfo['edit_comment']);
						$gBitSmarty->assign('mail_last_version', $this->mInfo['version'] - 1);
						$gBitSmarty->assign('mail_data', $this->mInfo['data']);
						$gBitSmarty->assign('mail_hash', $not['hash']);
						$foo = parse_url($_SERVER["REQUEST_URI"]);
						$machine = httpPrefix();
						$gBitSmarty->assign('mail_machine', $machine);
						$parts = explode('/', $foo['path']);

						if (count($parts) > 1)
							unset ($parts[count($parts) - 1]);

						$gBitSmarty->assign('mail_machine_raw', httpPrefix(). implode('/', $parts));
						$gBitSmarty->assign('mail_pagedata', $this->mInfo['data']);
						$mail_data = $gBitSmarty->fetch('bitpackage:wiki/user_watch_wiki_page_changed.tpl');
						$email_to = $not['email'];
						@mail($email_to, tra('Wiki page'). ' ' . $this->mInfo['title'] . ' ' . tra('changed'), $mail_data, "From: ".$gBitSystem->getConfig( 'site_sender_email' )."\r\nContent-type: text/plain;charset=utf-8\r\n");
					}
				}
			}
		}
		$this->mDb->CompleteTrans();
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* verify This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
	* NOTE: This is a PRIVATE METHOD!!!! do not call outside this class, under penalty of death!
	*
	* @param array pParams reference to hash of values that will be used to store the page, they will be modified where necessary
	*
	* @return bool TRUE on success, FALSE if verify failed. If FALSE, $this->mErrors will have reason why
	*
	* @access private
	**/
	function verify( &$pParamHash ) {
		global $gBitUser, $gBitSystem;

		// make sure we're all loaded up of we have a mPageId
		if( $this->verifyId( $this->mPageId ) && empty( $this->mInfo ) ) {
			$this->load();
		}

		if( isset( $this->mInfo['content_id'] ) && $this->verifyId( $this->mInfo['content_id'] ) ) {
			$pParamHash['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( @$this->verifyId( $pParamHash['content_id'] ) ) {
			$pParamHash['page_store']['content_id'] = $pParamHash['content_id'];
		}

		// check for name issues, first truncate length if too long
		if( empty( $pParamHash['title'] ) ) {
			$this->mErrors['title'] = 'You must specify a title';
		} elseif( !empty( $pParamHash['title']) || !empty($this->mPageName))  {
			if( !$this->verifyId( $this->mPageId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this page.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
					if ($gBitSystem->isFeatureActive( 'wiki_allow_dup_page_names')) {
						# silently allow pages with duplicate names to be created
					} else {
						if( $this->pageExists( $pParamHash['title'] ) ) {
							$this->mErrors['title'] = 'Page "'.$pParamHash['title'].'" already exists. Please choose a different name.';
						}
					}
				}
			} else {
				$pParamHash['content_store']['title'] = ( isset( $pParamHash['title'] ) ) ? substr( $pParamHash['title'], 0, 160 ) : $this->mPageName;
				if ($gBitSystem->isFeatureActive( 'wiki_allow_dup_page_names')) {
					# silently allow pages with duplicate names to be created
				} else {
					if( $gBitUser->hasPermission( 'p_wiki_rename_page' )
					&& (isset( $this->mInfo['title'] )
					&& ($pParamHash['title'] != $this->mInfo['title'])) ) {
						if( $this->pageExists( $pParamHash['title'] ) ) {
							$this->mErrors['title'] = 'Page "'.$pParamHash['title'].'" already exists. Please choose a different name.';
						}
					}
				}
			}
		}

		if( empty( $pParamHash['edit_comment'] ) ) {
			$pParamHash['page_store']['edit_comment'] = NULL;
		} else {
			$pParamHash['page_store']['edit_comment'] = substr( $pParamHash['edit_comment'], 0, 200 );
		}

		if( !empty( $pParamHash['minor'] ) && $this->isValid() ) {
			// we can only minor save over our own versions
			if( !$gBitUser->isRegistered() || ($this->mInfo['modifier_user_id'] != $gBitUser->mUserId && !$gBitUser->isAdmin()) ) {
				unset( $pParamHash['minor'] );
			}
		}
		
		// if we have an error we get them all by checking parent classes for additional errors
		if( count( $this->mErrors ) > 0 ){
			parent::verify( $pParamHash );
		}

		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * Remove page from database
	 */
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			$this->expungeVersion(); // will nuke all versions
			$query = "DELETE FROM `".BIT_DB_PREFIX."wiki_pages` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			if( LibertyMime::expunge() ) {
				$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}

	function isUserPage() {
		$ret = FALSE;
		if( $this->mPageName ) {
			$ret = preg_match( '/^UserPage(.*)/', $this->mPageName, $matches );
		}
		return $ret;
	}

	function isValid() {
		return( $this->verifyId( $this->mPageId ) );
	}

	function isLocked() {
		$ret = FALSE;
		if( $this->verifyId( $this->mPageId ) ) {
			if( empty( $this->mInfo ) ) {
				$this->load();
			}
			$ret = $this->getField( 'flag' ) == 'L';
		}
		return( $ret );
	}
	
	function isCommentable() {
		global $gBitSystem;
		return( $gBitSystem->isFeatureActive( 'wiki_comments' ));
	}	

	function setLock( $pLock, $pModUserId=NULL ) {
		if( $this->verifyId( $this->mPageId ) ) {
			$bindVars = array();
			$userSql = '';
			if( $pModUserId ) {
				$userSql = "`modifier_user_id`=?, ";
				array_push( $bindVars, $pModUserId );
			}
			array_push( $bindVars, $pLock, $this->mPageId );
			$query = "update `".BIT_DB_PREFIX."wiki_pages` SET $userSql `flag`=? where `page_id`=?";
			$result = $this->mDb->query($query, $bindVars );
			$this->mInfo['flag'] = $pLock;
		}
		return true;
	}

	function lock( $pModUserId=NULL ) {
		return( $this->setLock( 'L', $pModUserId ) );
	}

	function unlock( $pModUserId=NULL ) {
		return( $this->setLock( NULL, $pModUserId ) );
	}

	// *********  Footnote functions for the wiki ********** //
	/**
	 *  Store footnote
	 */
	function storeFootnote($pUserId, $pData) {
		if( $this->verifyId( $this->mPageId ) ) {
			$querydel = "delete from `".BIT_DB_PREFIX."wiki_footnotes` where `user_id`=? and `page_id`=?";
			$this->mDb->query( $querydel, array( $pUserId, $this->mPageId ) );
			$query = "insert into `".BIT_DB_PREFIX."wiki_footnotes`(`user_id`,`page_id`,`data`) values(?,?,?)";
			$this->mDb->query( $query, array( $pUserId, $this->mPageId, $pData ) );
		}
	}

	/**
	 *  Delete footnote
	 */
	function expungeFootnote( $pUserId ) {
		if( $this->verifyId( $this->mPageId ) ) {
			$query = "delete from `".BIT_DB_PREFIX."wiki_footnotes` where `user_id`=? and `page_id`=?";
			$this->mDb->query($query,array($pUserId,$this->mPageId));
		}
	}

	/**
	 *  Get footnote
	 */
	function getFootnote( $pUserId ) {
		if( $this->verifyId( $this->mPageId ) ) {
			$count = $this->mDb->getOne( "select count(*) from `".BIT_DB_PREFIX."wiki_footnotes` where `user_id`=? and `page_id`=?", array( $pUserId, $this->mPageId ) );
			if( $count ) {
				return $this->mDb->getOne("select `data` from `".BIT_DB_PREFIX."wiki_footnotes` where `user_id`=? and `page_id`=?",array( $pUserId, $this->mPageId ) );
			}
		}
	}

	/**
	* Generates a link to a wiki page within lists of pages
	* @param pExistsHash the hash that was returned by LibertyContent::pageExists
	* @return the link to display the page.
	*/
	function getListLink( $pPageHash ) {
		return BitPage::getDisplayLink( $pPageHash['title'], NULL );
	}


	/**
	* Returns include file that will
	* @return the fully specified path to file to be included
	*/
	function getRenderFile() {
		return WIKI_PKG_PATH."display_bitpage_inc.php";
	}


	/**
	 * Returns the center template for the view selected
	 */
	function getViewTemplate( $pAction ){				
		$ret = null;
		switch ( $pAction ){
			case "view":
				$ret = "bitpackage:wiki/center_wiki_page.tpl"; 
				break;
			case "list":
				$ret = "bitpackage:liberty/center_".$pAction."_generic.tpl"; 
				break;
		}
		return $ret;
	}


	/**
	* Generates the URL to this wiki page
	* @param pExistsHash the hash that was returned by LibertyContent::pageExists
	* @return the link to display the page.
	*/
	function getDisplayUrl( $pPageName = NULL, $pPageHash = NULL ) {
		global $gBitSystem;
		if( empty( $this->mPageName ) && !empty( $pPageHash['title'] )) {
			$pPageName = $pPageHash['title'];
		}

		if( empty( $pPageName ) && !empty( $this->mPageName )) {
			$pPageName = $this->mPageName;
		}

		if( !empty( $pPageName )) {
			if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'pretty_urls_extended' ) ) {
				$rewrite_tag = $gBitSystem->isFeatureActive( 'pretty_urls_extended' ) ? 'view/':'';
				$prettyPageName = preg_replace( '/ /', '+', $pPageName );
				$ret = WIKI_PKG_URL.$rewrite_tag.$prettyPageName;
			} else {
				$ret = WIKI_PKG_URL.'index.php?page='.urlencode( $pPageName );
			}
		} else {
			$ret = LibertyContent::getDisplayUrl( NULL, $pPageHash );
		}

		return $ret;
	}

	/**
	* Returns HTML link to display a page if it exists, or to create if not
	* @param pExistsHash the hash that was returned by LibertyContent::pageExists
	* @return the link to display the page.
	*/
	function getDisplayLink( $pPageName, $pExistsHash ) {
		global $gBitSystem, $gBitUser;
		$ret = $pPageName;
		if( $gBitSystem->isPackageActive( 'wiki' ) ) {
			if( !empty( $pExistsHash ) && is_array( $pExistsHash ) ) {
				if( is_array( current( $pExistsHash ) ) ) {
					$exists = $pExistsHash[0];
					$multiple = TRUE;
				} else {
					$exists = $pExistsHash;
					$multiple = FALSE;
				}

				// we have a multi-demensional array (likely returned from LibertyContent::pageExists() ) - meaning we potentially have multiple pages with the same name
				if( $multiple ) {
					$desc = tra( 'Multiple pages with this name' );
				} else {
					$desc = empty( $exists['summary'] ) ? $exists['title'] : $exists['summary'];
				}
				$ret = '<a title="'.htmlspecialchars( $desc ).'" href="'.BitPage::getDisplayUrl( $exists['title'] ).'">'.htmlspecialchars( $pPageName ).'</a>';
			} else {
				if( $gBitUser->hasPermission( 'p_wiki_create_page' ) ) {
					$ret = '<a title="'.tra( "Create the page" ).': '.htmlspecialchars( $pPageName ).'" href="'.WIKI_PKG_URL.'edit.php?page='.urlencode( $pPageName ).'" class="create">'.htmlspecialchars( $pPageName ).'</a>';
				} else {
					$ret = $pPageName;
				}
			}
		}
		return $ret;
	}

	/**
	* Returns content_id's that link to this page
	* @return hash of content
	*/
	function getBacklinks() {
		if( $this->isValid() ) {
			$to_title = $this->mInfo['title'];
			$query = "SELECT lcl.`from_content_id`, lc.`title`
					  FROM `".BIT_DB_PREFIX."liberty_content_links` lcl INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lcl.`from_content_id`=lc.`content_id`)
					  WHERE lcl.`to_title` = ?";
			$ret = $this->mDb->getAssoc( $query, array( $to_title ) );
			return $ret;
		}
	}


	/**
	 * Roll back to a specific version of a page
	 * @param pVersion Version number to roll back to
	 * @param comment Comment text to be added to the action log
	 * @return TRUE if completed successfully
	 */
	function rollbackVersion( $pVersion, $comment = '' ) {
		global $gBitSystem;
		$ret = FALSE;
		if( parent::rollbackVersion( $pVersion, $comment ) ) {
			$action = "Changed actual version to $pVersion";
			$t = $gBitSystem->getUTCTime();
			$query = "insert into `".BIT_DB_PREFIX."liberty_action_log`(`log_message`,`content_id`,`last_modified`,`user_id`,`ip`,`error_message`) values(?,?,?,?,?,?)";
			$result = $this->mDb->query($query,array($action,$this->mContentId,$t,ROOT_USER_ID,$_SERVER["REMOTE_ADDR"],$comment));
			$ret = TRUE;
		}
		return $ret;
	}

	/**
	 * getList 
	 * 
	 * @param array $pListHash array of list parameters
	 * @param boolean $pListHash['orphans_only'] only return orphan wiki pages
	 * @param boolean $pListHash['extras'] load extra infrmation such as backlinks and links
	 * @param boolean $pListHash['get_data'] return the wiki page data along with the listed information
	 * @param string $pListHash['find_title'] filter by the page title
	 * @param string $pListHash['find_author'] filter by the login name of the page author
	 * @param string $pListHash['find_last_editor'] filter by the login name of the last editor of the page
	 * @access public
	 * @return array of wiki pages
	 */
	function getList( &$pListHash ) {
		global $gBitSystem, $gBitUser;
		LibertyContent::prepGetList( $pListHash );

		if( $pListHash['sort_mode'] == 'size_asc' || $pListHash['sort_mode'] == 'size_desc' ) {
			$pListHash['sort_mode'] = str_replace( 'size', 'wiki_page_size', $pListHash['sort_mode'] );
		}

		$specialSort = array(
			'versions_desc',
			'versions_asc',
			'links_asc',
			'links_desc',
			'backlinks_asc',
			'backlinks_desc'
		);

		if( in_array( $pListHash['sort_mode'], $specialSort )) {
			$originalListHash         = $pListHash;
			// now we can set the new values in the pListHash
			$pListHash['sort_mode']   = 'modifier_user_desc';
			$pListHash['offset']      = 0;
			$pListHash['max_records'] = -1;
		}

		$whereSql = $joinSql = $selectSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars, NULL, $pListHash );

		// make find_title compatible with {minifind}
		if( empty( $pListHash['find_title'] )) {
			$pListHash['find_title'] = $pListHash['find'];
		}

		// use an array or string to search for wiki page titles
		if( is_array( $pListHash['find_title'] )) {
			$whereSql .= " AND lc.`title` IN (".implode(',',array_fill( 0, count( $pListHash['find_title'] ), '?' )).")";
			$bindVars = array_merge( $bindVars, $pListHash['find_title'] );
		} elseif( !empty( $pListHash['find_title'] ) && is_string( $pListHash['find_title'] )) {
			$whereSql .= " AND UPPER(lc.`title`) LIKE ? ";
			$bindVars = array_merge( $bindVars, array( '%'.strtoupper( $pListHash['find_title'] ) . '%' ));
		}

		// limit by user id
		if( @BitBase::verifyId( $pListHash['user_id'] )) {
			$whereSql .= " AND lc.`user_id` = ? ";
			$bindVars = array_merge( $bindVars, array( $pListHash['user_id'] ));
		}

		// filter pages by author login
		if( !empty( $pListHash['find_author'] )) {
			$whereSql .= " AND UPPER(uuc.`login`) = ? ";
			$bindVars = array_merge( $bindVars, array( strtoupper( $pListHash['find_author'] )));
		}

		// filter pages by last editor
		if( !empty( $pListHash['find_last_editor'] )) {
			$whereSql .= " AND UPPER(uue.`login`) = ? ";
			$bindVars = array_merge( $bindVars, array( strtoupper( $pListHash['find_last_editor'] )));
		}

		$get_data = '';
		if( !empty( $pListHash['get_data'] )) {
			$get_data = ', lc.`data`';
		}

		if( empty( $pListHash['orphans_only'] )) {
			$query = "SELECT 
					uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name, uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name,
					wp.`page_id`, wp.`wiki_page_size` as `len`, lcds.`data` AS `summary`, wp.`edit_comment`, wp.`content_id`, wp.`flag`,
					lc.`title`, lc.`format_guid`, lc.`last_modified`, lc.`created`, lc.`ip`, lc.`version`,
					lch.`hits` $get_data $selectSql
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`modifier_user_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_data` lcds ON (lc.`content_id` = lcds.`content_id` AND lcds.`data_type`='summary')
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_hits` lch ON (lc.`content_id` = lch.`content_id`)
					$joinSql
				WHERE lc.`content_type_guid`=? $whereSql
				ORDER BY ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
			$query_cant = "
				SELECT COUNT(*)
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`modifier_user_id` )
					$joinSql
				WHERE lc.`content_type_guid`=? $whereSql
				";
		} else {
			$query = "SELECT 
					uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name, uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name,
					wp.`page_id`, wp.`wiki_page_size` AS `len`,lcds.`data` AS `summary`, wp.`edit_comment`, wp.`content_id`, wp.`flag`,
					lc.`title`, lc.`format_guid`, lc.`last_modified`, lc.`created`, lc.`ip`, lc.`version`,
					lch.`hits` $get_data $selectSql
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`user_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_links` lcl ON (wp.`content_id` = lcl.`to_content_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_hits` lch ON (lc.`content_id` = lch.`content_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_data` lcds ON (lc.`content_id` = lcds.`content_id` AND lcds.`data_type`='summary')
					$joinSql
				WHERE lc.`content_type_guid`=?
					AND lcl.`to_content_id` is NULL
					$whereSql
				ORDER BY ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
			$query_cant = "
				SELECT COUNT(*)
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					LEFT JOIN `".BIT_DB_PREFIX."liberty_content_links` lcl ON (wp.`content_id` = lcl.`to_content_id`)
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					$joinSql
				WHERE lc.`content_type_guid`=?
					AND lcl.`to_content_id` IS NULL
					$whereSql";
		}

		// If sort mode is versions then offset is 0, max_records is -1 (again) and sort_mode is nil
		// If sort mode is links then offset is 0, max_records is -1 (again) and sort_mode is nil
		// If sort mode is backlinks then offset is 0, max_records is -1 (again) and sort_mode is nil

		$ret = array();
		$this->mDb->StartTrans();

		# get count of total number of items available
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
		$pListHash["cant"] = $cant;

		# Check for offset out of range
		if ( $pListHash['offset'] < 0 ) {
			$pListHash['offset'] = 0;
		} elseif ( $pListHash['offset']	> $pListHash["cant"] ) {
			$lastPageNumber = ceil ( $pListHash["cant"] / $pListHash['max_records'] ) - 1;
			$pListHash['offset'] = $pListHash['max_records'] * $lastPageNumber;
		}

		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		$this->mDb->CompleteTrans();
		while( $res = $result->fetchRow() ) {
			$aux = array();
			$aux = $res;
			$aux['creator'] = (isset( $res['creator_real_name'] ) ? $res['creator_real_name'] : $res['creator_user'] );
			$aux['editor'] = (isset( $res['modifier_real_name'] ) ? $res['modifier_real_name'] : $res['modifier_user'] );
			$aux['flag'] = $res["flag"] == 'L' ? 'locked' : 'unlocked';
			$aux['display_url'] = $this->getDisplayUrl( $aux['title'], $aux );
			// display_link does not seem to be used when getList is called
			//$aux['display_link'] = $this->getDisplayLink( $aux['title'] ); //WIKI_PKG_URL."index.php?page_id=".$res['page_id'];
			if( !empty( $pListHash['extras'] )) {
				// USE SPARINGLY!!! This gets expensive fast
//				$aux['versions"]  = $this->mDb->getOne( "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."liberty_content_history` WHERE `page_id`=?", array( $res["page_id"] ));
				$aux['links']     = $this->mDb->getOne( "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."liberty_content_links` WHERE `from_content_id`=?", array( $res["content_id"] ));
				$aux['backlinks'] = $this->mDb->getOne( "select COUNT(*) FROM `".BIT_DB_PREFIX."liberty_content_links` WHERE `to_title`=?", array( $aux['title'] ));
			}
			$ret[] = $aux;
		}

		// apply the custom sorting options if needed
		if( !empty( $originalListHash )) {
			if( $originalListHash['sort_mode'] == 'versions_asc' && !empty( $ret['versions'] )) {
				usort( $ret, 'compare_versions');
			} elseif( $originalListHash['sort_mode'] == 'versions_desc' && !empty( $ret['versions'] )) {
				usort( $ret, 'r_compare_versions');
			} elseif( $originalListHash['sort_mode'] == 'links_desc' && !empty( $ret['links'] )) {
				usort( $ret, 'compare_links');
			} elseif( $originalListHash['sort_mode'] == 'links_asc' && !empty( $ret['links'] )) {
				usort( $ret, 'r_compare_links');
			} elseif( $originalListHash['sort_mode'] == 'backlinks_desc' && !empty( $ret['backlinks'] )) {
				usort($ret, 'compare_backlinks');
			} elseif( $originalListHash['sort_mode'] == 'backlinks_asc' && !empty( $ret['backlinks'] )) {
				usort($ret, 'r_compare_backlinks');
			}

			// return only requested values
			if( in_array( $originalListHash['sort_mode'], $specialSort )) {
				$ret = array_slice( $ret, $originalListHash['offset'], $originalListHash['max_records'] );
			}

			// load original listHash
			$pListHash = $originalListHash;
		}

		LibertyContent::postGetList( $pListHash );

		return $ret;
	}

	/* Update a page
	 * $pHashOld the where conmdition : page_id
	 * $pHashNew the new fields: title, data
	 */
	function update( $pHashOld, $pHashNew) {
		$set = array();
		$where = array();
		if (!empty($pHashNew['title'])) {
			$set[] = "lc.`title`=?";
			$bindVars[] = $pHashNew['title'];
		}
		if (!empty($pHashNew['data'])) {
			$set[] = "lc.`data`=?";
			$bindVars[] = $pHashNew['data'];
		}
		if (!empty($pHashOld['page_id'])) {
			$where[] = "wp.`page_id`=?";
			$bindVars[] = $pHashOld['page_id'] ;
		}
		if (empty($where)) {
			$this->mErrors['page_id'] = "You must specify a where condition";
			return false;
		}

		$query = "update `".BIT_DB_PREFIX."liberty_content` lc
			LEFT JOIN `".BIT_DB_PREFIX."wiki_pages` wp on (wp.`content_id`= lc.`content_id`)
			SET ".implode(',', $set)."
			WHERE ".implode (" AND ", $where);
		$this->mDb->query( $query, $bindVars);
		return true;
	}

	// ...page... functions
	function countSubPages( $pData ) {
		return(( preg_match_all( '/'.( defined( 'PAGE_SEP' ) ? preg_quote( PAGE_SEP ) : '\.\.\.page\.\.\.').'/', $pData, $matches ) + 1 ));
	}

	/**
	 * getSubPage 
	 * 
	 * @param array $pData 
	 * @param array $pPageNumber 
	 * @access public
	 * @return string SubPage
	 */
	function getSubPage( $pData, $pPageNumber ) {
		// Get slides
		$parts = explode( defined( 'PAGE_SEP' ) ? PAGE_SEP : "...page...", $pData );
		if( substr( $parts[$pPageNumber - 1], 1, 5 ) == "<br/>" ) {
			$ret = substr( $parts[$pPageNumber - 1], 6 );
		} else {
			$ret = $parts[$pPageNumber - 1];
		}
		return $ret;
	}

	/**
	 * getLikePages Like pages are pages that share a word in common with the current page
	 * 
	 * @param array $pPageTitle 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function getLikePages( $pPageTitle ) {
		$ret = array();
		if( !empty( $pPageName ) ) {
			preg_match_all("/([A-Z])([a-z]+)/", $pPageTitle, $words);
			// Add support to ((x)) in either strict or full modes
			preg_match_all("/(([A-Za-z]|[\x80-\xFF])+)/", $pPageTitle, $words2);
			$words = array_unique(array_merge($words[0], $words2[0]));
			$exps = array();
			$bindVars=array();
			foreach ($words as $word) {
				$exps[] = "`title` like ?";
				$bindVars[] = "%$word%";
			}
			$selectSql = '';
			$joinSql = '';
			$whereSql = implode(" or ", $exps);
			array_push( $bindVars, $this->mContentTypeGuid );
			$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "SELECT lc.`title` FROM `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) $join WHERE $whereSql";
			$result = $this->mDb->query($query,$bindVars);
			while ($res = $result->fetchRow()) {
				$ret[] = $res["title"];
			}
		}
		return $ret;
	}

	/**
	 * getStats getStats is always used by the stats package to display various stats of your package.
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function getStats() {
		global $gBitSystem;
		$ret = array();

		$query = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."wiki_pages`";
		$ret['pages'] = array(
			'label' => "Number of pages",
			'value' => $this->mDb->getOne( $query ),
		);

		$listHash = array( 'orphans_only' => TRUE );
		$this->getList( $listHash );
		$ret['orphans'] = array(
			'label' => 'Orphan Pages',
			'value' => $listHash['listInfo']['total_records'],
		);

		$query = "SELECT SUM(`wiki_page_size`) FROM `".BIT_DB_PREFIX."wiki_pages`";
		$ret['size'] = array(
			'label' => "Combined size",
			'value' => $this->mDb->getOne( $query ),
			'modifier' => 'display_bytes',
		);

		$ret['average_size'] = array(
			'label' => 'Average page size',
			'value' => $ret['size']['value'] / $ret['pages']['value'],
			'modifier' => 'display_bytes',
		);

		$query = "
			SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."liberty_content_history` lch
			INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lch.`content_id` = lc.`content_id` )
			WHERE lc.`content_type_guid` = ?";
		$ret['versions'] = array(
			'label' => "Versions",
			'value' => $this->mDb->getOne( $query, array( BITPAGE_CONTENT_TYPE_GUID )),
		);

		$ret['average_versions'] = array(
			'label' => 'Average versions per page',
			'value' => round( $ret['versions']['value'] / $ret['pages']['value'], 3 ),
		);

		$query = "
			SELECT COUNT(*) FROM `".BIT_DB_PREFIX."liberty_content_links` lcl
			INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lcl.`from_content_id` = lc.`content_id` OR lcl.`from_content_id` = lc.`content_id` )
			WHERE lc.`content_type_guid` = ?";
		$ret['links'] = array(
			'label' => "Total wiki links",
			'value' => $this->mDb->getOne( $query, array( BITPAGE_CONTENT_TYPE_GUID )),
		);

		$ret['average_links'] = array(
			'label' => 'Average links per page',
			'value' => round( $ret['links']['value'] / $ret['pages']['value'], 3 ),
		);

		return $ret;
	}

	// {{{ ==================================== GraphViz wiki graph methods ====================================
	/**
	 * linkStructureGraph 
	 * 
	 * @param array $pLinkStructure 
	 * @param array $pParams 
	 * @param array $pGraphViz 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function linkStructureGraph( $pLinkStructure = array(), $pParams = array(), &$pGraphViz ) {
		if( !empty( $pLinkStructure ) && !empty( $pGraphViz )) {
			$pParams['graph']['URL'] = WIKI_PKG_URL.'index.php';
			$pGraphViz->addAttributes( $pParams['graph'] );

			$pParams['node']['URL'] = $this->getDisplayUrl( $pLinkStructure['name'] );
			$pGraphViz->addNode( $pLinkStructure['name'], $pParams['node'] );

			foreach( $pLinkStructure['pages'] as $node ) {
				$this->linkStructureGraph( $node, $pParams, $pGraphViz );
				$pGraphViz->addEdge( array( $pLinkStructure['name'] => $node['name'] ), $pParams['node'] );
			}
		}
	}

	/**
	 * linkStructureMap 
	 * 
	 * @param array $pPageName 
	 * @param int $pLevel 
	 * @param array $pParams 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function linkStructureMap( $pPageName, $pLevel = 0, $pParams = array() ) {
		if( !empty( $pPageName ) && @include_once( 'Image/GraphViz.php' )) {
			$graph = new Image_GraphViz();
			$this->linkStructureGraph( $this->getLinkStructure( $pPageName, $pLevel ), $pParams, $graph );
			return $graph->fetch( 'cmap' );
		}
	}

	/**
	 * getLinkStructure 
	 * 
	 * @param array $pPageName 
	 * @param float $pLevel 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function getLinkStructure( $pPageName, $pLevel = 0 ) {
		$query = "
			SELECT lc2.`title`
			FROM `".BIT_DB_PREFIX."liberty_content_links` lcl
				INNER JOIN liberty_content lc1 ON( lc1.`content_id` = lcl.`from_content_id` )
				INNER JOIN liberty_content lc2 ON( lc2.`content_id` = lcl.`to_content_id` )
			WHERE lc1.`title` = ?";
		$result = $this->mDb->query( $query, array( $pPageName ));

		$ret['pages'] = array();
		$ret['name']  = $pPageName;

		while ($res = $result->fetchRow()) {
			if( !empty( $pLevel )) {
				$ret['pages'][] = $this->getLinkStructure( $res['title'], $pLevel - 1 );
			} else {
				$ret['pages'][] = array(
					'name'  => $res['title'],
					'pages' => array(),
				);
			}
		}
		return $ret;
	}
	// }}}
}

/* vim: :set fdm=marker : */
?>
