<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/print_pages.php,v 1.8 2007/07/06 16:22:14 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: print_pages.php,v 1.8 2007/07/06 16:22:14 spiderr Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );
if( !$gBitSystem->isFeatureActive( 'wiki_multiprint' ) ) {
	$gBitSystem->fatalError( tra("This feature is disabled").": wiki_multiprint" );
}
// Now check permissions if user can view wiki pages
$gContent->verifyPermission( 'p_wiki_view_page' );

if (!isset($_REQUEST["printpages"])) {
	$printpages = array();
} else {
	$printpages = unserialize(urldecode($_REQUEST["printpages"]));
}
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$gBitSmarty->assign('find', $find);
if (isset($_REQUEST["addpage"])) {
	if (!in_array($_REQUEST["title"], $printpages)) {
		$printpages[] = $_REQUEST["title"];
	}
}
if (isset($_REQUEST["clearpages"])) {
	$printpages = array();
}
$gBitSmarty->assign('printpages', $printpages);
$form_printpages = urlencode(serialize($printpages));
$gBitSmarty->assign('form_printpages', $form_printpages);
$listHash = array(
	'max_records' => -1,
	'sort_mode' => 'title_asc',
	'find' => $find,
);
$pages = $gContent->getList( $listHash );
$gBitSmarty->assign_by_ref('pages', $pages);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/print_pages.tpl');
?>
