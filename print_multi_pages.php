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
require_once( '../kernel/setup_inc.php' );
require_once( WIKI_PKG_CLASS_PATH.'BitPage.php' );

$gBitSystem->verifyFeature( 'wiki_multiprint' );

if (!isset($_REQUEST["printpages"])) {
	$gBitSystem->fatalError( tra( "No pages indicated" ), NULL, NULL, HttpStatusCodes::HTTP_NOT_FOUND );
} else {
	$printpages = unserialize(urldecode($_REQUEST["printpages"]));
}

if (isset($_REQUEST["print"])) {
	// Create XMLRPC object
	$pages = array();
	foreach( $printpages as $contentId ) {
		$page = new BitPage( NULL, $contentId );
		if( $page->load() ) {
			$page->verifyViewPermission();
			$page->getParsedData();
			$pages[] = $page->mInfo;
		}
	}
}
$gBitSmarty->assignByRef('pages', $pages);

// Display the template
$gBitSmarty->display("bitpackage:wiki/print_multi_pages.tpl");
?>
