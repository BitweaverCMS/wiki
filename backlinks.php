<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/backlinks.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'lookup_page_inc.php');

$gBitSystem->verifyPackage( 'wiki' );
if ($feature_backlinks != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_backlinks");
	$gBitSystem->display( 'error.tpl' );
	die;
}
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
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$smarty->assign('msg', tra("Permission denied you cannot view backlinks for this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// If the page doesn't exist then display an error
if (!$wikilib->pageExists($page)) {
	$smarty->assign('msg', tra("The page cannot be found"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get the backlinks for the page "page"
$backlinks = $gContent->getBacklinks();
$smarty->assign_by_ref('backlinks', $backlinks);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/backlinks.tpl');
$smarty->assign('show_page_bar', 'y');
?>
