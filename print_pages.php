<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/print_pages.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );
if( !$gBitSystem->isFeatureActive( 'feature_wiki_multiprint' ) ) {
	$gBitSystem->fatalError( tra("This feature is disabled").": feature_wiki_multiprint" );
}
// Now check permissions if user can view wiki pages
$gBitSystem->verifyPermission( 'bit_p_view' );

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
$smarty->assign('find', $find);
if (isset($_REQUEST["addpage"])) {
	if (!in_array($_REQUEST["title"], $printpages)) {
		$printpages[] = $_REQUEST["title"];
	}
}
if (isset($_REQUEST["clearpages"])) {
	$printpages = array();
}
$smarty->assign('printpages', $printpages);
$form_printpages = urlencode(serialize($printpages));
$smarty->assign('form_printpages', $form_printpages);
$pages = $wikilib->getList(0, -1, 'title_asc', $find);
$smarty->assign_by_ref('pages', $pages["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/print_pages.tpl');
?>
