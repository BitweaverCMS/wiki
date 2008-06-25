<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page_permissions.php,v 1.12 2008/06/25 22:21:29 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_permissions.php,v 1.12 2008/06/25 22:21:29 spiderr Exp $
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


$gBitSystem->verifyPackage( 'wiki' );

// Make sure $gContent is set
if( !$gContent->isValid() ) {
	$gBitSmarty->assign('msg', tra( "No page indicated" ) );
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'display' ));
	die;
}

// make comment count for this page available for templates
$gComment = new LibertyComment( NULL, $gContent->mContentId );
$numComments = $gComment->getNumComments($gContent->mContentId);
$gBitSmarty->assign('comments_count', $numComments);


// Let creator set permissions
if( $gBitSystem->isFeatureActive( 'wiki_creator_admin' ) && $gContent->isOwner() ) {
	$gBitUser->setPreference( 'p_wiki_admin', TRUE );
}

// Now check permissions to access this page
if( !$gBitUser->hasPermission( 'p_wiki_admin' ) ) {
	$gBitSmarty->assign( 'msg', tra( "Permission denied you cannot assign permissions for this page" ) );
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'display' ));
	die;
}

if( isset( $_REQUEST["addemail"] ) ) {
	$notificationlib->add_mail_event( 'wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId, $_REQUEST["email"] );
}
if( isset( $_REQUEST["removeemail"] ) ) {
	$notificationlib->remove_mail_event( 'wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId, $_REQUEST["removeemail"] );
}

$emails = $notificationlib->get_mail_events( 'wiki_page_changes', $gContent->mInfo['content_type_guid'] . $gContent->mContentId );
$gBitSmarty->assign( 'emails', $emails );

if( !$gBitUser->isAdmin() ) {
	foreach( $gBitUser->mPerms as $key => $perm ) {
		if( $perm['package'] == 'wiki' ) {
			$assignPerms[$key] = $perm;
		}
	}
}

$gBitSmarty->assign_by_ref( 'pageInfo', $gContent->mInfo );

require_once( LIBERTY_PKG_PATH.'content_permissions_inc.php' );

$gBitSystem->display( 'bitpackage:wiki/page_permissions.tpl', tra( 'Page Permissions' ) , array( 'display_mode' => 'display' ));
?>
