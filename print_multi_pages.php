<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/print_multi_pages.php,v 1.1.1.1.2.1 2005/06/27 17:47:41 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: print_multi_pages.php,v 1.1.1.1.2.1 2005/06/27 17:47:41 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );
if ($feature_wiki_multiprint != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_wiki_multiprint");
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!isset($_REQUEST["printpages"])) {
	$smarty->assign('msg', tra("No pages indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
} else {
	$printpages = unserialize(urldecode($_REQUEST["printpages"]));
}
if (isset($_REQUEST["print"])) {
	
	// Create XMLRPC object
	$pages = array();
	foreach( $printpages as $contentId ) {
		$page = new BitPage( NULL, $contentId );
		if( $page->load() && $page->hasUserPermission( 'bit_p_view', TRUE )) {
			$page_info = $page->mInfo;
			$page_info["parsed"] = $page->parseData( $page_info["data"] );
			$pages[] = $page_info;
		}
	}
}
$smarty->assign_by_ref('pages', $pages);

// Display the template
$smarty->display("bitpackage:wiki/print_multi_pages.tpl");
?>
