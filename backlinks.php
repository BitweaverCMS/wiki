<?php
/**
 * assigned_modules
 *
 * @author   spider <spider@steelsun.com>
 * @version  $Revision: 1.12 $
 * @package  wiki
 * @subpackage  functions
 * @copyright Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * @license Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
 */
// $Header: /cvsroot/bitweaver/_bit_wiki/backlinks.php,v 1.12 2007/07/06 16:22:14 spiderr Exp $
// All Rights Reserved. See copyright.txt for details and a complete list of authors.

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'lookup_page_inc.php');

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_backlinks' );

// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$gBitSystem->fatalError(tra("No page indicated"));
} else {
	$page = $_REQUEST["page"];
	$gBitSmarty->assign_by_ref('page', $_REQUEST["page"]);
}

// Now check permissions to access this page
$gContent->verifyPermission( 'p_wiki_view_page' );

// If the page doesn't exist then display an error
if( !$gContent->pageExists( $page )) {
	$gBitSystem->fatalError(tra("The page could not be found"));
}
// Get the backlinks for the page "page"
$backlinks = $gContent->getBacklinks();
$gBitSmarty->assign_by_ref('backlinks', $backlinks);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/backlinks.tpl');
?>
