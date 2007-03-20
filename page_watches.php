<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/page_watches.php,v 1.2 2007/03/20 16:56:34 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_watches.php,v 1.2 2007/03/20 16:56:34 spiderr Exp $
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

//vd($gContent->mPageId);vd($gContent->mInfo);
if( !$gContent->isValid() || empty( $gContent->mInfo ) ) {
	$gBitSystem->fatalError( "Unknown page" );
}

$watches = NULL;
if( !empty( $gContent->mPageId ) ) {

    $event = 'wiki_page_changed';    
    $watches = $gBitUser->get_event_watches($event, $gContent->mPageId);
    $gBitSmarty->assign_by_ref('watches', $watches);
    }

// Display the template
$gBitSystem->display( 'bitpackage:wiki/page_watches.tpl');

?>
