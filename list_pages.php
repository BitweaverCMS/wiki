<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/list_pages.php,v 1.13 2007/01/29 05:42:11 jht001 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: list_pages.php,v 1.13 2007/01/29 05:42:11 jht001 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );

$gBitSystem->verifyPackage( 'wiki' );

$gBitSystem->verifyFeature( 'wiki_list_pages' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_wiki_view_page' );

/* mass-remove:
   the checkboxes are sent as the array $_REQUEST["checked[]"], values are the wiki-PageNames,
   e.g. $_REQUEST["checked"][3]="HomePage"
   $_REQUEST["submit_mult"] holds the value of the "with selected do..."-option list
   we look if any page's checkbox is on and if remove_pages is selected.
   then we check permission to delete pages.
   if so, we call BitPage::expunge for all the checked pages.
*/
if (isset($_REQUEST["submit_mult"]) && isset($_REQUEST["checked"]) && $_REQUEST["submit_mult"] == "remove_pages") {
	
	include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

	// Now check permissions to remove the selected pages
	$gBitSystem->verifyPermission( 'p_wiki_remove_page' );

	if( !empty( $_REQUEST['cancel'] ) ) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] ) ) {
		$formHash['delete'] = TRUE;
		$formHash['submit_mult'] = 'remove_pages';
		foreach( $_REQUEST["checked"] as $del ) {
			$tmpPage = new BitPage( $del);
			if ($tmpPage->load() && !empty($tmpPage->mInfo['title'])) {
				$info = $tmpPage->mInfo['title'];
			} else {
				$info = $del;
			}
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>'.$info;
		}
		$gBitSystem->confirmDialog( $formHash, array( 'warning' => 'Are you sure you want to delete '.count($_REQUEST["checked"]).' pages?', 'error' => 'This cannot be undone!' ) );
	} else {
		foreach ($_REQUEST["checked"] as $deletepage) {
			$tmpPage = new BitPage( $deletepage );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ) );
			}
		}
		if( !empty( $errors ) ) {
			$gBitSmarty->assign_by_ref( 'errors', $errors );
		}
	}
}
// This script can receive the thresold
// for the information as the number of
// days to get in the log 1,3,4,etc
// it will default to 1 recovering information for today
if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'last_modified_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
// If offset is set use it if not then use offset =0
// use the max_records php variable to set the limit
// if sortMode is not set then use last_modified_desc
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $max_records;
}
$gBitSmarty->assign_by_ref('offset', $offset);
if (isset($_REQUEST["find_title"])) {
	$find = $_REQUEST["find_title"];
} else {
	$find = '';
}
if (isset($_REQUEST["find_author"])) {
	$find_author = $_REQUEST["find_author"];
} else {
	$find_author = '';
}
if (isset($_REQUEST["find_last_editor"])) {
	$find_last_editor = $_REQUEST["find_last_editor"];
} else {
	$find_last_editor = '';
}
$gBitSmarty->assign('find_title', $find);
$gBitSmarty->assign('find_author', $find_author);
$gBitSmarty->assign('find_last_editor', $find_last_editor);
// Get a list of last changes to the Wiki database
$gContent = new BitPage();
$gBitSmarty->assign_by_ref( "gContent", $gContent );
$sort_mode = preg_replace( '/^user_/', 'creator_user_', $sort_mode );
$listpages = $gContent->getList( $offset, $max_records, $sort_mode, $find, NULL, TRUE, FALSE, FALSE, $find_author, $find_last_editor );
// If there're more records then assign next_offset
$cant_pages = ceil($listpages["cant"] / $max_records);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign_by_ref('pagecount', $listpages['cant']);
$gBitSmarty->assign('actual_page', 1 + ($offset / $max_records));
if ($listpages["cant"] > ($offset + $max_records)) {
	$gBitSmarty->assign('next_offset', $offset + $max_records);
} else {
	$gBitSmarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $max_records);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

$gBitSmarty->assign_by_ref('listpages', $listpages["data"]);
//print_r($listpages["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/list_pages.tpl', tra( 'Wiki Pages' ) );
?>
