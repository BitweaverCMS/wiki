<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_last_modif_pages.php,v 1.6 2007/05/23 04:20:37 laetzer Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_last_modif_pages.php,v 1.6 2007/05/23 04:20:37 laetzer Exp $
 * @package wiki
 * @subpackage modules
 */
global $gQueryUserId, $moduleParams, $wikilib;
$params = $moduleParams['module_params'];

if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	require_once( WIKI_PKG_PATH.'BitPage.php' );
	$listHash = array(
		'max_records' => $moduleParams['module_rows'],
		'sort_mode' => 'last_modified_desc',
		'user_id' => $gQueryUserId,
	);

	$modLastModif = $wikilib->getList( $listHash );

	$gBitSmarty->assign( 'modLastModif', $modLastModif );
	$gBitSmarty->assign( 'maxlen', isset( $params["maxlen"] ) ? $params["maxlen"] : 0 );
}
?>