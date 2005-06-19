<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/Attic/rollback.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
include_once( WIKI_PKG_PATH.'hist_lib.php');

$gBitSystem->verifyPackage( 'wiki' );
// Get the page from the request var or default it to HomePage
/*if (!isset($_REQUEST["page"])) {
	$smarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
} else {
	$page = $_REQUEST["page"];
	$smarty->assign_by_ref('page', $_REQUEST["page"]);
}*/
include( WIKI_PKG_PATH.'lookup_page_inc.php' );
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

if (!isset($_REQUEST["version"])) {
	$smarty->assign('msg', tra("No version indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
} else {
	$version = $_REQUEST["version"];
	$smarty->assign_by_ref('version', $_REQUEST["version"]);
}

// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_rollback' )) {
	$smarty->assign('msg', tra("Permission denied you cannot rollback this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
$version = $gContent->getHistory( $version );
$version[0]["data"] = $gContent->parseData($version[0]["data"]);
$smarty->assign_by_ref('preview', $version[0]);
if (isset($_REQUEST["rollback"])) {
	if( $gContent->rollbackVersion( $_REQUEST["version"] ) ) {
		header( "location: ".$gContent->getDisplayUrl() );
		die;
	}
}
$gBitSystem->display( 'bitpackage:wiki/rollback.tpl');
$smarty->assign('show_page_bar', 'y');
?>
