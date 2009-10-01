<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.php,v 1.9 2009/10/01 14:17:07 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: mod_random_pages.php,v 1.9 2009/10/01 14:17:07 wjames5 Exp $
 * @package wiki
 * @subpackage modules
 */
 
/**
 * Required files
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
$wp = new BitPage();

if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	$listHash = array(
		'sort_mode' => 'random',
		'max_records' => $moduleParams['module_rows'],
	);
	$pages = $wp->getList( $listHash );
	$gBitSmarty->assign( 'modRandomPages', $pages );
}
?>
