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
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'wiki' );
require_once( WIKI_PKG_CLASS_PATH.'BitPage.php' );

if( !empty( $_REQUEST['structure_id'] ) ) {
	include( LIBERTY_PKG_PATH.'display_structure_inc.php' );
} else {
	// if no page set
	if ( !isset( $_REQUEST['page'] ) and !isset( $_REQUEST['page_id'] ) ) {
		// if auto create home page disabled just get a list
		if( $gBitSystem->isFeatureActive( 'wiki_disable_auto_home' ) ){
			include( WIKI_PKG_PATH.'list_pages.php' );
			die;
		}
		// auto fetch/create default home page
		$_REQUEST['page'] = $gBitSystem->getConfig( 'wiki_home_page', 'HomePage' );

	}
	$gHome = new BitPage();
	$wikiHome = $gBitSystem->getConfig( "wiki_home_page", 'HomePage' );
	if( !($gHome->pageExists( $wikiHome ) ) ) {
		$homeHash = array(
			'title' => ( isset( $wikiHome ) ? $wikiHome : 'HomePage' ),
			'creator_user_id' => ROOT_USER_ID,
			'modifier_user_id' => ROOT_USER_ID,
			'edit' => 'Welcome to '.( $gBitSystem->getConfig( 'site_title', 'our site' ) ) );
		$gHome->store( $homeHash );
	}

	include( WIKI_PKG_PATH.'lookup_page_inc.php' );
	if( $gContent->isValid() ) {
		$gBitSystem->setCanonicalLink( $gContent->getDisplayUrl() );
	}
	include( WIKI_PKG_PATH.'display_bitpage_inc.php' );
}
