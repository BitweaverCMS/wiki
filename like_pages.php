<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/like_pages.php,v 1.4 2006/01/27 21:57:53 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: like_pages.php,v 1.4 2006/01/27 21:57:53 squareing Exp $
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
$gBitSystem->verifyFeature( 'feature_likePages' );
$gBitSystem->verifyPermission( 'bit_p_view', tra( "Permission denied you cannot view pages like this page" ) );

// Get the page from the request var or default it to HomePage
if( !$gContent->isValid() ) {
	$gBitSmarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

$likepages = $wikilib->get_like_pages( $gContent->mInfo['title'] );
$gBitSmarty->assign_by_ref('likepages', $likepages);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/like_pages.tpl');
$gBitSmarty->assign('show_page_bar', 'y');
?>
