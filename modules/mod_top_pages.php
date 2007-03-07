<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.php,v 1.8 2007/03/07 18:48:45 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_top_pages.php,v 1.8 2007/03/07 18:48:45 squareing Exp $
 * @package wiki
 * @subpackage modules
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
global $gQueryUser, $module_rows, $module_params;

if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	$modWiki = new BitPage();
	$listHash = array(
		'max_records' => $module_rows,
		'sort_mode' => 'hits_desc',
		'user_id' => !empty( $module_params['user_pages'] ) ? $gQueryUser->mUserId : NULL,
	);
	$modRank = $modWiki->getList( $listHash );
	$gBitSmarty->assign( 'modTopPages', $modRank );
}
?>
