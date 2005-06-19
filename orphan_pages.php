<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/orphan_pages.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php' );
include_once( WIKI_PKG_PATH.'BitPage.php' );
$BitPage = new BitPage();

$gBitSystem->verifyPackage( 'wiki' );

$gBitSystem->verifyFeature( 'feature_listPages' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'bit_p_view' );

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
	$gBitSystem->verifyPermission( 'bit_p_remove' );

	if( !empty( $_REQUEST['cancel'] ) ) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] ) ) {
		$formHash['delete'] = TRUE;
		$formHash['submit_mult'] = 'remove_pages';
		foreach( $_REQUEST["checked"] as $del ) {
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>';
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
			$smarty->assign_by_ref( 'errors', $errors );
		}
	}
}
// This script can receive the thresold
// for the information as the number of
// days to get in the log 1,3,4,etc
// it will default to 1 recovering information for today
if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'title_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
// If offset is set use it if not then use offset =0
// use the maxRecords php variable to set the limit
// if sortMode is not set then use last_modified_desc
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $maxRecords;
}
$smarty->assign_by_ref('offset', $offset);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign('find', $find);
// Get a list of last changes to the Wiki database
$listpages = $BitPage->getList( $offset, $maxRecords, $sort_mode, $find, NULL, NULL, TRUE );
// If there're more records then assign next_offset
$cant_pages = ceil($listpages["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $maxRecords));
if ($listpages["cant"] > ($offset + $maxRecords)) {
	$smarty->assign('next_offset', $offset + $maxRecords);
} else {
	$smarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$smarty->assign('prev_offset', -1);
}
$smarty->assign_by_ref('listpages', $listpages["data"]);
//print_r($listpages["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/orphan_pages.tpl');
?>
