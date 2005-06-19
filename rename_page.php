<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/Attic/rename_page.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$smarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
} else {
	$page = $_REQUEST["page"];
	$smarty->assign_by_ref('page', $_REQUEST["page"]);
}
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );
// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_rename' )) {
	$smarty->assign('msg', tra("Permission denied you cannot remove versions from this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// If the page doesn't exist then display an error
if (!$wikilib->pageExists($page,true)) { // true: casesensitive check here
	$smarty->assign('msg', tra("Page cannot be found"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (isset($_REQUEST["rename"])) {
	
	if (!$wikilib->wiki_rename_page($_REQUEST['oldpage'], $_REQUEST['newpage'])) {
		$smarty->assign('msg', tra("Cannot rename page maybe new page already exists"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	$newName = $_REQUEST['newpage'];
	header ("location: ".WIKI_PKG_URL."index.php?page=$newName");
}

$gBitSystem->display( 'bitpackage:wiki/rename_page.tpl');
$smarty->assign('show_page_bar', 'y');
?>
