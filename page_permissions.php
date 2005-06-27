<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page_permissions.php,v 1.1.1.1.2.1 2005/06/27 17:47:46 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_permissions.php,v 1.1.1.1.2.1 2005/06/27 17:47:46 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
include_once( WIKI_PKG_PATH.'BitBook.php');
include_once( KERNEL_PKG_PATH.'notification_lib.php' );
include_once( WIKI_PKG_PATH.'lookup_page_inc.php' );
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

	$gBitSystem->verifyPackage( 'wiki' );
	// Get the page from the request var or default it to HomePage
	if( !$gContent->isValid() ) {
		$smarty->assign('msg', tra("No page indicated"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	
	// Let creator set permissions
	if ($wiki_creator_admin == 'y') {
		if( $gContent->isOwner() ) {
			$bit_p_admin_wiki = 'y';
			$smarty->assign('bit_p_admin_wiki', 'y');
		}
	}

	// Now check permissions to access this page
	if (!$gBitUser->hasPermission( 'bit_p_admin_wiki' )) {
		$smarty->assign('msg', tra("Permission denied you cannot assign permissions for this page"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	if (isset($_REQUEST["addemail"])) {
		
		$notificationlib->add_mail_event('wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId, $_REQUEST["email"]);
	}
	if (isset($_REQUEST["removeemail"])) {
		
		$notificationlib->remove_mail_event('wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId, $_REQUEST["removeemail"]);
	}
	
	$emails = $notificationlib->get_mail_events('wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId);
	$smarty->assign('emails', $emails);

// Process the form to assign a new permission to this page
if (isset($_REQUEST["assign"])) {
	$gBitUser->assign_object_permission($_REQUEST["group_id"], $gContent->mContentId, $gContent->mInfo['content_type_guid'], $_REQUEST["perm"]);
}
/* TODO: CURRENTLY CANNOT ADD PERMISSIONS TO NEW STYLE STRUCTURES
// Process the form to assign a new permission to this structure
if(isset($_REQUEST["assignstructure"])) {
	$gBitUser->assign_object_permission($_REQUEST["group"],$gContent->mPageId,'wiki page',$_REQUEST["perm"]);
	$pages=$structlib->get_structure_pages($gContent->mPageId);
	foreach($pages as $subpage) {
		$gBitUser->assign_object_permission($_REQUEST["group"],$subpage,'wiki page',$_REQUEST["perm"]);
	}
}
*/
// Process the form to remove a permission from the page
if (isset($_REQUEST["action"])) {
	if ($_REQUEST["action"] == 'remove') {
		$gBitUser->remove_object_permission($_REQUEST["group_id"], $gContent->mContentId, $gContent->mInfo['content_type_guid'], $_REQUEST["perm"]);
	}
/* TODO: CURRENTLY CANNOT ADD PERMISSIONS TO NEW STYLE STRUCTURES
	if($_REQUEST["action"] == 'removestructure') {
		$gBitUser->remove_object_permission($_REQUEST["group"],$gContent->mPageId,'wiki page',$_REQUEST["perm"]);
		$pages=$structlib->get_structure_pages($gContent->mPageId);
		foreach($pages as $subpage) {
			$gBitUser->remove_object_permission($_REQUEST["group"],$subpage,'wiki page',$_REQUEST["perm"]);
		}
	}
*/
}
// Now we have to get the individual page permissions if any
$page_perms = $gBitUser->get_object_permissions( $gContent->mContentId, $gContent->mInfo['content_type_guid'] );
$smarty->assign_by_ref('page_perms', $page_perms);
// Get a list of groups
$listHash = array( 'sort_mode' => 'group_name_asc' );
$groups = $gBitUser->getAllGroups( $listHash );
$smarty->assign_by_ref('groups', $groups["data"]);
// Get a list of permissions
$perms = $gBitUser->getGroupPermissions( '', WIKI_PKG_NAME );
$smarty->assign_by_ref('perms', $perms);


$smarty->assign( (!empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'permissions').'TabSelect', 'tdefault' );

$smarty->assign('show_page_bar', 'y');
$gBitSystem->display( 'bitpackage:wiki/page_permissions.tpl');
?>
