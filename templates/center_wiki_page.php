<?php
global $moduleParams, $gBitSmarty;

// Load up the correct wiki page
$lookupHash['page_id']    = ( !empty( $moduleParams['module_params']['page_id'] )    ? $moduleParams['module_params']['page_id'] : NULL );
$lookupHash['content_id'] = ( !empty( $moduleParams['module_params']['content_id'] ) ? $moduleParams['module_params']['content_id'] : NULL );
$lookupHash['page']       = ( !empty( $moduleParams['module_params']['page'] )       ? $moduleParams['module_params']['page'] : NULL );

require_once( WIKI_PKG_CLASS_PATH.'BitPage.php' );
$modulePage = BitPage::lookupObject( $lookupHash );
$showTitle = TRUE;
if( !empty( $moduleParams['module_params']['notitle'] ) ) {
	$showTitle = FALSE;
} elseif( !empty( $moduleParams['title'] )) {
	$modulePage->mInfo['title'] = $moduleParams['title'];
}
$_template->tpl_vars['showTitle'] = new Smarty_variable( $showTitle );
$_template->tpl_vars['wikiPage'] = new Smarty_variable( $modulePage );
