<?php

use Bitweaver\Wiki\BitPage;
global $moduleParams, $gBitSmarty;

// Load up the correct wiki page
$lookupHash['page_id']    = !empty( $moduleParams['module_params']['page_id'] )    ? $moduleParams['module_params']['page_id'] : null;
$lookupHash['content_id'] = !empty( $moduleParams['module_params']['content_id'] ) ? $moduleParams['module_params']['content_id'] : null;
$lookupHash['page']       = !empty( $moduleParams['module_params']['page'] )       ? $moduleParams['module_params']['page'] : null;

$showTitle = true;
if( !empty( $moduleParams['module_params']['notitle'] ) ) {
	$showTitle = false;
} elseif( !empty( $moduleParams['title'] )) {
	$modulePage->mInfo['title'] = $moduleParams['title'];
}
$gBitSmarty->assign( 'showTitle', $showTitle );
$gBitSmarty->assign( 'wikiPage', $modulePage );
