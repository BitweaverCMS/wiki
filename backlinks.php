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
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\KernelTools;
include_once WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php';

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_backlinks' );

// Get the page from the request var or default it to HomePage
if ( empty($gContent->mPageName) ) {
}
// Now check permissions to access this page
$gContent->verifyViewPermission();

// If the page doesn't exist then display an error
if( !$gContent->pageExists( $gContent->mPageName )) {
	$gBitSystem->fatalError(KernelTools::tra("The page could not be found"));
} else {
	$_REQUEST["page"] = $gContent->mPageName;
	$gBitSmarty->assign('page', $_REQUEST["page"]);
}

// Get the backlinks for the page "page"
$backlinks = $gContent->getBacklinks();
$gBitSmarty->assign('backlinks', $backlinks);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/backlinks.tpl', null, [ 'display_mode' => 'display' ] );
