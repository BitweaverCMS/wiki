<?php
global $gBitThemes, $gQueryUserId, $gBitSmarty, $fHomepage, $gBitSystem, $module_params, $module_rows;

$errors = array();
$success = array();
$gBitSmarty->assign_by_ref('errors', $errors);
$gBitSmarty->assign_by_ref('success', $success);

if (!empty($_REQUEST['fSubmitCenterWikiPageSettings'])) {
	if (empty($fHomepage) || $fHomepage != $gBitUser->mUserId) {
		$errors[] = "You cannot modify settings for this module";
		break 2;
	}
	$storeParams = array('page' => $_REQUEST['page_name'], 'display_title' => (!empty($_REQUEST['display_title']) ? $_REQUEST['display_title'] : NULL));
	$gBitThemes->storeModuleParameters('bitpackage:wiki/center_wiki_page.tpl', $gBitUser->mUserId, $storeParams);
	$success[] = "Center wiki page settings successfully saved";
}

// Get Parameters Assigned to this module
if( !empty( $gQueryUserId ) ) {
	$module_params = $gBitThemes->getModuleParameters('bitpackage:wiki/center_wiki_page.tpl', $gQueryUserId);
} else {
}
$gBitSmarty->assign_by_ref('modParams', $module_params);

// Load up the correct wiki page
$_REQUEST['page_id'] = ( !empty( $module_params['page_id'] ) ? $module_params['page_id'] : NULL );
$_REQUEST['content_id'] = ( !empty( $module_params['content_id'] ) ? $module_params['content_id'] : NULL );
$_REQUEST['page'] = ( !empty( $module_params['page'] ) ? $module_params['page'] : 'HomePage' );
include_once( WIKI_PKG_PATH."lookup_page_inc.php" );

// Parse the wiki page data (This should be done by BitPage->load shouldn't it?)
$gContent->mInfo['parsed_data'] = $gContent->parseData();

// userOwnsPage determines whether or not the viewing user owns the page (i.e. this is the center_wiki_page on their user homepage)
$gBitSmarty->assign('userOwnsPage', (!empty($fHomepage) && $fHomepage == $gBitUser->mUserId));

$gBitSmarty->assign('fEditCenterWikiPageSettings', !empty($_REQUEST['fEditCenterWikiPageSettings']) ? TRUE : FALSE);

?>
