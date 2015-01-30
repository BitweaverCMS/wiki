<?php
global $moduleParams, $gBitSmarty;

// Load up the correct wiki page
$lookupHash['page_id']    = ( !empty( $moduleParams['module_params']['page_id'] )    ? $moduleParams['module_params']['page_id'] : NULL );
$lookupHash['content_id'] = ( !empty( $moduleParams['module_params']['content_id'] ) ? $moduleParams['module_params']['content_id'] : NULL );
$lookupHash['page']       = ( !empty( $moduleParams['module_params']['page'] )       ? $moduleParams['module_params']['page'] : 'HomePage' );
// make sure gContent doesn't hold any information in case this is included multiple times
$gContent = NULL;
include( WIKI_PKG_PATH."lookup_page_inc.php" );
$showTitle = TRUE;
if( !empty( $moduleParams['module_params']['notitle'] ) ) {
	$showTitle = FALSE;
} elseif( !empty( $moduleParams['title'] )) {
	$gContent->mInfo['title'] = $moduleParams['title'];
}
$_template->tpl_vars['showTitle'] = new Smarty_variable( $showTitle );
$_template->tpl_vars['gContent'] = new Smarty_variable( $gContent );
