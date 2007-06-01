<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.php,v 1.7 2007/06/01 14:09:46 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_random_pages.php,v 1.7 2007/06/01 14:09:46 squareing Exp $
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
