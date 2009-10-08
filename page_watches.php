<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/page_watches.php,v 1.9 2009/10/08 19:39:49 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: page_watches.php,v 1.9 2009/10/08 19:39:49 wjames5 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'users_watches' );
$gBitSystem->verifyPermission( 'p_admin_users', tra( "Permission denied you cannot browse these page watches" ) );

// Get the page from the request var or default it to HomePage
include( WIKI_PKG_PATH.'lookup_page_inc.php' );

// make comment count for this page available for templates
$gComment = new LibertyComment();
$numComments = $gComment->getNumComments($gContent->mContentId);
$gBitSmarty->assign('comments_count', $numComments);



//vd($gContent->mPageId);vd($gContent->mInfo);
if( !$gContent->isValid() || empty( $gContent->mInfo ) ) {
	$gBitSystem->fatalError( tra( "Unknown page" ));
}

$watches = NULL;
if( !empty( $gContent->mPageId ) ) {

    $event = 'wiki_page_changed';    
    $watches = $gBitUser->get_event_watches($event, $gContent->mPageId);
    $gBitSmarty->assign_by_ref('watches', $watches);
    $gBitSmarty->assign_by_ref( 'pageInfo', $gContent->mInfo );
    }

// Display the template
$gBitSystem->display( 'bitpackage:wiki/page_watches.tpl', NULL, array( 'display_mode' => 'display' ));

?>
