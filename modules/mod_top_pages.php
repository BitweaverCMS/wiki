<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage modules
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
global $gQueryUser, $module_rows, $module_params;

extract( $moduleParams );

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
