<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/edit_book.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $

// Copyright (c) 2004, Christian Fowler, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPermission( 'bit_p_edit_books' );

if( isset( $_COOKIE['book_section'] ) && $_COOKIE['book_section'] == 'o' ) {
	$book_section = 'block';
} else {
	$book_section = 'none';
}
$smarty->assign( 'book_section',$book_section );

include_once( WIKI_PKG_PATH.'lookup_page_inc.php');
include_once( LIBERTY_PKG_PATH.'LibertyStructure.php');
include_once( WIKI_PKG_PATH.'BitBook.php');

global $gStructure;

// check what tab is active
if( isset( $_REQUEST['tab'] ) ) {
	$smarty->assign( $_REQUEST['tab'].'TabSelect','tdefault' );
}

if( isset($_REQUEST["createstructure"]) ) {
	if ((empty($_REQUEST['name']))) {
		$smarty->assign('msg', tra("You must specify a name."));
		$gBitSystem->display( 'error.tpl' );
		die;
	}

	//try to add a new structure
	$bookPage = new BitBook();
	$pageId = $bookPage->findByPageName( $_REQUEST['name'] );
	if( $pageId ) {
		$bookPage->mPageId = $pageId;
		$bookPage->load();
	} else {
		$params['title'] = $_REQUEST['name'];
		$params['edit'] = '{toc}';
		$bookPage->store( $params );
	}

	if( $bookPage->isValid() ) {
		$gStructure = new LibertyStructure();
		// alias => '' is a temporary setting until alias stuff has been removed
		$structureHash = array( 'content_id' => $bookPage->mContentId, 'alias' => '' );
		$structure_id = $gStructure->storeNode( $structureHash );
		//Cannot create a structure if a structure already exists
		if (!isset($structure_id)) {
			$smarty->assign('msg', $_REQUEST['name'] . " " . tra("page not added (Exists)"));
			$gBitSystem->display( 'error.tpl' );
			die;
		}

		$chapters = explode("\n", $_REQUEST["chapters"]);
		foreach ($chapters as $chapter) {
			$chapterName = trim($chapter);
			if( !empty( $chapterName ) ) {
				unset( $params );
				unset( $nodeHash );
				$nodeHash['parent_id'] = $structure_id;
				$nodeHash['root_structure_id'] = $structure_id;
				$nodeHash['level'] = 1;
				//try to add a new structure
				$nodePage = new BitPage();
				$pageId = $nodePage->findByPageName( $chapterName );
				if( $pageId ) {
					$nodePage->mPageId = $pageId;
					$nodePage->load();
				} else {
					$params['title'] = trim($chapterName);
					$params['edit'] = '';
					if( !$nodePage->store( $params ) ) {
						vd( $bookPage->mErrors );
					}
				}
				$nodeHash['content_id'] = $nodePage->mContentId;
				$nodeHash['after_ref_id'] = $gStructure->storeNode( $nodeHash );
			}
		}
		header( "location: ".WIKI_PKG_URL."edit_book.php?structure_id=".$structure_id );
	}
} elseif( !empty( $_REQUEST["structure_id"] ) ) {
	$mid = 'bitpackage:wiki/edit_book.tpl';
	include_once( LIBERTY_PKG_PATH.'edit_structure_inc.php');
} else {
	$gBitSystem->setBrowserTitle( 'Create Wiki Book' );
	$mid = 'bitpackage:wiki/create_book.tpl';
}
$gBitSystem->setBrowserTitle( !empty($gStructure) ? 'Edit Wiki Book:'.$gStructure->mInfo["title"] : NULL );
// Display the template
$gBitSystem->display( $mid );

?>
