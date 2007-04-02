<?php
global $moduleParams, $gContent;
// Load up the correct wiki page
$lookupHash['page_id']    = ( !empty( $moduleParams['module_params']['page_id'] )    ? $moduleParams['module_params']['page_id'] : NULL );
$lookupHash['content_id'] = ( !empty( $moduleParams['module_params']['content_id'] ) ? $moduleParams['module_params']['content_id'] : NULL );
$lookupHash['page']       = ( !empty( $moduleParams['module_params']['page'] )       ? $moduleParams['module_params']['page'] : 'HomePage' );
// make sure gContent doesn't hold any information in case this is included multiple times
$gContent = NULL;
include( WIKI_PKG_PATH."lookup_page_inc.php" );
if( !empty( $moduleParams['title'] )) {
	$gContent->mInfo['title'] = $moduleParams['title'];
}
?>
