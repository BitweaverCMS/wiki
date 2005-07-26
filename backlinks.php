<?php
/**
 * assigned_modules
 *
 * @author   spider <spider@steelsun.com>
 * @version  $Revision: 1.1.1.1.2.3 $
 * @package  wiki
 * @subpackage  functions
 * @copyright Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * @license Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
 */
// $Header: /cvsroot/bitweaver/_bit_wiki/backlinks.php,v 1.1.1.1.2.3 2005/07/26 15:50:32 drewslater Exp $
// All Rights Reserved. See copyright.txt for details and a complete list of authors.

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'lookup_page_inc.php');

$gBitSystem->verifyPackage( 'wiki' );
if ($feature_backlinks != 'y') {
	$gBitSmarty->assign('msg', tra("This feature is disabled").": feature_backlinks");
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$gBitSmarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
} else {
	$page = $_REQUEST["page"];
	$gBitSmarty->assign_by_ref('page', $_REQUEST["page"]);
}
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );
// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$gBitSmarty->assign('msg', tra("Permission denied you cannot view backlinks for this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// If the page doesn't exist then display an error
if (!$wikilib->pageExists($page)) {
	$gBitSmarty->assign('msg', tra("The page cannot be found"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get the backlinks for the page "page"
$backlinks = $gContent->getBacklinks();
$gBitSmarty->assign_by_ref('backlinks', $backlinks);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/backlinks.tpl');
$gBitSmarty->assign('show_page_bar', 'y');
?>
