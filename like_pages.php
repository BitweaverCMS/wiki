<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/like_pages.php,v 1.1.1.1.2.1 2005/06/27 17:47:44 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: like_pages.php,v 1.1.1.1.2.1 2005/06/27 17:47:44 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
include_once( WIKI_PKG_PATH.'lookup_page_inc.php' );
$gBitSystem->verifyPackage( 'wiki' );
if ($feature_likePages != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_likePages");
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get the page from the request var or default it to HomePage
if( !$gContent->isValid() ) {
	$smarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );
// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$smarty->assign('msg', tra("Permission denied you cannot view pages like this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}

$likepages = $wikilib->get_like_pages( $gContent->mInfo['title'] );
$smarty->assign_by_ref('likepages', $likepages);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/like_pages.tpl');
$smarty->assign('show_page_bar', 'y');
?>
