<?php
global $modlib, $gQueryUserId, $gBitSmarty, $fHomepage;

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
	$modlib->store_module_params('bitpackage:wiki/center_wiki_page.tpl', $gBitUser->mUserId, $storeParams);
	$success[] = "Center wiki page settings successfully saved";
}

// Get Parameters Assigned to this module
$modParams = $modlib->get_module_params('bitpackage:wiki/center_wiki_page.tpl', $gQueryUserId);
$gBitSmarty->assign_by_ref('modParams', $modParams);

// Load up the correct wiki page
$_REQUEST['page'] = (!empty($modParams['page']) ? $modParams['page'] : 'HomePage');
include_once(WIKI_PKG_PATH."lookup_page_inc.php");

// Parse the wiki page data (This should be done by BitPage->load shouldn't it?)
$gContent->mInfo['parsed_data'] = $gContent->parseData();

// userOwnsPage determines whether or not the viewing user owns the page (i.e. this is the center_wiki_page on their user homepage)
$gBitSmarty->assign('userOwnsPage', (!empty($fHomepage) && $fHomepage == $gBitUser->mUserId));

$gBitSmarty->assign('fEditCenterWikiPageSettings', !empty($_REQUEST['fEditCenterWikiPageSettings']) ? TRUE : FALSE);

?>
