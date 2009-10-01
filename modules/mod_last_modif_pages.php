<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_last_modif_pages.php,v 1.10 2009/10/01 14:17:07 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: mod_last_modif_pages.php,v 1.10 2009/10/01 14:17:07 wjames5 Exp $
 * @package wiki
 * @subpackage modules
 */
global $gQueryUserId, $moduleParams;
$params = $moduleParams['module_params'];

/**
 * required setup
 */
 
if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	require_once( WIKI_PKG_PATH.'BitPage.php' );
	$wp = new BitPage();

	$listHash = array(
		'max_records' => $moduleParams['module_rows'],
		'sort_mode' => 'last_modified_desc',
		'user_id' => $gQueryUserId,
	);
	$modLastModif = $wp->getList( $listHash );

	$gBitSmarty->assign( 'modLastModif', $modLastModif );
	$gBitSmarty->assign( 'maxlen', isset( $params["maxlen"] ) ? $params["maxlen"] : 0 );
}
?>
