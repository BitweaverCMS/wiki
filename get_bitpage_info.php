<?php
/**
 * $Header: get_bitpage_info.php
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: get_bitpage_info.php
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once( WIKI_PKG_PATH.'BitBook.php');

$gBitSystem->verifyPackage( 'wiki' );

$gContent->verifyViewPermission();

// Check permissions to access this page
if( !$gContent->isValid() ) {
	$gBitSystem->setHttpStatus( 404 );
	$gBitSystem->fatalError( tra( 'Page cannot be found' ));
}

$displayHash = array( 'perm_name' => 'p_wiki_view_page' );
$gContent->invokeServices( 'content_display_function', $displayHash );

// Let creator set permissions
if($gBitSystem->isFeatureActive( 'wiki_creator_admin' )) {
	if( $gContent->isOwner() ) {
		$gBitUser->setPreference( 'p_wiki_admin', TRUE );
	}
}

if( $gBitSystem->isFeatureActive( 'wiki_backlinks' ) ) {
	// Get the backlinks for the page "page"
	$backlinks = $gContent->getBacklinks();
	$gBitSmarty->assign_by_ref('backlinks', $backlinks);
}

// Update the pagename with the canonical name.  This makes it
// possible to link to a page using any case, but the page is still
// displayed with the original capitalization.  So if there's a page
// called 'About Me', then one can conveniently make a link to it in
// the text as '... learn more ((about me)).'.  When the link is
// followed,
$gBitSystem->setBrowserTitle( $gContent->mInfo['title'] );

//$gBitSmarty->assign_by_ref('last_modified',date("l d of F, Y  [H:i:s]",$gContent->mInfo["last_modified"]));
$gBitSmarty->assign_by_ref('last_modified',$gContent->mInfo["last_modified"]);
if(empty($gContent->mInfo["user"])) {
	$gContent->mInfo["user"]='anonymous';
}
$gBitSmarty->assign_by_ref('lastUser',$gContent->mInfo["user"]);

// Comments engine!
if( $gBitSystem->isFeatureActive( 'wiki_comments' ) ) {
	$comments_vars = Array('page');
	$comments_prefix_var='wiki page:';
	$comments_object_var='page';
	$commentsParentId = $gContent->mContentId;
	$comments_return_url = WIKI_PKG_URL.'index.php?page_id='.$gContent->mPageId;
	if ( ( isset( $_REQUEST["comments_page"] ) && ( $_REQUEST["comments_page"] == '1' ) )
	|| !empty($_REQUEST['view_comment_id'])
	
	) {
		 $gBitSmarty->assign('comments_page',1);
		 $comments_on_separate_page = 1;
		$comments_return_url = WIKI_PKG_URL.'index.php?page_id='.$gContent->mPageId . '&' . 'comments_page=1';
		if ( !empty($_REQUEST['view_comment_id']) ) {
			$comments_return_url .= '&comments_maxComments=1';
		}
		$gBitSmarty->assign( 'nav_highlight','comments');
	}
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

if( $gBitSystem->isFeatureActive( 'wiki_attachments' ) ) {
	$gBitSmarty->assign('atts',$gContent->mStorage);
	$gBitSmarty->assign('atts_count',count($gContent->mStorage));
}

if( $gBitSystem->isFeatureActive( 'wiki_footnotes' ) && $gBitUser->isValid() ) {
	if( $footnote = $gContent->getFootnote( $gBitUser->mUserId ) ) {
		$gBitSmarty->assign( 'footnote', $gContent->parseData( $footnote ) );
	}
}

if( $gBitSystem->isFeatureActive( 'wiki_copyrights' ) ) {
	require_once( WIKI_PKG_PATH.'copyrights_lib.php' );
	$copyrights = $copyrightslib->list_copyrights( $gContent->mPageId );
	$gBitSmarty->assign('pageCopyrights', $copyrights["data"]);
}

if( $gBitSystem->isFeatureActive( 'users_watches' ) ) {
	$gBitSmarty->assign('user_watching_page','n');
	if( $watch = $gBitUser->getEventWatches( 'wiki_page_changed', $gContent->mPageId ) ) {
		$gBitSmarty->assign('user_watching_page','y');
	}
}
$sameurl_elements=Array('title','page');

// Display the Index Template
$gBitSmarty->assign_by_ref( 'pageInfo', $gContent->mInfo );


?>
