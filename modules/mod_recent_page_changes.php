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
global $gQueryUserId, $moduleParams;

/**
 * required setup
 */
 
if( $gBitUser->hasPermission( 'p_wiki_view_page' ) ) {
	require_once( WIKI_PKG_CLASS_PATH.'BitPage.php' );
	$wp = new BitPage();

	$listHash = array(
		'sort_mode' => 'last_modified_desc',
		'user_id' => $gQueryUserId,
	);
	if( !empty( $moduleParams['module_rows'] ) ) {
		$listHash['max_records'] = $moduleParams['module_rows'];
	}
	$modLastModif = $wp->getList( $listHash );

	$_template->tpl_vars['modLastModif'] = new Smarty_variable( $modLastModif );
	if( !empty( $moduleParams['module_params']["maxlen"] ) ) {
		$_template->tpl_vars['maxlen'] = new Smarty_variable( isset( $moduleParams['module_params']["maxlen"] ) );
	}
}
