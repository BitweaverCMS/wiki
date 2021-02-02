<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once( WIKI_PKG_CLASS_PATH.'BitBook.php');

$gBitSystem->verifyPackage( 'wiki' );

$gContent->verifyViewPermission();

// Check permissions to access this page
if( !$gContent->isValid() || !is_a( $gContent, 'BitPage' ) ) {
	$gBitSystem->fatalError( tra( 'Page cannot be found' ), NULL, NULL, HttpStatusCodes::HTTP_GONE );
}

$displayHash = array( 'perm_name' => 'p_wiki_view_page' );
$gContent->invokeServices( 'content_display_function', $displayHash );

// Let creator set permissions
// does this work with setPreference()??? - xing - Tuesday Oct 07, 2008   17:51:38 CEST
if( $gBitSystem->isFeatureActive( 'wiki_creator_admin' ) && $gContent->isOwner() ) {
	$gBitUser->setPreference( 'p_wiki_admin', TRUE );
}

// doesn't seem to be used - xing - Tuesday Oct 07, 2008   17:52:42 CEST
//if( isset( $_REQUEST["copyrightpage"] )) {
//	$gBitSmarty->assignByRef( 'copyrightpage', $_REQUEST["copyrightpage"] );
//}

// Get the backlinks for the page "page"
if( $gBitSystem->isFeatureActive( 'wiki_backlinks' )) {
	$gBitSmarty->assign( 'backlinks', $gContent->getBacklinks() );
}

// Now increment page hits since we are visiting this page
$gContent->addHit();

// Check if we have to lock / unlock this page
if( !empty( $_REQUEST["action"] ) && ( $_REQUEST["action"] == 'lock' || $_REQUEST["action"] == 'unlock' )
	&& ( $gContent->hasAdminPermission() || ($gContent->hasUserPermission( 'p_wiki_lock_page' ) && $gBitSystem->isFeatureActive( 'wiki_usrlock' )) ) ) {
	$gContent->setLock( $_REQUEST["action"] == 'lock' ? 'L' : NULL );
}

// Process an undo here
if( !empty( $_REQUEST["undo"] ) && !$gContent->isLocked() && ( $gContent->hasUpdatePermission() || $gContent->hasUserPermission( 'p_wiki_rollback' ))) {
	// Remove the last version
	$gContent->removeLastVersion();
}

// work out if this page has slides
if( $gBitSystem->isFeatureActive( 'wiki_uses_slides' )) {
	$slides = split( "-=[^=]+=-", $gContent->mInfo["data"] );
	if( count( $slides ) <= 1 ) {
		$slides = explode( defined( 'PAGE_SEP' ) ? PAGE_SEP : "...page...", $gContent->mInfo["data"] );
	}

	// if we have more than on slide, we let the templates know about it
	if( count( $slides ) > 1 ) {
		$gBitSmarty->assign( 'show_slideshow', 'y' );
	}
}

// ...page... stuff - TODO: this is cumbersome and should be cleaned up
$pages = $gContent->countSubPages( $gContent->getField( 'parsed_data' ) );
if( $pages > 1 ) {
	if( !isset( $_REQUEST['pagenum'] )) {
		$_REQUEST['pagenum'] = 1;
	}
	$gContent->mInfo['parsed_data'] = $gContent->getSubPage( $gContent->mInfo['parsed_data'], $_REQUEST['pagenum'] );
	$gBitSmarty->assign( 'pages', $pages );
	if( $pages > $_REQUEST['pagenum'] ) {
		$gBitSmarty->assign('next_page', $_REQUEST['pagenum'] + 1 );
	} else {
		$gBitSmarty->assign( 'next_page', $_REQUEST['pagenum'] );
	}
	if( $_REQUEST['pagenum'] > 1 ) {
		$gBitSmarty->assign( 'prev_page', $_REQUEST['pagenum'] - 1 );
	} else {
		$gBitSmarty->assign( 'prev_page', 1 );
	}
	$gBitSmarty->assign( 'first_page', 1 );
	$gBitSmarty->assign( 'last_page', $pages );
	$gBitSmarty->assign( 'pagenum', $_REQUEST['pagenum'] );
}

// Comments engine!
if( $gBitSystem->isFeatureActive( 'wiki_comments' )) {
	$comments_vars = array( 'page' );
	$comments_prefix_var = 'wiki page:';
	$comments_object_var = 'page';
	$commentsParentId = $gContent->mContentId;
	$comments_return_url = WIKI_PKG_URL.'index.php?page_id='.$gContent->mPageId;
	# Support displaying comments on their own page instead of on content page
	if( isset( $_REQUEST["comments_page"] ) && ( $_REQUEST["comments_page"] == '1' ) || !empty( $_REQUEST['view_comment_id'] )) {
		$gBitSmarty->assign( 'comments_page', 1 );
		$comments_on_separate_page = 1;
		$comments_return_url = WIKI_PKG_URL.'index.php?page_id='.$gContent->mPageId.'&amp;comments_page=1';
		if ( !empty($_REQUEST['view_comment_id']) ) {
			$comments_return_url .= '&amp;comments_maxComments=1';
		}
	}
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

// Footnotes
if( $gBitSystem->isFeatureActive( 'wiki_footnotes' ) && $gBitUser->isValid() ) {
	if( $footnote = $gContent->getFootnote( $gBitUser->mUserId ) ) {
		$gBitSmarty->assign( 'footnote', LibertyContent::parseDataHash( $footnote ) );
	}
}

// Copyrights
if( $gBitSystem->isFeatureActive( 'wiki_copyrights' ) ) {
	require_once( WIKI_PKG_PATH.'copyrights_lib.php' );
	$copyrights = $copyrightslib->list_copyrights( $gContent->mPageId );
	$gBitSmarty->assign('pageCopyrights', $copyrights["data"]);
}

// Watches
if( $gBitSystem->isFeatureActive( 'users_watches' ) ) {
	if( !empty( $_REQUEST['watch_event'] ) ) {
		if( $gBitUser->isRegistered() ) {
			if( $_REQUEST['watch_action']=='add' ) {
				$gBitUser->storeWatch( $_REQUEST['watch_event'], $_REQUEST['watch_object'], $gContent->mContentTypeGuid, $gContent->mPageName, $gContent->getDisplayUrl() );
			} else {
				$gBitUser->expungeWatch( $_REQUEST['watch_event'], $_REQUEST['watch_object'] );
			}
		} else {
			$gBitSystem->fatalError( tra( "This feature requires a registered user. ").": users_watches" );
		}
	}

	if( $watch = $gBitUser->getEventWatches( 'wiki_page_changed', $gContent->mPageId ) ) {
		$gBitSmarty->assign( 'user_watching_page', 'y' );
	}
}

if( $gContent->isValid() && $gBitSystem->isPackageActive( 'stickies' ) ) {
	require_once( STICKIES_PKG_PATH.'BitSticky.php' );
	global $gNote;
	$gNote = new BitSticky( NULL, NULL, $gContent->mContentId );
	$gNote->load();
	$gBitSmarty->assignByRef( 'stickyInfo', $gNote->mInfo );
}

$pageInfo = $gContent->mInfo;
$pageInfo['title'] = $gContent->getTitle();

// Display the Index Template
$gBitSmarty->assignByRef( 'pageInfo', $pageInfo );

// S5 slideshows
if( isset( $_REQUEST['s5'] )) {
	include_once( WIKI_PKG_PATH.'s5.php');
}

$gBitSystem->display( 'bitpackage:wiki/show_page.tpl', $pageInfo['title'], array( 'display_mode' => 'display' ));
?>
