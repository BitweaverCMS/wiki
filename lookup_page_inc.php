<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/lookup_page_inc.php,v 1.1.1.1.2.3 2005/08/14 11:08:26 wolff_borg Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: lookup_page_inc.php,v 1.1.1.1.2.3 2005/08/14 11:08:26 wolff_borg Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
	require_once( WIKI_PKG_PATH.'BitBook.php');

	global $gContent, $wikilib;
	include_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

	// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
	if( empty( $gContent ) || !is_object( $gContent ) ) {
		$gContent = new BitPage( !empty( $_REQUEST['page_id'] ) ? $_REQUEST['page_id'] : NULL, !empty( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : NULL );

		$loadPage = (!empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : NULL);
		if( empty( $gContent->mPageId ) && empty( $gContent->mContentId )  ) {
			//handle legacy forms that use plain 'page' form variable name

			if( $loadPage && $existsInfo = $wikilib->pageExists( $loadPage ) ) {
				if (count($existsInfo)) {
					if (count($existsInfo) > 1) {
						// Display page so user can select which wiki page they want (there are multiple that share this name)
						$gBitSmarty->assign( 'choose', $_REQUEST['page'] );
						$gBitSmarty->assign('dupePages', $existsInfo);
						$gBitSystem->display('bitpackage:wiki/page_select.tpl');
						die;
					} else {
						$gContent->mPageId = $existsInfo[0]['page_id'];
					}
				}
			}
		}
		if( !$gContent->load() && $loadPage ) {
			$gContent->mInfo['title'] = $loadPage;
		}
		$gBitSmarty->assign_by_ref( 'gContent', $gContent );
		$gBitSmarty->assign_by_ref( 'pageInfo', $gContent->mInfo );
	}

	// we weren't passed a structure, but maybe this page belongs to one. let's check...
	if( empty( $gStructure ) ) {
		//Get the structures this page is a member of
		if( !empty($_REQUEST['structure']) ) {
			$structure=$_REQUEST['structure'];
		} else {
			$structure='';
		}
		$structs = $gContent->getStructures();
		if (count($structs)==1) {
			$gStructure = new LibertyStructure( $structs[0]['structure_id'] );
			if( $gStructure->load() ) {
				$gStructure->loadNavigation();
				$gStructure->loadPath();
				$gBitSmarty->assign( 'structureInfo', $gStructure->mInfo );
			}
		} else {
			$gBitSmarty->assign('showstructs', $structs);
		}
	}

	if( $gContent->isValid() && $gBitSystem->isPackageActive( 'stickies' ) ) {
		require_once( STICKIES_PKG_PATH.'BitSticky.php' );
		global $gNote;
		$gNote = new BitSticky( NULL, NULL, $gContent->mContentId );
		$gNote->load();
		$gBitSmarty->assign_by_ref( 'stickyInfo', $gNote->mInfo );
	}

	// if we are looking up a page
	if( $gBitSystem->isFeatureActive( 'feature_warn_on_edit' ) && $gContent->isValid() ) {
		// Notice if a page is being edited or if it was being edited and not anymore
		// print($GLOBALS["HTTP_REFERER"]);
		// IF isset the referer and if the referer is editpage then unset taking the pagename from the
		// query or homepage if not query
		if (isset($_SERVER['HTTP_REFERER']) && (strstr($_SERVER['HTTP_REFERER'], WIKI_PKG_URL.'edit') ) ) {
			$purl = parse_url($_SERVER['HTTP_REFERER']);

			if (!isset($purl["query"])) {
				$purl["query"] = '';
			}

			parse_str($purl["query"], $purlquery);

			if (!isset($purlquery["page"])) {
				$purlquery["page"] = $wikiHomePage;
			}

			if (isset($_SESSION["edit_lock"])) {
				// TODO - find out if this function is supposed to exist - wolff_borg
				//$gBitUser->expungeSemaphore($purlquery["page"], $_SESSION["edit_lock"]);
			}
		}

		if (strstr($_SERVER['REQUEST_URI'], WIKI_PKG_URL . 'edit')) {
			$purl = parse_url($_SERVER['REQUEST_URI']);

			if (!isset($purl["query"])) {
				$purl["query"] = '';
			}

			parse_str($purl["query"], $purlquery);

			// When WIKI_PKG_URL.'edit.php' is loading, check to see if there is an editing conflict
			if( $gBitUser->hasSemaphoreConflict( $gContent->mContentId, $gBitSystem->mPrefs['warn_on_edit_time'] * 60 ) ) {
				$gBitSmarty->assign('editpageconflict', 'y');
			} else {
				if (!(isset($_REQUEST['save'])) && $gContent->isValid() ) {
					$_SESSION["edit_lock"] = $gBitUser->storeSemaphore( $gContent->mContentId );
					$gBitSmarty->assign('editpageconflict', 'n');
				}
			}
		}

		if( $semUser = $gBitUser->hasSemaphoreConflict( $gContent->mContentId, $gBitSystem->mPrefs['warn_on_edit_time'] * 60 ) ) {
			$gContent->mErrors['edit_conflict'] = 'This page is being edited by '.$gBitUser->getDisplayName( TRUE, $semUser ).'. Proceed at your own peril';
			$gBitSmarty->assign( 'semUser', $semUser );
			$beingedited = 'y';
		} else {
			$beingedited = 'n';
		}
		$gBitSmarty->assign('beingEdited', $beingedited);
	}
?>
