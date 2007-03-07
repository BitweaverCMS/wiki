<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_last_modif_pages.php,v 1.5 2007/03/07 18:48:45 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_last_modif_pages.php,v 1.5 2007/03/07 18:48:45 squareing Exp $
 * @package wiki
 * @subpackage modules
 */
global $gQueryUserId, $module_rows, $module_params, $wikilib;

/**
 * required setup
 */

if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	require_once( WIKI_PKG_PATH.'BitPage.php' );
	$listHash = array(
		'max_records' => $module_rows,
		'sort_mode' => 'last_modified_desc',
		'user_id' => $gQueryUserId,
	);
	$ranking = $wikilib->getList( $listHash );

	$gBitSmarty->assign( 'modLastModif', $ranking );
	$gBitSmarty->assign( 'maxlen', isset( $module_params["maxlen"] ) ? $module_params["maxlen"] : 0 );
}
?>
