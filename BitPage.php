<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/BitPage.php,v 1.84 2007/03/31 23:30:19 squareing Exp $
 * @package wiki
 *
 * @author spider <spider@steelsun.com>
 *
 * @version $Revision: 1.84 $ $Date: 2007/03/31 23:30:19 $ $Author: squareing $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: BitPage.php,v 1.84 2007/03/31 23:30:19 squareing Exp $
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyAttachable.php' );

/**
 * Sample class to illustrate best practices when creating a new bitweaver package that
 * builds on core bitweaver functionality, such as the Liberty CMS engine
 *
 * @package wiki
 *
 * created 2004/8/15
 */
class BitPage extends LibertyAttachable {
	var $mPageId;
	var $mPageName;

	function BitPage( $pPageId=NULL, $pContentId=NULL ) {
		LibertyAttachable::LibertyAttachable();
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

	function load() {
		if( $this->verifyId( $this->mPageId ) || $this->verifyId( $this->mContentId ) ) {
			global $gBitSystem;
			$lookupColumn = @BitBase::verifyId( $this->mPageId ) ? 'page_id' : 'content_id';

			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mPageId )? $this->mPageId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "select wp.*, lc.*,
					  uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
					  uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
					  FROM `".BIT_DB_PREFIX."wiki_pages` wp
						INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) $joinSql
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
					  WHERE wp.`$lookupColumn`=? $whereSql";
			if( $this->mInfo = $this->mDb->getRow( $query, $bindVars ) ) {
				$this->mContentId = $this->mInfo['content_id'];
				$this->mPageId = $this->mInfo['page_id'];
				$this->mPageName = $this->mInfo['title'];
				$this->mInfo['creator'] = (isset( $this->mInfo['creator_real_name'] ) ? $this->mInfo['creator_real_name'] : $this->mInfo['creator_user'] );
				$this->mInfo['editor'] = (isset( $this->mInfo['modifier_real_name'] ) ? $this->mInfo['modifier_real_name'] : $this->mInfo['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();

				// parse the data respecting caching options
				$cache = $gBitSystem->getConfig( 'wiki_cache' );
				if( @BitBase::verifyId( $this->mInfo['wiki_cache'] ) && $this->mInfo['wiki_cache'] > 0 ) {
					$cache = $this->mInfo['wiki_cache'];
				}

				// Save some work if wiki_attachments are not active
				// get prefs before we parse the data that we know how to parse the data
				if( $gBitSystem->isFeatureActive( 'wiki_attachments' ) ) {
					LibertyAttachable::load();
				} else {
					LibertyContent::load();
				}

				if( $cache > 0 ) {
					if( ( $this->mInfo['cache_timestamp'] + $cache ) > $gBitSystem->getUTCTime() ) {
						$this->mInfo['parsed_data'] = $this->mInfo['page_cache'];
						$this->mInfo['page_is_cached'] = TRUE;
					} else {
						$this->mInfo['parsed_data'] = $this->parseData();
						$this->updateCache( $this->mInfo['parsed_data'] );
					}
				} else {
					$this->mInfo['parsed_data'] = $this->parseData();
				}

			} else {
				$this->mPageId = NULL;
			}
		}
		return( count( $this->mInfo ) );
	}
	// }}}
	// {{{ store
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

		if( $this->verify( $pParamHash ) && LibertyAttachable::store( $pParamHash ) ) {
			if(isset($pParamHash['wiki_cache']) ) {
				$this->setPageCache( $pParamHash['wiki_cache'] );
			}

			$pParamHash['page_store']['wiki_page_size'] = !empty( $pParamHash['edit'] ) ? strlen( $pParamHash['edit'] ) : 0;

			$table = BIT_DB_PREFIX."wiki_pages";
			if( $this->verifyId( $this->mPageId ) ) {
				$this->invalidateCache();
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
	// }}}
	// {{{ verify
	/**
	* This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
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

		// check some lengths, if too long, then truncate
		if( $this->isValid() && !empty( $this->mInfo['description'] ) && empty( $pParamHash['description'] ) ) {
			// someone has deleted the description, we need to null it out
			$pParamHash['page_store']['description'] = '';
		} elseif( empty( $pParamHash['description'] ) ) {
			unset( $pParamHash['description'] );
		} else {
			$pParamHash['page_store']['description'] = substr( $pParamHash['description'], 0, 200 );
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

/*		} elseif( !empty( $pParamHash['page'] ) && !empty( $pParamHash['newpage'] ) && ( $pParamHash['page'] != $pParamHash['newpage'] ) ) {
			// check for rename, and rename it now if we can
			if ($this->wiki_rename_page( $pParamHash['page'], $pParamHash['newpage'])) {
				$pParamHash['page'] = $pParamHash['newpage'];
			} else {
				$this->mErrors['page'] = 'Page "'.$pParamHash['newpage'].'" already exists, it could not be renamed';
			}
*/		}

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

		//override default index words because wiki pages have data in non-liberty tables (description in this case)
		$this->mInfo['index_data'] = ( !empty( $pParamHash['content_store']['title'] ) ? $pParamHash['content_store']['title'] : '').' '.$pParamHash['edit'].' '.( !empty( $pParamHash['page_store']['description'] ) ? $pParamHash['page_store']['description'] : '' );

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
			if( LibertyAttachable::expunge() ) {
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
	function storeFootnote($pUserId, $data) {
		if( $this->verifyId( $this->mPageId ) ) {
			$querydel = "delete from `".BIT_DB_PREFIX."wiki_footnotes` where `user_id`=? and `page_id`=?";
			$this->mDb->query( $querydel, array( $pUserId, $this->mPageId ) );
			$query = "insert into `".BIT_DB_PREFIX."wiki_footnotes`(`user_id`,`page_id`,`data`) values(?,?,?)";
			$this->mDb->query( $query, array( $pUserId, $this->mPageId, $data ) );
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
	* Generates the URL to this wiki page
	* @param pExistsHash the hash that was returned by LibertyContent::pageExists
	* @return the link to display the page.
	*/
	function getDisplayUrl( $pPageName=NULL, $pMixed=NULL ) {
		global $gBitSystem;
		if( empty( $this->mPageName ) && !empty( $pMixed['title'] )) {
			$pPageName = $pMixed['title'];
		}

		if( empty( $pPageName ) && !empty( $this->mPageName )) {
			$pPageName = $this->mPageName;
		}

		if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'pretty_urls_extended' ) ) {
			$rewrite_tag = $gBitSystem->isFeatureActive( 'pretty_urls_extended' ) ? 'view/':'';
			$baseUrl = WIKI_PKG_URL . $rewrite_tag;
			$baseUrl .= urlencode( $pPageName );
		}
		else {
			$baseUrl = WIKI_PKG_URL . 'index.php?page=';
			$baseUrl .= urlencode( $pPageName );
		}
		return $baseUrl;
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
					$desc = empty( $exists['description'] ) ? $exists['title'] : $exists['description'];
				}
				$ret = '<a title="'.htmlspecialchars( $desc ).'" href="'.BitPage::getDisplayUrl( $exists['title'] ).'">'.htmlspecialchars( $pPageName ).'</a>';
			} else {
				if( $gBitUser->hasPermission( 'p_wiki_edit_page' ) ) {
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
   	 * Methods to cache and handle the cached version of wiki pages
	 * to prevent parsing large pages.
	 */
   	/**
   	 * Save cached version of page to store
   	 * @param cache Data to be cached
	 */
	function setPageCache( $cache ) {
		if( $this->verifyId( $this->mPageId ) ) {
			$query = "update `".BIT_DB_PREFIX."wiki_pages` set `wiki_cache`=? where `page_id`=?";
			$this->mDb->query( $query, array( $cache, $this->mPageId ) );
		}
	}

   	/**
   	 * Get cached version of page from store
   	 * @param page ? Not used
	 */
	function get_cache_info($page) {
		if( $this->verifyId( $this->mPageId ) ) {
			$query = "select `page_cache`,`cache_timestamp` from `".BIT_DB_PREFIX."wiki_pages` where `page_id`=?";
			$result = $this->mDb->query( $query, array( $this->mPageId ) );
			return $result->fetchRow();
		}
	}

   	/**
   	 * Update cached version of page in store
   	 * @param data Data to be cached
	 */
	function updateCache( $data ) {
		if( $this->verifyId( $this->mPageId ) ) {
			global $gBitSystem;
			$now = $gBitSystem->getUTCTime();
			$query = "update `".BIT_DB_PREFIX."wiki_pages` set `page_cache`=?, `cache_timestamp`=$now where `page_id`=?";
			$result = $this->mDb->query( $query, array( $data, $this->mPageId ) );
			return true;
		}
	}

   	/**
   	 * Flag cached version as out of date
   	 * Cache will be updated next time the page is accessed
	 */
	function invalidateCache() {
		if( $this->verifyId( $this->mPageId ) ) {
			$query = "UPDATE `".BIT_DB_PREFIX."wiki_pages` SET `cache_timestamp`=? WHERE `page_id`=?";
			$this->mDb->query( $query, array( 0, $this->mPageId ) );
		}
	}

	// this was the original set of parameters
	// function getList( $offset = 0, $max_records = -1, $sort_mode = 'title_desc', $find = '', $pUserId=NULL, $pExtras=FALSE, $pOrphansOnly=FALSE, $pGetData=FALSE, $pFilterAuthor='', $pFilterLastEditor='' ) {
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
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

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
					wp.`page_id`, wp.`wiki_page_size` as `len`, wp.`description`, wp.`edit_comment`, wp.`content_id`, wp.`flag`,
					lc.`title`, lc.`format_guid`, lc.`last_modified`, lc.`created`, lc.`ip`, lc.`version`,
					lch.`hits` $get_data $selectSql
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`user_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_hits` lch ON (lc.`content_id` = lch.`content_id`)
					$joinSql
				WHERE lc.`content_type_guid`=? $whereSql
				ORDER BY ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
			$query_cant = "
				SELECT COUNT(*)
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`user_id` )
					$joinSql
				WHERE lc.`content_type_guid`=? $whereSql
				";
		} else {
			$query = "SELECT 
					uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name, uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name,
					wp.`page_id`, wp.`wiki_page_size` AS `len`,wp.`description`, wp.`edit_comment`, wp.`content_id`, wp.`flag`,
					lc.`title`, lc.`format_guid`, lc.`last_modified`, lc.`created`, lc.`ip`, lc.`version`,
					lch.`hits` $get_data $selectSql
				FROM `".BIT_DB_PREFIX."wiki_pages` wp
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = lc.`user_id` )
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = lc.`user_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_links` lcl ON (wp.`content_id` = lcl.`to_content_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_hits` lch ON (lc.`content_id` = lch.`content_id`)
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
		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
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

		$pListHash['cant'] = $cant;
		LibertyContent::postGetList( $pListHash );

		return $ret;
	}

	// Overriding the LibertyContet function to include decriptions from wiki_pages table.
	function setIndexData( $pContentId = 0 ) {
		global $gBitSystem ;
		if ( $pContentId == 0 ) $pContentId = $this->mContentId;
		$sql = "SELECT lc.`title`, lc.`data`, uu.`login`, uu.`real_name`, wp.`description` " .
				"FROM `" . BIT_DB_PREFIX . "liberty_content` lc " .  
				"INNER JOIN `" . BIT_DB_PREFIX . "users_users` uu ON uu.`user_id`    = lc.`user_id` " . 
				"INNER JOIN `" . BIT_DB_PREFIX . "wiki_pages`  wp ON lc.`content_id` = wp.`content_id` " .
				"WHERE lc.`content_id` = ?" ;
		$res = $gBitSystem->mDb->getRow($sql, array($pContentId));
		if (!(isset($this->mInfo['no_index']) and $this->mInfo['no_index'] == true)) {
			$this->mInfo['index_data'] = $res["title"] . " " . $res["data"] . " " . $res["login"] . " " . $res["real_name"] . ' ' . $res["description"] ;
		}
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
}

define('PLUGINS_DIR', WIKI_PKG_PATH.'plugins');
/**
 * @package wiki
 */
class WikiLib extends BitPage {
	function WikiLib() {
		BitPage::BitPage();
	}

	// 29-Jun-2003, by zaufi
	// The 2 functions below contain duplicate code
	// to remove <PRE> tags... (moreover I copy this code
	// from gBitSystem.php, and paste to artlib.php, bloglib.php
	// and wikilib.php)
	// TODO: it should be separate function to avoid
	// maintain 3 pieces... (but I don't know PHP and TIKI
	// architecture very well yet to make this :()
	//Special parsing for multipage articles
	function countPages($data) {
/*

		SPIDER KILL - 2005-05-16 - This was causing apache segfaults

		// Temporary remove <PRE></PRE> secions to protect
		// from broke <PRE> tags and leave well known <PRE>
		// behaviour (i.e. type all text inside AS IS w/o
		// any interpretation)

		$preparsed = array();
		preg_match_all("/(<[Pp][Rr][Ee]>)((.|\n)*?)(<\/[Pp][Rr][Ee]>)/", $data, $preparse);
		$idx = 0;
		foreach (array_unique($preparse[2])as $pp) {
			$key = md5(BitSystem::genPass());
			$aux["key"] = $key;
			$aux["data"] = $pp;
			$preparsed[] = $aux;
			$data = str_replace($preparse[1][$idx] . $pp . $preparse[4][$idx], $key, $data);
			$idx = $idx + 1;
		}
		$parts = explode("...page...", $data);
		return count($parts);
*/
		// we always have at least one page
		return( (preg_match_all( '/'.(defined('PAGE_SEP') ? preg_quote(PAGE_SEP) : '\.\.\.page\.\.\.').'/', $data, $matches ) + 1) );
	}

	function get_page($data, $i) {
/*

		SPIDER KILL - 2005-05-16 - This was causing apache segfaults

		// Temporary remove <PRE></PRE> secions to protect
		// from broke <PRE> tags and leave well known <PRE>
		// behaviour (i.e. type all text inside AS IS w/o
		// any interpretation)

		$preparsed = array();
		preg_match_all("/(<[Pp][Rr][Ee]>)((.|\n)*?)(<\/[Pp][Rr][Ee]>)/", $data, $preparse);
		$idx = 0;
		foreach (array_unique($preparse[2])as $pp) {
			$key = md5(BitSystem::genPass());
			$aux["key"] = $key;
			$aux["data"] = $pp;
			$preparsed[] = $aux;
			$data = str_replace($preparse[1][$idx] . $pp . $preparse[4][$idx], $key, $data);
			$idx = $idx + 1;
		}
*/		// Get slides
		$parts = explode(defined('PAGE_SEP') ? PAGE_SEP : "...page...", $data);
		if (substr($parts[$i - 1], 1, 5) == "<br/>")
			$ret = substr($parts[$i - 1], 6);
		else
			$ret = $parts[$i - 1];
/*		// Replace back <PRE> sections
		foreach ($preparsed as $pp)
			$ret = str_replace($pp["key"], "<pre>" . $pp["data"] . "</pre>", $ret);
*/
		return $ret;
	}

	// Like pages are pages that share a word in common with the current page
	function get_like_pages($page) {
		$ret = array();
		if( !empty( $pPageName ) ) {
			preg_match_all("/([A-Z])([a-z]+)/", $page, $words);
			// Add support to ((x)) in either strict or full modes
			preg_match_all("/(([A-Za-z]|[\x80-\xFF])+)/", $page, $words2);
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
/*

	DEPRECTATED - spider 2005-10-07

	function get_user_pages( $pUserId, $max, $who='user_id') {
		if( $pUserId ) {
			$query = "SELECT lc.`title` as `title`
					  FROM `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`)
					  WHERE `$who`=?";

			$result = $this->mDb->query($query,array($pUserId),$max);
			$ret = array();

			while ($res = $result->fetchRow()) {
			$ret[] = $res;
			}

			return $ret;
		}
	}
*/

/* ======================================== NOT USED ANYWHERE - not sure how to calculate a ranking of wiki pages anyway. what are the criteria and what does it say about a page? - xing
	// This function calculates the page_ranks for the wiki_pages
	// it can be used to compute the most relevant pages
	// according to the number of links they have
	// this can be a very interesting ranking for the Wiki
	// More about this on version 1.3 when we add the page_rank
	// column to wiki_pages
	function page_rank($loops = 16) {
		$query = "select `content_id`, lc.`title`  from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( wp.`content_id`=lc.`content_id` ";
		$result = $this->mDb->query($query,array());
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[$res["content_id"]] = $res["title"];
		}

		// Now calculate the loop
		$pages = array();

		foreach( array_keys( $ret ) as $conId ) {
			$val = 1 / count($ret);

			$pages[$conId] = $val;
			// Fixed query.  -rlpowell
			$query = "update `".BIT_DB_PREFIX."wiki_pages` set `page_rank`=? where `content_id`= ?";
			$result = $this->mDb->query($query, array((int)$val, $conId) );
		}

		for ($i = 0; $i < $loops; $i++) {
			foreach ($pages as $pagename => $rank) {
				// Get all the pages linking to this one
				// Fixed query.  -rlpowell
				$query = "select `from_content_id`  from `".BIT_DB_PREFIX."liberty_content_links` where `to_content_id` = ?";
				$result = $this->mDb->query($query, array( $pagename ) );
				$sum = 0;

				while ($res = $result->fetchRow()) {
				$linking = $res["from_page"];

				if (isset($pages[$linking])) {
					// Fixed query.  -rlpowell
					$q2 = "select count(*) from `".BIT_DB_PREFIX."liberty_content_links` where `from_page`= ?";
					$cant = $this->mDb->getOne($q2, array($linking) );
					if ($cant == 0) $cant = 1;
					$sum += $pages[$linking] / $cant;
				}
				}

				$val = (1 - 0.85) + 0.85 * $sum;
				$pages[$pagename] = $val;
				// Fixed query.  -rlpowell
				$query = "update `".BIT_DB_PREFIX."wiki_pages` set `page_rank`=? where `title`=?";
				$result = $this->mDb->query($query, array((int)$val, $pagename) );

				// Update
			}
		}

		arsort ($pages);
		return $pages;
	}
*/

	function wiki_page_graph(&$str, &$graph, $garg) {
		$page = $str['name'];
		$graph->addAttributes(array(
			'nodesep' => (isset($garg['att']['nodesep']))?$garg['att']['nodesep']:".1",
			'rankdir' => (isset($garg['att']['rankdir']))?$garg['att']['rankdir']:'LR',
			'size' => (isset($garg['att']['size']))?$garg['att']['size']:'6',
			'bgcolor' => (isset($garg['att']['bgcolor']))?$garg['att']['bgcolor']:'transparent',
			'URL' => WIKI_PKG_URL.'index.php'
		));
		$graph->addNode("$page", array(
			'URL' => WIKI_PKG_URL."index.php?page=" . urlencode(addslashes($page)),
			'label' => "$page",
			'fontname' => (isset($garg['node']['fontname']))?$garg['node']['fontname']:"Verdana",
			'fontsize' => (isset($garg['node']['fontsize']))?$garg['node']['fontsize']:'10',
			'shape' => (isset($garg['node']['shape']))?$garg['node']['shape']:'ellipse',
			'color' => (isset($garg['node']['color']))?$garg['node']['color']:'#AAAAAA',
			'style' => (isset($garg['node']['style']))?$garg['node']['style']:'filled',
			'fillcolor' => (isset($garg['node']['fillcolor']))?$garg['node']['fillcolor']:'#FFFFFF',
			'width' => (isset($garg['node']['width']))?$garg['node']['width']:'.5',
			'height' => (isset($garg['node']['height']))?$garg['node']['height']:'.25'
		));
		//print("add node $page<br/>");
		foreach ($str['pages'] as $neig) {
			$this->wiki_page_graph($neig, $graph, $garg);
			$graph->addEdge(array("$page" => $neig['name']), array(
				'color' => (isset($garg['edge']['color']))?$garg['edge']['color']:'#998877',
				'style' => (isset($garg['edge']['style']))?$garg['edge']['style']:'solid',
			));
			//print("add edge $page to ".$neig['name']."<br/>");
		}
	}

	// This funcion return the $limit most accessed pages
	// it returns title and hits for each page
	function get_top_pages($limit) {
		$query = "select `title` , `hits`
		from `".BIT_DB_PREFIX."wiki_pages` JOIN `".BIT_DB_PREFIX."liberty_content_hits` 
			on  `".BIT_DB_PREFIX."wiki_pages`.`content_id` = `".BIT_DB_PREFIX."liberty_content_hits`.`content_id`)
		order by `hits` desc";

		$result = $this->mDb->query($query, array(),$limit);
		$ret = array();

		while ($res = $result->fetchRow()) {
		$aux["title"] = $res["title"];

		$aux["hits"] = $res["hits"];
		$ret[] = $aux;
		}

		return $ret;
	}

	// Returns the name of "n" random pages
	function get_random_pages( $pNumPages=10 ) {
		$ret = NULL;
		$query = "select `content_id`, `title`  from `".BIT_DB_PREFIX."liberty_content` WHERE `content_type_guid`='".BITPAGE_CONTENT_TYPE_GUID."' ORDER BY ".$this->mDb->convertSortmode( 'random' );
		$rs = $this->mDb->query( $query, array(), $pNumPages );
		while( $rs && !$rs->EOF ) {
			$ret[$rs->fields['content_id']]['title'] = $rs->fields['title'];
			$ret[$rs->fields['content_id']]['display_url'] = $this->getDisplayUrl( $rs->fields['title'] );
			$rs->MoveNext();
		}

		return $ret;
	}

	function get_graph_map($page, $level, $garg) {
		include_once( UTIL_PKG_PATH.'GraphViz.php' );
		$str = $this->wiki_get_link_structure($page, $level);
		$graph = new Image_GraphViz();
		$this->wiki_page_graph($str, $graph, $garg);
		return $graph->fetch( 'cmap' );
	}

	function wiki_get_link_structure($page, $level) {
		$query = "select lc2.`title` from `".BIT_DB_PREFIX."liberty_content_links` lcl
			INNER JOIN liberty_content lc1 ON lc1.`content_id` = lcl.`from_content_id`
			INNER JOIN liberty_content lc2 ON lc2.`content_id` = lcl.`to_content_id`
			WHERE lc1.`title`=?";
		$result = $this->mDb->query($query,array($page));
		$aux['pages'] = array();
		$aux['name'] = $page;
		while ($res = $result->fetchRow()) {
			if ($level) {
				$aux['pages'][] = $this->wiki_get_link_structure($res['title'], $level - 1);
			} else {
				$inner['name'] = $res['title'];
				$inner['pages'] = array();
				$aux['pages'][] = $inner;
			}
		}
		return $aux;
	}

	function wiki_link_structure() {
		$query = "select `title` from `".BIT_DB_PREFIX."wiki_pages` order by ".$this->mDb->convertSortmode("title_asc");
		$result = $this->mDb->query($query);
		while ($res = $result->fetchRow()) {
			print ($res["title"] . " ");
			$page = $res["title"];
			$query2 = "select `to_page` from `".BIT_DB_PREFIX."liberty_content_links` where `from_page`=?";
			$result2 = $this->mDb->query($query2, array( $page ) );
			$pages = array();
			while ($res2 = $result2->fetchRow()) {
			if (($res2["to_page"] <> $res["title"]) && (!in_array($res2["to_page"], $pages))) {
				$pages[] = $res2["to_page"];
				print ($res2["to_page"] . " ");
			}
			}
			print ("\n");
		}
	}
	/*shared*/
	function list_received_pages($offset, $max_records, $sort_mode = 'title_asc', $find) {
		$bindvars = array();
		if ($find) {
		$findesc = '%'.strtoupper( $find ).'%';
		$mid = " where (UPPER(`pagename`) like ? or UPPER(`data`) like ?)";
		$bindvbars[] = $findesc;
		$bindvbars[] = $findesc;
		} else {
		$mid = "";
		}

		$query = "select * from `".BIT_DB_PREFIX."wiki_received_pages` $mid order by ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."wiki_received_pages` $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
		if ($this->pageExists($res["title"])) {
			$res["exists"] = 'y';
		} else {
			$res["exists"] = 'n';
		}

		$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function getDumpFile() {
		global $gBitSystem;
		return( $this->getStoragePath( $gBitSystem->getConfig( 'bitdomain' ), NULL, WIKI_PKG_NAME ).'dump.tar' );
	}

	function getDumpUrl() {
		global $gBitSystem;
		return( $this->getStorageUrl( $gBitSystem->getConfig( 'bitdomain' ), NULL, WIKI_PKG_NAME ).'dump.tar' );
	}

	// Dumps the database to dump/new.tar
	// changed for virtualhost support
	function dumpPages() {
		global $wiki_home_page, $gBitSystem, $gBitUser;

		$tar = new tar();
		$tar->addFile( $gBitSystem->getStyleCss() );
		// Foreach page
		$query = "select * from `".BIT_DB_PREFIX."wiki_pages`";
		$result = $this->mDb->query($query,array());

		$dumpFile = $this->getDumpFile();
		if( file_exists( $dumpFile ) ) {
			unlink( $dumpFile );
		}

		while ($res = $result->fetchRow()) {
			$title = $res["title"] . '.html';

			$dat = $this->parseData($res);
			// Now change index.php?page=foo to foo.html
			// and index.php to HomePage.html
			$dat = preg_replace("/index.php\?page=([^\'\"\$]+)/", "$1.html", $dat);
			$dat = preg_replace("/edit.php\?page=([^\'\"\$]+)/", "", $dat);
			//preg_match_all("/index.php\?page=([^ ]+)/",$dat,$cosas);
			//print_r($cosas);
			$data = "<html><head><title>" . $res["title"] . "</title><link rel='StyleSheet' href='".$gBitSystem->getStyleCss()."' type='text/css'></head><body><a class='wiki' href='$wiki_home_page.html'>home</a><br/><h1>" . $res["title"] . "</h1><div class='wikitext'>" . $dat . '</div></body></html>';
			$tar->addData($title, $data, $res["last_modified"]);
		}

		$tar->toTar( $dumpFile, FALSE );
		unset ($tar);
		$action = "dump created";
		$t = $gBitSystem->getUTCTime();
		$query = "insert into `".BIT_DB_PREFIX."liberty_action_log`(`log_message`,`content_id`,`last_modified`,`user_id`,`ip`,`error_message`) values(?,?,?,?,?,?)";
		$result = $this->mDb->query($query,array($action,1,$t,$gBitUser->mContentId,$_SERVER["REMOTE_ADDR"],''));
	}

	function list_extwiki($offset, $max_records, $sort_mode, $find) {
		$bindvars=array();
		if ($find) {
			$findesc = '%' . $find . '%';

			$mid = " where (`extwiki` like ? )";
			$bindvars[]=$findesc;
		} else {
			$mid = "";
		}

		$query = "select * from `".BIT_DB_PREFIX."wiki_ext` $mid order by ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."wiki_ext` $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function replace_extwiki($extwiki_id, $extwiki, $name) {
		// Check the name
		if ($extwiki_id) {
			$query = "update `".BIT_DB_PREFIX."wiki_ext` set `extwiki`=?,`name`=? where `extwiki_id`=?";
			$result = $this->mDb->query($query,array($extwiki,$name,$extwiki_id));
		} else {
			$query = "delete from `".BIT_DB_PREFIX."wiki_ext` where `name`=? and `extwiki`=?";
			$bindvars=array($name,$extwiki);
			$result = $this->mDb->query($query,$bindvars);
			$query = "insert into `".BIT_DB_PREFIX."wiki_ext`(`name`,`extwiki`)
                		values(?,?)";
			$result = $this->mDb->query($query,$bindvars);
		}

		// And now replace the perm if not created
		$perm_name = 'bit_p_extwiki_' . $name;
		$query = "delete from `".BIT_DB_PREFIX."users_permissions`where `perm_name`=?";
		$this->mDb->query($query,array($perm_name));
		$query = "insert into `".BIT_DB_PREFIX."users_permissions`(`perm_name`,`perm_desc`,`type`,`perm_level`) values
    			(?,?,?,?)";
		$this->mDb->query($query,array($perm_name,'Can use extwiki $extwiki','extwiki','editor'));
		return true;
	}

	function remove_extwiki($extwiki_id) {
		$info = $this->get_extwiki($extwiki_id);

		$perm_name = 'bit_p_extwiki_' . $info['name'];
		$query = "delete from `".BIT_DB_PREFIX."users_permissions` where `perm_name`=?";
		$this->mDb->query($query,array($perm_name));
		$query = "delete from `".BIT_DB_PREFIX."wiki_ext` where `extwiki_id`=?";
		$this->mDb->query($query,array($extwiki_id));
		return true;
	}

	function get_extwiki($extwiki_id) {
		$query = "select * from `".BIT_DB_PREFIX."wiki_ext` where `extwiki_id`=?";

		$result = $this->mDb->query($query,array($extwiki_id));

		if (!$result->numRows())
			return false;

		$res = $result->fetchRow();
		return $res;
	}

/* ================== WIKI TAG FUNCTIONS ============== */
	function tag_exists($tag) {
		$query = "select distinct `tag_name` from `".BIT_DB_PREFIX."wiki_tags` where `tag_name` = ?";

		$result = $this->mDb->query($query,array($tag));
		return $result->numRows($result);
	}

	function remove_tag($tagname) {
		global $wiki_home_page, $gBitUser, $gBitSystem;

		$this->mDb->StartTrans();
		$query = "delete from `".BIT_DB_PREFIX."wiki_tags` where `tag_name`=?";
		$result = $this->mDb->query($query,array($tagname));
		$action = "removed tag: $tagname";
		$t = $gBitSystem->getUTCTime();
		$homeContentId = $this->mDb->getOne( "SELECT `content_id` from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON(wp.`content_id`=lc.`content_id`) WHERE lc.`title`=?", array( $wiki_home_page ) );
		$query = "insert into `".BIT_DB_PREFIX."liberty_action_log` (`content_id`, `log_message`, `last_modified`, `user_id`, `ip`, `error_message`) values ( ?,?,?,?,?,?,? )";
		$result = $this->mDb->query($query,array($homeContentId, $action,$wiki_home_page,$t,$gBitUser->mUserId,$_SERVER["REMOTE_ADDR"],''));
		$this->mDb->CompleteTrans();
		return true;
	}

	function get_tags() {
		$query = "select distinct `tag_name` from `".BIT_DB_PREFIX."wiki_tags`";

		$result = $this->mDb->query($query,array());
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res["tag_name"];
		}

		return $ret;
	}

	// This function can be used to store the set of actual pages in the "tags"
	// table preserving the state of the wiki under a tag name.
	function create_tag($tagname, $comment = '') {
		global $wiki_home_page, $gBitUser, $gBitSystem;

		$this->mDb->StartTrans();
		$query = "select * from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( wp.`content_id`=lc.`content_id` )";
		$result = $this->mDb->query($query,array());

		while ($res = $result->fetchRow()) {
			$data = $res["data"];
			$description = $res["description"];
			$query = "delete from `".BIT_DB_PREFIX."wiki_tags`where `tag_name`=? and `page_id`=?";
			$this->mDb->query($query,array($tagname,$res["page_id"]));
			$query = "insert into `".BIT_DB_PREFIX."wiki_tags`(`page_id`,`tag_name`,`page_name`,`hits`,`data`,`last_modified`,`error_message`,`version`,`user_id`,`ip`,`flag`,`description`)
                		values(?,?,?,?,?,?,?,?,?,?,?,?)";
			$result2 = $this->mDb->query($query,array($res["page_id"],$tagname,$res["title"],$res["hits"],$data,$res["last_modified"],$res["edit_comment"],$res["version"],$res["user_id"],$res["ip"],$res["flag"],$description));
		}

		$homeContentId = $this->mDb->getOne( "SELECT `content_id` from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON(wp.`content_id`=lc.`content_id`) WHERE lc.`title`=?", array( $wiki_home_page ) );
		$action = "created tag: $tagname";
		$t = $gBitSystem->getUTCTime();
		$query = "insert into `".BIT_DB_PREFIX."liberty_action_log`(`content_id`,`log_message`,last_modified`,`user_id`,`ip`,`error_message`) values(?,?,?,?,?,?,?)";
		$result = $this->mDb->query($query,array($homeContentId,$action,$wiki_home_page,$t,$gBitUser->mUserId,$_SERVER["REMOTE_ADDR"],$comment));
		$this->mDb->CompleteTrans();
		return true;
	}

	// This funcion recovers the state of the wiki using a tag_name from the
	// tags table
	function restore_tag($tagname) {
		global $wiki_home_page, $gBitUser, $gBitSystem;
		require_once( WIKI_PKG_PATH.'BitPage.php' );

		$this->mDb->StartTrans();
		$query = "update `".BIT_DB_PREFIX."wiki_pages` set `cache_timestamp`=0";
		$this->mDb->query($query,array());
		$query = "select *, `data` AS `edit`, `page_name` AS `title` FROM `".BIT_DB_PREFIX."wiki_tags` where `tag_name`=?";
		$result = $this->mDb->query($query,array($tagname));

		while ($res = $result->fetchRow()) {
			$tagPage = new BitPage( $res["page_id"] );
			$tagPage->store( $res );
		}

		$homePageId = $this->mDb->getOne( "SELECT `page_id` from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON(wp.`content_id`=lc.`content_id`) WHERE lc.`title`=?", array( $wiki_home_page ) );
		$action = "recovered tag: $tagname";
		$t = $gBitSystem->getUTCTime();
		}
}

/**
 * the wikilib class
 * @global WikiLib $wikilib
 */
global $wikilib;
// Perhaps someone overrode the wikilib class to do there own magic, and have alread instantiated...
if( empty( $wikilib ) ) {
	$wikilib = new WikiLib();
}

?>
