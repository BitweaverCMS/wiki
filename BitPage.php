<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/BitPage.php,v 1.2.2.34 2005/10/29 09:53:06 squareing Exp $
 * @package wiki
 *
 * @author spider <spider@steelsun.com>
 *
 * @version $Revision: 1.2.2.34 $ $Date: 2005/10/29 09:53:06 $ $Author: squareing $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: BitPage.php,v 1.2.2.34 2005/10/29 09:53:06 squareing Exp $
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
		$this->mPageId = $pPageId;
		$this->mContentId = $pContentId;
		$this->mContentTypeGuid = BITPAGE_CONTENT_TYPE_GUID;
	}

	function findByPageName( $pPageName, $pUserId=NULL ) {
		$userWhere = '';
		$bindVars = array( $pPageName, $this->mContentTypeGuid );
		if( !empty( $pUserId ) ) {
			$userWhere = " AND tc.`user_id`=?";
			array_push( $bindVars, $pUserId );
		}
		$ret = $this->mDb->getOne("select `page_id` from `".BIT_DB_PREFIX."tiki_pages` tp INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`) where tc.`title`=? AND tc.`content_type_guid`=? $userWhere", $bindVars );
		return $ret;
	}

	function load() {
		if( $this->verifyId( $this->mPageId ) || $this->verifyId( $this->mContentId ) ) {
			global $gBitSystem;
			$lookupColumn = !empty( $this->mPageId )? 'page_id' : 'content_id';

			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			$this->getServicesSql( 'content_load_function', $selectSql, $joinSql, $whereSql, $bindVars );

			array_push( $bindVars, $lookupId = !empty( $this->mPageId )? $this->mPageId : $this->mContentId );
			$query = "select tp.*, tc.*,
					  uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
					  uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
					  FROM `".BIT_DB_PREFIX."tiki_pages` tp
						INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`) $joinSql
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = tc.`modifier_user_id`)
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = tc.`user_id`)
					  WHERE tp.`$lookupColumn`=? $whereSql";
			$result = $this->mDb->query( $query, $bindVars );

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mPageId = $result->fields['page_id'];
				$this->mPageName = $result->fields['title'];
				$this->mInfo['creator'] = (isset( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = (isset( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				// Save some work if feature_wiki_attachments are not active
				if( $gBitSystem->isFeatureActive( 'feature_wiki_attachments' ) ) {
					LibertyAttachable::load();
				} else {
					LibertyContent::load();
				}
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

			$pParamHash['page_store']['page_size'] = !empty( $pParamHash['edit'] ) ? strlen( $pParamHash['edit'] ) : 0;

			$table = BIT_DB_PREFIX."tiki_pages";
            if( $this->mPageId ) {
				$this->invalidateCache();
				if( !empty( $pParamHash['force_history'] ) || ( empty( $pParamHash['minor'] ) && !empty( $this->mInfo['version'] ) && $pParamHash['field_changed'] )) {
					if( $this->mPageName != 'SandBox' && empty( $pParamHash['has_no_history'] ) ) {
						$query = "insert into `".BIT_DB_PREFIX."tiki_history`( `page_id`, `version`, `last_modified`, `user_id`, `ip`, `comment`, `data`, `description`, `format_guid`) values(?,?,?,?,?,?,?,?,?)";
 						$result = $this->mDb->query( $query, array( $this->mPageId, (int)$this->mInfo['version'], (int)$this->mInfo['last_modified'] , $this->mInfo['modifier_user_id'], $this->mInfo['ip'], $this->mInfo['comment'], $this->mInfo['data'], $this->mInfo['description'], $this->mInfo['format_guid'] ) );
					}
					$action = "Created";
					$mailEvents = 'wiki_page_changes';
				}

				$locId = array ( "name" => "page_id", "value" => $this->mPageId );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['page_store'], $locId );

			} else {
				$pParamHash['page_store']['content_id'] = $pParamHash['content_id'];
				if( isset( $pParamHash['page_id'] ) && is_numeric( $pParamHash['page_id'] ) ) {
					// if pParamHash['page_id'] is set, some is requesting a particular page_id. Use with caution!
					$pParamHash['page_store']['page_id'] = $pParamHash['page_id'];
				} else {
					$pParamHash['page_store']['page_id'] = $this->mDb->GenID( 'tiki_pages_page_id_seq');
				}
				$this->mPageId = $pParamHash['page_store']['page_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['page_store'] );
			}
			// Access new data for notifications
			$this->load();

			if( isset( $mailEvents ) ) {
				global $notificationlib, $gBitUser, $gBitSystem, $gBitSmarty;
				include_once( KERNEL_PKG_PATH.'notification_lib.php' );
				$notificationlib->post_content_event($this->mContentId, $this->mInfo['content_type_guid'], 'wiki', $this->mInfo['title'], $this->mInfo['modifier_user'], $this->mInfo['comment'], $this->mInfo['data']);

				if( $gBitSystem->isFeatureActive( 'feature_user_watches') ) {
					$nots = $gBitUser->get_event_watches( 'wiki_page_changed', $this->mPageId );

					foreach ($nots as $not) {
#						if ($wiki_watch_editor != 'y' && $not['user_id'] == $user)
#							break;
						$gBitSmarty->assign('mail_site', $_SERVER["SERVER_NAME"]);

						$gBitSmarty->assign('mail_page', $this->mInfo['title']);
						$gBitSmarty->assign('mail_date', $gBitSystem->getUTCTime());
						$gBitSmarty->assign('mail_user', $this->mInfo['modifier_user']);
						$gBitSmarty->assign('mail_comment', $this->mInfo['comment']);
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
						@mail($email_to, tra('Wiki page'). ' ' . $this->mInfo['title'] . ' ' . tra('changed'), $mail_data, "From: ".$gBitSystem->getPreference( 'sender_email' )."\r\nContent-type: text/plain;charset=utf-8\r\n");
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
		global $gBitUser, $user, $bit_p_rename, $gBitSystem;

		// make sure we're all loaded up of we have a mPageId
		if( $this->mPageId && empty( $this->mInfo ) ) {
			$this->load();
		}

		if( !empty( $this->mInfo['content_id'] ) ) {
			$pParamHash['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( !empty( $pParamHash['content_id'] ) ) {
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
			$this->mErrors['title'] = 'You must specify a name';
		} elseif( !empty( $pParamHash['title']) || !empty($this->mPageName))  {
			if( empty( $this->mPageId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this page.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
					if ($gBitSystem->isFeatureActive( 'feature_allow_dup_wiki_page_names')) {
						# silently allow pages with duplicate names to be created
					} else {
						if( $this->pageExists( $pParamHash['title'] ) ) {
							$this->mErrors['title'] = 'Page "'.$pParamHash['title'].'" already exists. Please choose a different name.';
						}
					}
				}
			} else {
				$pParamHash['content_store']['title'] = ( isset( $pParamHash['title'] ) ) ? substr( $pParamHash['title'], 0, 160 ) : $this->mPageName;
				if ($gBitSystem->isFeatureActive( 'feature_allow_dup_wiki_page_names')) {
					# silently allow pages with duplicate names to be created
				} else {
					if( $gBitUser->hasPermission( 'bit_p_rename' )
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

		if( empty( $pParamHash['comment'] ) ) {
			unset( $pParamHash['comment'] );
		} else {
			$pParamHash['page_store']['comment'] = substr( $pParamHash['comment'], 0, 200 );
		}

		if( !empty( $pParamHash['minor'] ) && $this->isValid() ) {
			// we can only minor save over our own versions
			if( !$gBitUser->isRegistered() || ($this->mInfo['modifier_user_id'] != $gBitUser->mUserId && !$gBitUser->isAdmin()) ) {
				unset( $pParamHash['minor'] );
			}
		}

		if( empty( $this->mPageId ) ) {
			$pParamHash['page_store']['version'] = 1;
		} else {
			$pParamHash['page_store']['version'] = $this->mInfo['version'] + 1;
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
			$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_pages` WHERE `content_id` = ?";
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
		return( !empty( $this->mPageId ) );
	}


    function isLocked() {
		$ret = FALSE;
		if( $this->mPageId ) {
			if( empty( $this->mInfo ) ) {
				$this->load();
			}
			$ret = (isset( $this->mInfo["flag"] ) && $this->mInfo["flag"] == 'L');
		}
		return( $ret );
    }

	function setLock( $pLock, $pModUserId=NULL ) {
		if( $this->mPageId ) {
			$bindVars = array();
			$userSql = '';
			if( $pModUserId ) {
				$userSql = "`modifier_user_id`=?, ";
				array_push( $bindVars, $pModUserId );
			}
			array_push( $bindVars, $pLock, $this->mPageId );
			$query = "update `".BIT_DB_PREFIX."tiki_pages` SET $userSql `flag`=? where `page_id`=?";
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

    /**
     * Removes last version of the page (from pages) if theres some
	 * version in the tiki_history then the last version becomes the actual version
	 */
	function removeLastVersion( $comment = '' ) {
		if( $this->mPageId ) {
			global $gBitSystem;
			$this->invalidateCache();
			$query = "select * from `".BIT_DB_PREFIX."tiki_history` where `page_id`=? order by ".$this->mDb->convert_sortmode("last_modified_desc");
			$result = $this->mDb->query($query, array( $this->mPageId ) );
			if ($result->numRows()) {
				// We have a version
				$res = $result->fetchRow();
				$this->rollbackVersion( $res["version"] );
				$this->expungeVersion( $res["version"] );
			} else {
				$this->remove_all_versions($page);
			}
			$action = "Removed last version";
			$t = $gBitSystem->getUTCTime();
			$query = "insert into `".BIT_DB_PREFIX."tiki_actionlog`( `action`, `page_id`, `last_modified`, `user_id`, `ip`, `comment`) values( ?, ?, ?, ?, ?, ?)";
			$result = $this->mDb->query($query, array( $action, $this->mPageId, $t, ROOT_USER_ID, $_SERVER["REMOTE_ADDR"], $comment ) );
		}
	}

	// *********  Footnote functions for the wiki ********** //
	/**
	 *  Store footnote
	 */
	function storeFootnote($pUserId, $data) {
		if( $this->mPageId ) {
			$querydel = "delete from `".BIT_DB_PREFIX."tiki_page_footnotes` where `user_id`=? and `page_id`=?";
			$this->mDb->query( $querydel, array( $pUserId, $this->mPageId ) );
			$query = "insert into `".BIT_DB_PREFIX."tiki_page_footnotes`(`user_id`,`page_id`,`data`) values(?,?,?)";
			$this->mDb->query( $query, array( $pUserId, $this->mPageId, $data ) );
		}
	}

	/**
	 *  Delete footnote
	 */
	function expungeFootnote( $pUserId ) {
		if( $this->mPageId ) {
			$query = "delete from `".BIT_DB_PREFIX."tiki_page_footnotes` where `user_id`=? and `page_id`=?";
			$this->mDb->query($query,array($pUserId,$this->mPageId));
		}
	}

	/**
	 *  Get footnote
	 */
	function getFootnote( $pUserId ) {
		if( $this->mPageId ) {
			$count = $this->mDb->getOne( "select count(*) from `".BIT_DB_PREFIX."tiki_page_footnotes` where `user_id`=? and `page_id`=?", array( $pUserId, $this->mPageId ) );
			if( $count ) {
				return $this->mDb->getOne("select `data` from `".BIT_DB_PREFIX."tiki_page_footnotes` where `user_id`=? and `page_id`=?",array( $pUserId, $this->mPageId ) );
			}
		}
	}

    /**
    * Generates a link to a wiki page within lists of pages
    * @param pExistsHash the hash that was returned by LibertyContent::pageExists
    * @return the link to display the page.
    */
	function getListLink( $pPageHash ) {
		return BitPage::getDisplayUrl($pPageHash['title']);
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
	function getDisplayUrl( $pPageName=NULL ) {
		global $gBitSystem;
		if( empty( $pPageName ) ) {
			$pPageName = $this->mPageName;
		}
		$rewrite_tag = $gBitSystem->isFeatureActive( 'feature_pretty_urls_extended' ) ? 'view/':'';
		if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'feature_pretty_urls_extended' ) ) {
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
			if( is_array( $pExistsHash ) ) {
				if( is_array( current( $pExistsHash ) ) ) {
					$exists = $pExistsHash[0];
				} else {
					$exists = $pExistsHash;
				}

				// we have a multi-demensional array (likely returned from LibertyContent::pageExists() ) - meaning we potentially have multiple pages with the same name
				if( count( $pExistsHash ) > 1 ) {
					$desc = tra( 'Multiple pages with this name' );
					$ret = "<a title=\"$desc\" href=\"" .  BitPage::getDisplayUrl( $exists['title'] ) . "\">$pPageName</a>";
				} elseif( count( $pExistsHash ) == 1 ) {
					$desc = $exists['description'];
					$ret = "<a title=\"$desc\" href=\"" . BitPage::getDisplayUrl( $exists['title'] ) . "\">$pPageName</a>";
				} else {
					if( $gBitUser->hasPermission( 'bit_p_edit' ) ) {
						$ret = "<a href=\"".WIKI_PKG_URL."edit.php?page=" . urlencode( $exists['title'] ). "\" class=\"create\">$pPageName</a>";
					} else {
						$ret = $pPageName;
					}
				}
			} else {
				if( $gBitUser->hasPermission( 'bit_p_edit' ) ) {
					$ret = "<a href=\"".WIKI_PKG_URL."edit.php?page=" . urlencode( $pPageName ). "\" class=\"create\">$pPageName</a>";
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
			$query = "SELECT tl.`from_content_id`, tc.`title`
					  FROM `".BIT_DB_PREFIX."tiki_links` tl INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tl.`from_content_id`=tc.`content_id`)
					  WHERE tl.`to_content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			$ret = array();
			while ( !$result->EOF ) {
				$ret[$result->fields["from_content_id"]] = $result->fields["title"];
				$result->MoveNext();
			}
			return $ret;
		}
    }


	// *********  History functions for the wiki ********** //
    /**
    * Get count of the number of historic records for the page
    * @return count
    */
	function getHistoryCount() {
		$ret = NULL;
		if( $this->isValid() ) {
			$query = "SELECT COUNT(*) AS `count`
					FROM `".BIT_DB_PREFIX."tiki_history`
					WHERE `page_id` = ?";
			$rs = $this->mDb->query($query, array($this->mPageId));
			$ret = $rs->fields['count'];
		}
		return $ret;
	}

    /**
    * Get complete set of historical data in order to display a given wiki page version
    * @param pExistsHash the hash that was returned by LibertyContent::pageExists
    * @return array of mInfo data
    */
	function getHistory( $pVersion=NULL, $pUserId=NULL, $pOffset = 0, $maxRecords = -1 ) {
		$ret = NULL;
		if( $this->isValid() ) {
			global $gBitSystem;
			$versionSql = '';
			if( !empty( $pUserId ) ) {
				$bindVars = array( $pUserId );
				$whereSql = ' th.`user_id`=? ';
			} else {
				$bindVars = array( $this->mPageId );
				$whereSql = ' th.`page_id`=? ';
			}
			if( !empty( $pVersion ) ) {
				array_push( $bindVars, $pVersion );
				$versionSql = ' AND th.`version`=? ';
			}
			$query = "SELECT tc.`title`, th.*,
						uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
						uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				   FROM `".BIT_DB_PREFIX."tiki_history` th INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp ON (tp.`page_id` = th.`page_id`) INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = th.`user_id`)
						LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = tc.`user_id`)
				   WHERE $whereSql $versionSql order by th.`version` desc";

			$result = $this->mDb->query( $query, $bindVars, $maxRecords, $pOffset );
			$ret = array();
			while( !$result->EOF ) {
				$aux = $result->fields;
				$aux['creator'] = (isset( $aux['creator_real_name'] ) ? $aux['creator_real_name'] : $aux['creator_user'] );
				$aux['editor'] = (isset( $aux['modifier_real_name'] ) ? $aux['modifier_real_name'] : $aux['modifier_user'] );
				array_push( $ret, $aux );
				$result->MoveNext();
			}
		}
		return $ret;
	}

	/**
	 * Roll back to a specific version of a page
	 * @param pVersion Version number to roll back to
	 * @param comment Comment text to be added to the action log
	 * @return TRUE if completed successfully
	 */
	function rollbackVersion( $pVersion, $comment = '' ) {
		$ret = FALSE;
		if( $this->isValid() ) {
			global $gBitUser,$gBitSystem;
			$this->mDb->StartTrans();
			// JHT - cache invalidation appears to be handled by store function - so don't need to do it here
			$query = "select *, `user_id` AS modifier_user_id, `data` AS `edit` from `".BIT_DB_PREFIX."tiki_history` where `page_id`=? and `version`=?";
			$result = $this->mDb->query($query,array( $this->mPageId, $pVersion ) );
			if( $result->numRows() ) {
				$res = $result->fetchRow();
				$res['comment'] = 'Rollback to version '.$pVersion.' by '.$gBitUser->getDisplayName();
				// JHT 2005-06-19_15:22:18
				// set ['force_history'] to
				// make sure we don't destory current content without leaving a copy in history
				// if rollback can destroy the current page version, it can be used
				// maliciously
				$res['force_history'] = 1;
				// JHT 2005-10-16_22:21:10
				// title must be set or store fails
				// we use current page name
				$res['title'] = $this->mPageName;
				if( $this->store( $res ) ) {
					$action = "Changed actual version to $pVersion";
					$t = $gBitSystem->getUTCTime();
					$query = "insert into `".BIT_DB_PREFIX."tiki_actionlog`(`action`,`page_id`,`last_modified`,`user_id`,`ip`,`comment`) values(?,?,?,?,?,?)";
					$result = $this->mDb->query($query,array($action,$this->mPageId,$t,ROOT_USER_ID,$_SERVER["REMOTE_ADDR"],$comment));
					$ret = TRUE;
				}
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}

	/**
	 * Removes a specific version of a page
	 * @param pVersion Version number to roll back to
	 * @param comment Comment text to be added to the action log
	 * @return TRUE if completed successfully
	 */
	function expungeVersion( $pVersion=NULL, $comment = '' ) {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			$bindVars = array( $this->mPageId );
			$versionSql = '';
			if( $pVersion ) {
				$versionSql = " and `version`=? ";
				array_push( $bindVars, $pVersion );
			}
			$hasRows = $this->mDb->getOne( "SELECT COUNT(`version`) FROM `".BIT_DB_PREFIX."tiki_history` WHERE `page_id`=? $versionSql ", $bindVars );
			$query = "delete from `".BIT_DB_PREFIX."tiki_history` where `page_id`=? $versionSql ";
			$result = $this->mDb->query( $query, $bindVars );
			if( $hasRows ) {
				global $gBitSystem;
				$action = "Removed version $pVersion";
				$t = $gBitSystem->getUTCTime();
				$query = "insert into `".BIT_DB_PREFIX."tiki_actionlog`(`action`,`page_id`,`last_modified`,`user_id`,`ip`,`comment`) values(?,?,?,?,?,?)";
				$result = $this->mDb->query($query,array($action,$this->mPageId,$t,ROOT_USER_ID,$_SERVER["REMOTE_ADDR"],$comment));
				$ret = TRUE;
			}
			$this->mDb->CompleteTrans();
		}
		return $ret;
	}

/*
	// Returns information about a specific version of a page
	function get_version($page_id, $version) {
		$query = "SELECT th.*, tc.`title`
				  FROM `".BIT_DB_PREFIX."tiki_history` th INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp ON (tp.`page_id` = th.`page_id`) INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
				  WHERE th.`page_id`=? and th.`version`=? ";
		$result = $this->mDb->query($query,array($page_id,$version));
		$res = $result->fetchRow();
		return $res;
	}
	// Returns all the versions for this page
	// without the data itself
	function get_page_history($pageId) {
		global $gBitSystem;
		$query = "SELECT tc.`title`, th.*,
					uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name, uue.`user_id` AS modifier_user_id,
					uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name, uuc.`user_id` AS creator_user_id

				  FROM `".BIT_DB_PREFIX."tiki_history` th INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp ON (tp.`page_id` = th.`page_id`) INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = th.`user_id`)
					INNER JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = tc.`user_id`)
				  WHERE th.`page_id` = ?
				  ORDER BY th.`version` desc";

		$result = $this->mDb->query($query,array($pageId));
		$ret = array();
		while ($res = $result->fetchRow()) {
			$aux = $res;
			$aux['creator'] = (isset( $res['creator_real_name'] ) ? $res['creator_real_name'] : $res['creator_user'] );
			$aux['editor'] = (isset( $res['modifier_real_name'] ) ? $res['modifier_real_name'] : $res['modifier_user'] );
			$aux["last_modified"] = $res["last_modified"];
			//$aux["percent"] = levenshtein($res["data"],$actual);
			$ret[] = $aux;
		}
		return $ret;
	}
*/

   	/**
   	 * Methods to cache and handle the cached version of wiki pages
	 * to prevent parsing large pages.
	 */
   	/**
   	 * Save cached version of page to store
   	 * @param cache Data to be cached
	 */
	function setPageCache( $cache ) {
		if( $this->mPageId ) {
			$query = "update `".BIT_DB_PREFIX."tiki_pages` set `wiki_cache`=? where `page_id`=?";
			$this->mDb->query( $query, array( $cache, $this->mPageId ) );
		}
	}

   	/**
   	 * Get cached version of page from store
   	 * @param page ? Not used
	 */
	function get_cache_info($page) {
		if( $this->mPageId ) {
			$query = "select `cache`,`cache_timestamp` from `".BIT_DB_PREFIX."tiki_pages` where `page_id`=?";
			$result = $this->mDb->query( $query, array( $this->mPageId ) );
			return $result->fetchRow();
		}
	}

   	/**
   	 * Update cached version of page in store
   	 * @param data Data to be cached
	 */
	function updateCache( $data ) {
		if( $this->mPageId ) {
			global $gBitSystem;
			$now = $gBitSystem->getUTCTime();
			$query = "update `".BIT_DB_PREFIX."tiki_pages` set `cache`=?, `cache_timestamp`=$now where `page_id`=?";
			$result = $this->mDb->query( $query, array( $data, $this->mPageId ) );
			return true;
		}
	}

   	/**
   	 * Flag cached version as out of date
   	 * Cache will be updated next time the page is accessed
	 */
	function invalidateCache() {
		if( $this->mPageId ) {
			$query = "UPDATE `".BIT_DB_PREFIX."tiki_pages` SET `cache_timestamp`=? WHERE `page_id`=?";
			$this->mDb->query( $query, array( 0, $this->mPageId ) );
		}
	}

   	/**
   	 * Generate list of pages
   	 * @param offset Number of the first record to list
   	 * @param maxRecords Number of records to list
   	 * @param sort_mode Order in which the records will be sorted
   	 * @param find Filter to be applied to the list
   	 * @param pUserId If set additionally filter on UserId
   	 * @param pExtras If set adds additional counts of links to and from each page
   	 *	This can take some time to calculate, and so should not normally be enabled
   	 * @param pOrphansOnly If Set list only unattached pages ( ones not used in other content )
	 */
	function getList($offset = 0, $maxRecords = -1, $sort_mode = 'title_desc', $find = '', $pUserId=NULL, $pExtras=FALSE, $pOrphansOnly=FALSE, $pGetData=FALSE ) {
		global $gBitSystem;
		if ($sort_mode == 'size_desc') {
			$sort_mode = 'page_size_desc';
		}

		if ($sort_mode == 'size_asc') {
			$sort_mode = 'page_size_asc';
		}

		$old_sort_mode = '';
		if (in_array($sort_mode, array(
				'versions_desc',
				'versions_asc',
				'links_asc',
				'links_desc',
				'backlinks_asc',
				'backlinks_desc'
				))) {
			$old_offset = $offset;

			$old_maxRecords = $maxRecords;
			$old_sort_mode = $sort_mode;
			$sort_mode = 'modifier_user_desc';
			$offset = 0;
			$maxRecords = -1;
		}

		$mid = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		if (is_array($find)) { // you can use an array of pages
			$mid = " AND tc.`title` IN (".implode(',',array_fill(0,count($find),'?')).")";
			$bindVars = array_merge($bindVars,$find);
		} elseif ( is_string($find) and $find != '' ) { // or a string
			$mid = " AND UPPER(tc.`title`) LIKE ? ";
			$bindVars = array_merge($bindVars,array('%' . strtoupper( $find ) . '%'));
		} elseif( !empty( $pUserId ) ) { // or a string
			$mid = " AND tc.`user_id` = ? ";
			$bindVars = array_merge($bindVars, array( $pUserId ));
		}

		if( $pGetData ) {
			$get_data = 'tc.`data`,';
		} else {
			$get_data = '';
		}

		$query = "SELECT
			uue.`login` AS modifier_user,
			uue.`real_name` AS modifier_real_name,
			uuc.`login` AS creator_user,
			uuc.`real_name` AS creator_real_name,
			`page_id`,
			`hits`,
			`page_size` as `len`,
			tc.`title`,
			tc.`format_guid`,
			tp.`description`,
			tc.`last_modified`,
			tc.`created`,
			$get_data
			`ip`,
			`comment`,
			`version`,
			`flag`,
			tp.`content_id`
				FROM `".BIT_DB_PREFIX."tiki_pages` tp
				INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`),
				`".BIT_DB_PREFIX."users_users` uue,
				`".BIT_DB_PREFIX."users_users` uuc
				  WHERE tc.`content_type_guid`=?
				  AND tc.`modifier_user_id`=uue.`user_id`
				  AND tc.`user_id`=uuc.`user_id` $mid
				  ORDER BY ".$this->mDb->convert_sortmode( $sort_mode );
		$query_cant = "SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."tiki_pages` tp
			INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
			WHERE tc.`content_type_guid`=? $mid";

		if( $pOrphansOnly ) {
			$query = "SELECT
			uue.`login` AS modifier_user,
			uue.`real_name` AS modifier_real_name,
			uuc.`login` AS creator_user,
			uuc.`real_name` AS creator_real_name ,
			`page_id`,
			`hits`,
			`page_size` as `len`,
			tc.`title`,
			tc.`format_guid`,
			tp.`description`,
			tc.`last_modified`,
			tc.`created`,
			$get_data
			`ip`,
			`comment`,
			`version`,
			`flag`,
			tp.`content_id`
			FROM `".BIT_DB_PREFIX."tiki_pages` tp
				LEFT JOIN `".BIT_DB_PREFIX."tiki_links` tl ON (tp.`content_id` = tl.`to_content_id`)
				INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`),
				`".BIT_DB_PREFIX."users_users` uue,
				`".BIT_DB_PREFIX."users_users` uuc
				  WHERE tc.`content_type_guid`=?
				  AND tc.`modifier_user_id`=uue.`user_id`
				  AND tc.`user_id`=uuc.`user_id` $mid
				  AND tl.`to_content_id` is NULL
				  ORDER BY ".$this->mDb->convert_sortmode( $sort_mode );
			$query_cant = "SELECT COUNT(*)
				FROM `".BIT_DB_PREFIX."tiki_pages` tp
				LEFT JOIN `".BIT_DB_PREFIX."tiki_links` tl ON (tp.`content_id` = tl.`to_content_id`)
				INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
				  WHERE tc.`content_type_guid`=? $mid
				  AND tl.`to_content_id` IS NULL";
		}

		// If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
		// If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
		// If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil

		$this->mDb->StartTrans();
		$result = $this->mDb->query( $query, $bindVars, $maxRecords, $offset );
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
		$this->mDb->CompleteTrans();
		$ret = array();
		while( $res = $result->fetchRow() ) {
			$aux = array();
			$aux = $res;
			$aux['creator'] = (isset( $res['creator_real_name'] ) ? $res['creator_real_name'] : $res['creator_user'] );
			$aux['editor'] = (isset( $res['modifier_real_name'] ) ? $res['modifier_real_name'] : $res['modifier_user'] );
			$aux['flag'] = $res["flag"] == 'L' ? tra('locked') : tra('unlocked');
			$aux['display_link'] = $this->getListLink( $aux ); //WIKI_PKG_URL."index.php?page_id=".$res['page_id'];
			$aux['display_url'] = $this->getDisplayUrl( $aux['title'], $aux );
			if( $pExtras ) {
				// USE SPARINGLY!!! This gets expensive fast
//				$aux['versions"] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_history` where `page_id`=?", array( $res["page_id"] ) );
				$aux['links'] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_links` where `from_content_id`=?", array( $res["content_id"] ) );
				$aux['backlinks'] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_links` where `to_content_id`=?", array( $res["content_id"] ) );
			}
			$ret[] = $aux;
		}


		// If sortmode is versions, links or backlinks sort using the ad-hoc function and reduce using old_offse and old_maxRecords
		if ($old_sort_mode == 'versions_asc' && !empty( $ret['versions'] ) ) {
			usort($ret, 'compare_versions');
		}

		if ($old_sort_mode == 'versions_desc' && !empty( $ret['versions'] ) ) {
			usort($ret, 'r_compare_versions');
		}

		if ($old_sort_mode == 'links_desc' && !empty( $ret['links'] ) ) {
			usort($ret, 'compare_links');
		}

		if ($old_sort_mode == 'links_asc' && !empty( $ret['links'] ) ) {
			usort($ret, 'r_compare_links');
		}

		if( $old_sort_mode == 'backlinks_desc' && !empty( $ret['backlinks'] ) ) {
			usort($ret, 'compare_backlinks');
		}

		if( $old_sort_mode == 'backlinks_asc' && !empty( $ret['backlinks'] ) ) {
			usort($ret, 'r_compare_backlinks');
		}

		if (in_array($old_sort_mode, array(
				'versions_desc',
				'versions_asc',
				'links_asc',
				'links_desc',
				'backlinks_asc',
				'backlinks_desc'
				))) {
			$ret = array_slice($ret, $old_offset, $old_maxRecords);
		}


		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
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
			$bindvars=array();
			foreach ($words as $word) {
				$exps[] = "`title` like ?";
				$bindvars[] = "%$word%";
			}
			$exp = implode(" or ", $exps);
			$query = "SELECT tc.`title` FROM `".BIT_DB_PREFIX."tiki_pages` tp INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`) WHERE $exp";
			$result = $this->mDb->query($query,$bindvars);
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
			$query = "SELECT tc.`title` as `title`
					  FROM `".BIT_DB_PREFIX."tiki_pages` tp INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
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
	// This function calculates the page_ranks for the tiki_pages
	// it can be used to compute the most relevant pages
	// according to the number of links they have
	// this can be a very interesting ranking for the Wiki
	// More about this on version 1.3 when we add the page_rank
	// column to tiki_pages
	function page_rank($loops = 16) {
		$query = "select `content_id`, tc.`title`  from `".BIT_DB_PREFIX."tiki_pages` tp INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON( tp.`content_id`=tc.`content_id` ";
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
			$query = "update `".BIT_DB_PREFIX."tiki_pages` set `page_rank`=? where `content_id`= ?";
			$result = $this->mDb->query($query, array((int)$val, $conId) );
		}

		for ($i = 0; $i < $loops; $i++) {
			foreach ($pages as $pagename => $rank) {
				// Get all the pages linking to this one
				// Fixed query.  -rlpowell
				$query = "select `from_content_id`  from `".BIT_DB_PREFIX."tiki_links` where `to_content_id` = ?";
				$result = $this->mDb->query($query, array( $pagename ) );
				$sum = 0;

				while ($res = $result->fetchRow()) {
				$linking = $res["from_page"];

				if (isset($pages[$linking])) {
					// Fixed query.  -rlpowell
					$q2 = "select count(*) from `".BIT_DB_PREFIX."tiki_links` where `from_page`= ?";
					$cant = $this->mDb->getOne($q2, array($linking) );
					if ($cant == 0) $cant = 1;
					$sum += $pages[$linking] / $cant;
				}
				}

				$val = (1 - 0.85) + 0.85 * $sum;
				$pages[$pagename] = $val;
				// Fixed query.  -rlpowell
				$query = "update `".BIT_DB_PREFIX."tiki_pages` set `page_rank`=? where `title`=?";
				$result = $this->mDb->query($query, array((int)$val, $pagename) );

				// Update
			}
		}

		arsort ($pages);
		return $pages;
	}


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
				'fontname' => (isset($garg['node']['fontname']))?$garg['node']['fontname']:"Arial",
				'fontsize' => (isset($garg['node']['fontsize']))?$garg['node']['fontsize']:'9',
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
				'color' => '#998877',
				'style' => 'solid'
				));
			//print("add edge $page to ".$neig['name']."<br/>");
		}
    }

	// This funcion return the $limit most accessed pages
	// it returns title and hits for each page
	function get_top_pages($limit) {
		$query = "select `title` , `hits`
		from `".BIT_DB_PREFIX."tiki_pages`
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
		$query = "select `content_id`, `title`  from `".BIT_DB_PREFIX."tiki_content` WHERE `content_type_guid`='".BITPAGE_CONTENT_TYPE_GUID."' ORDER BY ".$this->mDb->convert_sortmode( 'random' );
		$rs = $this->mDb->query( $query, array(), $pNumPages );
		while( $rs && !$rs->EOF ) {
			$ret[$rs->fields['content_id']]['title'] = $rs->fields['title'];
			$ret[$rs->fields['content_id']]['display_url'] = $this->getDisplayUrl( $rs->fields['title'] );
			$rs->MoveNext();
		}

		return $ret;
	}

    function get_graph_map($page, $level, $garg) {
		$str = $this->wiki_get_link_structure($page, $level);
		$graph = new Image_GraphViz();
		$this->wiki_page_graph($str, $graph, $garg);
		return $graph->map();
    }

    function wiki_get_link_structure($page, $level) {
		$query = "select tc2.`title` from `".BIT_DB_PREFIX."tiki_links` tl
			INNER JOIN tiki_content tc1 ON tc1.`content_id` = tl.`from_content_id`
			INNER JOIN tiki_content tc2 ON tc2.`content_id` = tl.`to_content_id`
			WHERE tc1.`title`=?";
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

	/* *********  UNUSED FUNCTIONS - will delete soon - spiderr 2005-10-07 **********
	function add_wiki_attachment_hit($id) {
		global $count_admin_pvs, $user;
		if ($count_admin_pvs == 'y' || !$gBitUser->isAdmin()) {
		$query = "update `".BIT_DB_PREFIX."tiki_wiki_attachments` set `downloads`=`downloads`+1 where `att_id`=?";
		$result = $this->mDb->query($query,array((int)$id));
		}
		return true;
	}
	function get_wiki_attachment($att_id) {
		$query = "select * from `".BIT_DB_PREFIX."tiki_wiki_attachments` where `att_id`=?";
		$result = $this->mDb->query($query,array((int)$att_id));
		if (!$result->numRows()) return false;
		$res = $result->fetchRow();
		return $res;
	}

	function remove_wiki_attachment($att_id) {
		global $w_use_dir;
		$path = $this->mDb->getOne("select `path` from `".BIT_DB_PREFIX."tiki_wiki_attachments` where `att_id`=$att_id");
		if ($path) {
			@unlink ($w_use_dir . $path);
		}
		$query = "delete from `".BIT_DB_PREFIX."tiki_wiki_attachments` where `att_id`='$att_id'";
		$result = $this->mDb->query($query);
	}

	function wiki_attach_file($page, $name, $type, $size, $data, $comment, $pUserId, $fhash) {
		global $gBitSystem;
		$comment = strip_tags($comment);
		$now = $gBitSystem->getUTCTime();
		$query = "insert into `".BIT_DB_PREFIX."tiki_wiki_attachments` (`page`,`filename`,`filesize`,`filetype`,`data`,`created`,`downloads`,`user_id`,`comment`,`path`) values(?,?,?,?,?,?,0,?,?,?)";
		$result = $this->mDb->query($query,array($page,$name, (int) $size,$type,$data, (int) $now, $pUserId, $comment,$fhash));
	}
    function list_plugins() {
		$files = array();
		if (is_dir(PLUGINS_DIR)) {
			if ($dh = opendir(PLUGINS_DIR)) {
			while (($file = readdir($dh)) !== false) {
				if (preg_match("/^wikiplugin_.*\.php$/", $file))
				array_push($files, $file);
			}
			closedir ($dh);
			}
		}
		return $files;
	}
	//
	// Call 'wikiplugin_.*_description()' from given file
	//
	function get_plugin_description($file) {
		global $gBitSystem;
		include_once (PLUGINS_DIR . '/' . $file);
		$func_name = str_replace(".php", "", $file). '_help';
		return function_exists($func_name) ? $func_name() : "";
	}
	//
	// Call 'wikiplugin_.*_extended_description()' from given file
	//
	function get_plugin_extended_description($file) {
		global $gBitSystem;
		include_once (PLUGINS_DIR . '/' . $file);
		$func_name = str_replace(".php", "", $file). '_extended_help';
		return function_exists($func_name) ? $func_name() : "";
	}
	// Removes all the versions of a page and the page itself
	function remove_all_versions( $pPageId, $comment = '') {
		if( is_numeric( $pPageId ) ) {
			global $gBitUser,$gBitSystem;
			$this->mDb->StartTrans();

			//Delete structure references before we delete the page
			$query  = "SELECT ts.`structure_id`, tc.`title`
					   FROM `".BIT_DB_PREFIX."tiki_structures` ts INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp ON (tp.`page_id` = ts.`content_id`) INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`)
					   WHERE ts.`content_id`=?";
			$result = $this->mDb->query($query, array( $pPageId ) );
			if ( $result ) $delPageName = $result['title'];
			else $delPageName = '';
			while ($res = $result->fetchRow()) {
				$this->remove_from_structure($res["structure_id"]);
			}
			$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_history` where `page_id` = ?";
			$result = $this->mDb->query( $query, array( $pPageId ) );
			$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_links` where `from_content_id` = ?";
			$result = $this->mDb->query( $query, array( $pPageId ) );
			$query = "DELETE FROM `".BIT_DB_PREFIX."tiki_pages` where `page_id` = ?";
			$result = $this->mDb->query( $query, array( $pPageId ) );
			$action = "Removed";
			$t = $gBitSystem->getUTCTime();
			$query = "INSERT INTO `".BIT_DB_PREFIX."tiki_actionlog`(`action`,`page_id`, `title`, `last_modified`, `user_id`, `ip`, `comment`) VALUES (?,?,?,?,?,?,?)";
			$result = $this->mDb->query( $query, array( $action, $pPageId, $delPageName, (int)$t, $gBitUser->mUserId, $_SERVER["REMOTE_ADDR"], $comment ) );
			$query = "UPDATE `".BIT_DB_PREFIX."users_groups` SET `group_home`=? WHERE `group_home`=?";
			$this->mDb->query($query, array(NULL, $delPageName));

			#$this->remove_object('wiki page', $delPageName);

			$this->mDb->CompleteTrans();

			return true;
		}
	}
*/


	function wiki_link_structure() {
		$query = "select `title` from `".BIT_DB_PREFIX."tiki_pages` order by ".$this->mDb->convert_sortmode("title_asc");
		$result = $this->mDb->query($query);
		while ($res = $result->fetchRow()) {
			print ($res["title"] . " ");
			$page = $res["title"];
			$query2 = "select `to_page` from `".BIT_DB_PREFIX."tiki_links` where `from_page`=?";
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
	function list_received_pages($offset, $maxRecords, $sort_mode = 'title_asc', $find) {
		$bindvars = array();
		if ($find) {
		$findesc = '%'.strtoupper( $find ).'%';
		$mid = " where (UPPER(`pagename`) like ? or UPPER(`data`) like ?)";
		$bindvbars[] = $findesc;
		$bindvbars[] = $findesc;
		} else {
		$mid = "";
		}

		$query = "select * from `".BIT_DB_PREFIX."tiki_received_pages` $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_received_pages` $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
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
		return( $this->getStoragePath( $gBitSystem->getPreference( 'bitdomain' ), NULL, WIKI_PKG_NAME ).'dump.tar' );
	}

	function getDumpUrl() {
		global $gBitSystem;
		return( $this->getStorageUrl( $gBitSystem->getPreference( 'bitdomain' ), NULL, WIKI_PKG_NAME ).'dump.tar' );
	}

	// Dumps the database to dump/new.tar
	// changed for virtualhost support
	function dumpPages() {
		global $wikiHomePage, $gBitSystem, $gBitUser;

		$tar = new tar();
		$tar->addFile( $gBitSystem->getStyleCss() );
		// Foreach page
		$query = "select * from `".BIT_DB_PREFIX."tiki_pages`";
		$result = $this->mDb->query($query,array());

		$dumpFile = $this->getDumpFile();
		if( file_exists( $dumpFile ) ) {
			unlink( $dumpFile );
		}

		while ($res = $result->fetchRow()) {
			$title = $res["title"] . '.html';

			$dat = $this->parseData($res["data"]);
			// Now change index.php?page=foo to foo.html
			// and index.php to HomePage.html
			$dat = preg_replace("/index.php\?page=([^\'\"\$]+)/", "$1.html", $dat);
			$dat = preg_replace("/edit.php\?page=([^\'\"\$]+)/", "", $dat);
			//preg_match_all("/index.php\?page=([^ ]+)/",$dat,$cosas);
			//print_r($cosas);
			$data = "<html><head><title>" . $res["title"] . "</title><link rel='StyleSheet' href='".$gBitSystem->getStyleCss()."' type='text/css'></head><body><a class='wiki' href='$wikiHomePage.html'>home</a><br/><h1>" . $res["title"] . "</h1><div class='wikitext'>" . $dat . '</div></body></html>';
			$tar->addData($title, $data, $res["last_modified"]);
		}

		$tar->toTar( $dumpFile, FALSE );
		unset ($tar);
		$action = "dump created";
		$t = $gBitSystem->getUTCTime();
		$query = "insert into `".BIT_DB_PREFIX."tiki_actionlog`(`action`,`page_id`,`last_modified`,`user_id`,`ip`,`comment`) values(?,?,?,?,?,?)";
		$result = $this->mDb->query($query,array($action,1,$t,$gBitUser->mUserId,$_SERVER["REMOTE_ADDR"],''));
	}

/* using BitPage::getList() now - xing
	// Scan pages and find orphaned ones
	function list_orphan_pages($offset = 0, $maxRecords = -1, $sort_mode = 'title_desc', $find = '') {

		if ($sort_mode == 'size_desc') {
			$sort_mode = 'page_size_desc';
		}

		if ($sort_mode == 'size_asc') {
			$sort_mode = 'page_size_asc';
		}

		$old_sort_mode = '';

		if (in_array($sort_mode, array(
			'versions_desc',
			'versions_asc',
			'links_asc',
			'links_desc',
			'backlinks_asc',
			'backlinks_desc'
		))) {
			$old_offset = $offset;

			$old_maxRecords = $maxRecords;
			$old_sort_mode = $sort_mode;
			$sort_mode = 'user_id_desc';
			$offset = 0;
			$maxRecords = -1;
		}
		$bindvars = array();
		if ($find) {
			$mid = " AND UPPER(tc.`title`) like ? ";
			$mid_cant = " WHERE UPPER(tc.`title`) like ? ";
			$bindvars[] = '%'.strtoupper( $find ).'%';
		} else {
			$mid = "";
			$mid_cant = "";
		}

		// If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
		// If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
		// If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil
		$query = "SELECT tc.`title`, tc.`hits`, tp.`page_size` as `len` ,tc.`last_modified`, tc.`user_id`, tc.`ip`, tp.`comment`, tp.`version`, tp.`flag`, tc.`content_id`, tp.`page_id`
				  FROM `".BIT_DB_PREFIX."tiki_content` tc INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp on (tp.`content_id` = tc.`content_id` )
				  WHERE tc.`content_type_guid`='bitpage' $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_content` tc $mid_cant";
		$result = $this->mDb->query($query,$bindvars,-1,0);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();
		$num_or = 0;

		while ($res = $result->fetchRow()) {

			$page_ci = $res["content_id"];
			$queryc = "select count(*) from `".BIT_DB_PREFIX."tiki_links` where `to_content_id`=?";
			$cant = $this->mDb->getOne($queryc,array($page_ci));
			$queryc = "select count(*) from `".BIT_DB_PREFIX."tiki_structures` ts, `".BIT_DB_PREFIX."tiki_pages` tp where ts.`content_id`=tp.`page_id` and tp.`content_id`=?";
			$cant += $this->mDb->getOne($queryc,array($page_ci));

			if ($cant == 0) {
				$num_or++;
				$aux = array();
				$title = $res["title"];
				$aux["title"] = $title;
				$page_id = $res["page_id"];
				$page = $aux["title"];
				$page_as = addslashes($page);
				$aux["page_id"] = $res["page_id"];
				$aux['display_url'] = $this->getDisplayUrl( $aux['title'], $aux );
				$aux["hits"] = $res["hits"];
				$aux["last_modified"] = $res["last_modified"];
				$aux["user"] = $res["user_id"];
				$aux["ip"] = $res["ip"];
				$aux["len"] = $res["len"];
				$aux["comment"] = $res["comment"];
				$aux["version"] = $res["version"];
				$aux["flag"] = $res["flag"] == 'y' ? tra('locked') : tra('unlocked');
				$aux["versions"] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_history` where `page_id`=?",array($page_id));
				$aux["links"] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_links` where `from_content_id`=?",array($page_ci));
				$aux["backlinks"] = $this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_links` where `to_content_id`=?",array($page_ci));
				$ret[] = $aux;
			}
		}

		// If sortmode is versions, links or backlinks sort using the ad-hoc function and reduce using old_offse and old_maxRecords
		if ($old_sort_mode == 'versions_asc') {
			usort($ret, 'compare_versions');
		}

		if ($old_sort_mode == 'versions_desc') {
			usort($ret, 'r_compare_versions');
		}

		if ($old_sort_mode == 'links_desc') {
			usort($ret, 'compare_links');
		}

		if ($old_sort_mode == 'links_asc') {
			usort($ret, 'r_compare_links');
		}

		if ($old_sort_mode == 'backlinks_desc') {
			usort($ret, 'compare_backlinks');
		}

		if ($old_sort_mode == 'backlinks_asc') {
			usort($ret, 'r_compare_backlinks');
		}

		if (in_array($old_sort_mode, array(
			'versions_desc',
			'versions_asc',
			'links_asc',
			'links_desc',
			'backlinks_asc',
			'backlinks_desc'
		))) {
			$ret = array_slice($ret, $old_offset, $old_maxRecords);
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $num_or;
		return $retval;
	}
*/
	// Templates ////
	/*shared*/
	function list_templates($section, $offset, $maxRecords, $sort_mode, $find) {
		$bindvars = array($section);
		if ($find) {
		$findesc = '%'.strtoupper( $find ).'%';
		$mid = " and (UPPER(`content`) like ?)";
		$bindvars[] = $findesc;
		} else {
		$mid = "";
		}

		$query = "select `name` ,`created`,tcts.`template_id` from `".BIT_DB_PREFIX."tiki_content_templates` tct, `".BIT_DB_PREFIX."tiki_content_templates_sections` tcts ";
		$query.= " where tcts.`template_id`=tct.`template_id` and tcts.`section`=? $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_content_templates` tct, `".BIT_DB_PREFIX."tiki_content_templates_sections` tcts ";
		$query_cant.= "where tcts.`template_id`=tct.`template_id` and tcts.`section`=? $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
		$query2 = "select `section`  from `".BIT_DB_PREFIX."tiki_content_templates_sections` where `template_id`=?";

		$result2 = $this->mDb->query($query2,array((int)$res["template_id"]));
		$sections = array();

		while ($res2 = $result2->fetchRow()) {
			$sections[] = $res2["section"];
		}

		$res["sections"] = $sections;
		$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	/*shared*/
	function get_template($template_id) {
		$query = "select * from `".BIT_DB_PREFIX."tiki_content_templates` where `template_id`=?";
		$result = $this->mDb->query($query,array((int)$template_id));
		if (!$result->numRows()) return false;
		$res = $result->fetchRow();
		return $res;
	}
	// templates ////
/*

	pulled from the now nuked hist_lib during bitweaver conversion. I think this function is deprecated. XOXO spiderr

	// This function get the last changes from pages from the last $days days
	// if days is 0 this gets all the registers
	// function parameters modified by ramiro_v on 11/03/2002
	function get_last_changes($days, $offset = 0, $limit = -1, $sort_mode = 'last_modified_desc', $findwhat = '') {
		// section added by ramiro_v on 11/03/2002 begins here
		$where = '';
		if ($findwhat == '') {
			$bindvars=array();
		} else {
			$findstr='%' . strtoupper( $findwhat ) . '%';
			$where = "WHERE UPPER(tc.`title`) like ? OR UPPER(tc.`comment`) like ? ";
			$bindvars=array($findstr,$findstr);
		}
		// section added by ramiro_v on 11/03/2002 ends here
		if ($days) {
			$toTime = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
			$fromTime = $toTime - (24 * 60 * 60 * $days);
			$where .= "WHERE th.`last_modified` BETWEEN ? AND ? ";
			$bindvars[]=$fromTime;
			$bindvars[]=$toTime;
		}
/ *		if (empty($where)) {
			$where=" ta.`last_modified` = th.`last_modified` ";
		} else {
			$where.="WHERE ta.`last_modified` = th.`last_modified`";
		}
* /
		$query = "SELECT 'Update' as `action`, th.`last_modified`, uu.`login`, uu.`real_name` as `user`, tc.`ip`, " .
				"th.`description`, tc.`title`, th.`comment`, th.`version`, th.`page_id` " .
				"FROM `".BIT_DB_PREFIX."tiki_history` th " .
				"INNER JOIN `".BIT_DB_PREFIX."tiki_pages` tp ON (th.`page_id`= tp.`page_id`) " .
				"INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON (tc.`content_id` = tp.`content_id`) " .
				"INNER JOIN `".BIT_DB_PREFIX."users_users` uu ON (uu.`user_id`= th.`user_id`) " .
				"$where " .
				"order by th.".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tiki_history` th " . $where;
		$result = $this->mDb->query($query,$bindvars,$limit,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();
		$r = array();
		while ($res = $result->fetchRow()) {
			$r["action"] = $res["action"];
			$r["last_modified"] = $res["last_modified"];
			$r["user"] = $res["user"];
			$r["ip"] = $res["ip"];
			$r["page_id"] = $res["page_id"];
			$r["title"] = $res["title"];
			$r["comment"] = $res["comment"];
			$r["version"] = $res["version"];
			$ret[] = $r;
		}
		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
*/
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
