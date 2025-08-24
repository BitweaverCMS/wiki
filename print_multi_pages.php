<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\Wiki\BitPage;
use Bitweaver\KernelTools;
use Bitweaver\HttpStatusCodes;

$gBitSystem->verifyFeature( 'wiki_multiprint' );

if (!isset($_REQUEST["printpages"])) {
	$gBitSystem->fatalError( KernelTools::tra( "No pages indicated" ), null, null, HttpStatusCodes::HTTP_NOT_FOUND );
} else {
	$printpages = unserialize(urldecode($_REQUEST["printpages"]));
}

if (isset($_REQUEST["print"])) {
	// Create XMLRPC object
	$pages = [];
	foreach( $printpages as $contentId ) {
		$page = new BitPage( null, $contentId );
		if( $page->load() ) {
			$page->verifyViewPermission();
			$page->getParsedData();
			$pages[] = $page->mInfo;
		}
	}
}
$gBitSmarty->assign('pages', $pages);

// Display the template
$gBitSmarty->display("bitpackage:wiki/print_multi_pages.tpl");
