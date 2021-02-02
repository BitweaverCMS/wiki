<?php
/**
 * assigned_modules
 *
 * @author   spider <spider@steelsun.com>
 * @package  wiki
 * @subpackage  functions
 * @copyright Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * @license Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );
include_once( WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php');

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_backlinks' );

// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$gBitSystem->fatalError(tra("No page indicated"));
} else {
	$page = $_REQUEST["page"];
	$gBitSmarty->assignByRef('page', $_REQUEST["page"]);
}

// Now check permissions to access this page
$gContent->verifyViewPermission();

// If the page doesn't exist then display an error
if( !$gContent->pageExists( $page )) {
	$gBitSystem->fatalError(tra("The page could not be found"));
}
// Get the backlinks for the page "page"
$backlinks = $gContent->getBacklinks();
$gBitSmarty->assignByRef('backlinks', $backlinks);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/backlinks.tpl', NULL, array( 'display_mode' => 'display' ));
?>
