<?php
/**
 * $Header$
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id this is a test$
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
