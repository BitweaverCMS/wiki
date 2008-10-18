<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/lookup_page_inc.php,v 1.26 2008/10/18 17:11:14 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: lookup_page_inc.php,v 1.26 2008/10/18 17:11:14 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitBook.php');

global $gContent;
include_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

// this is needed when the center module is applied to avoid abusing $_REQUEST
if( empty( $lookupHash )) {
	$lookupHash = &$_REQUEST;
}

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || strtolower( get_class( $gContent ) ) != 'bitpage' ) {
	$gContent = new BitPage( @BitBase::verifyId( $lookupHash['page_id'] ) ? $lookupHash['page_id'] : NULL, @BitBase::verifyId( $lookupHash['content_id'] ) ? $lookupHash['content_id'] : NULL );

	$loadPage = (!empty( $lookupHash['page'] ) ? $lookupHash['page'] : NULL);
	if( empty( $gContent->mPageId ) && empty( $gContent->mContentId )  ) {
		//handle legacy forms that use plain 'page' form variable name

		if( $loadPage && $existsInfo = $gContent->pageExists( $loadPage ) ) {
			if (count($existsInfo)) {
				if (count($existsInfo) > 1) {
					// Display page so user can select which wiki page they want (there are multiple that share this name)
					$gBitSmarty->assign( 'choose', $lookupHash['page'] );
					$gBitSmarty->assign('dupePages', $existsInfo);
					$gBitSystem->display('bitpackage:wiki/page_select.tpl', NULL, array( 'display_mode' => 'display' ));
					die;
				} else {
					$gContent->mPageId = $existsInfo[0]['page_id'];
					$gContent->mContentId = $existsInfo[0]['content_id'];
				}
			}
		} elseif( $loadPage ) {
			$gBitSmarty->assign('page', $loadPage);//to have the create page link in the error
		}
	}
	if( !$gContent->load() && $loadPage ) {
		$gContent->mInfo['title'] = $loadPage;
	}
}

// we weren't passed a structure, but maybe this page belongs to one. let's check...
if( empty( $gStructure ) ) {
	//Get the structures this page is a member of
	if( !empty($lookupHash['structure']) ) {
		$structure=$lookupHash['structure'];
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

$gBitSmarty->clear_assign( 'gContent' );
$gBitSmarty->assign_by_ref( 'gContent', $gContent );
?>
