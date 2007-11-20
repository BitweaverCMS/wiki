<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/page_history.php,v 1.26 2007/11/20 10:02:18 jht001 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_history.php,v 1.26 2007/11/20 10:02:18 jht001 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_history' );

// Get the page from the request var or default it to HomePage
include( WIKI_PKG_PATH.'lookup_page_inc.php' );

//vd($gContent->mPageId);vd($gContent->mInfo);
if( !$gContent->isValid() || empty( $gContent->mInfo ) ) {
	$gBitSystem->fatalError( tra( "Unknown page" ));
}

$gContent->verifyViewPermission();
$gContent->verifyPermission( 'p_wiki_view_history' );

if (!empty( $_REQUEST['rollback_preview'] )) {
	$gBitSmarty->assign( 'rollback_preview', $_REQUEST['rollback_preview']);
	}

// set up stuff to get history working
$smartyContentRef = 'pageInfo';
$rollbackPerm     = 'p_wiki_rollback';
include_once( LIBERTY_PKG_PATH.'content_history_inc.php' );

// pagination stuff
$gBitSmarty->assign( 'page', $page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1 );
if( !empty( $_REQUEST['list_page'] )) {
	$gBitSmarty->assign( 'page', $page = !empty( $_REQUEST['list_page'] ) ? $_REQUEST['list_page'] : 1 );
}

$offset = ( $page - 1 ) * $gBitSystem->getConfig( 'max_records' );
$history = $gContent->getHistory( NULL, NULL, $offset, $gBitSystem->getConfig( 'max_records' ) );
$gBitSmarty->assign_by_ref( 'data', $history['data'] );
$gBitSmarty->assign_by_ref( 'listInfo', $history['listInfo'] );

// Display the template
$gBitSmarty->assign_by_ref( 'gContent', $gContent );
$gBitSystem->display( 'bitpackage:wiki/page_history.tpl');
?>
