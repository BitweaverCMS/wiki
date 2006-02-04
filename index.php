<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/index.php,v 1.4 2006/02/04 10:32:44 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: index.php,v 1.4 2006/02/04 10:32:44 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );

	if( !empty( $_REQUEST['structure_id'] ) ) {
		include( LIBERTY_PKG_PATH.'display_structure_inc.php' );
	} else {
		if ( !isset( $_REQUEST['page'] ) and !isset( $_REQUEST['page_id'] ) ) {
			$_REQUEST['page'] = $gBitSystem->getPreference( 'wikiHomePage', 'HomePage' );
		}
		$gHome = new BitPage();
		$wikiHome = $gBitSystem->getPreference("wikiHomePage", 'HomePage');
		if( !($gHome->pageExists( $wikiHome )) ) {
			$homeHash = array( 'title' => (isset( $wikiHome ) ? $wikiHome : 'HomePage'),
							   'creator_user_id' => ROOT_USER_ID,
							   'modifier_user_id' => ROOT_USER_ID,
							   'edit' => 'Welcome to '.( $gBitSystem->getPreference( 'siteTitle', 'our site' ) ) );
			$gHome->store( $homeHash );
		}

		include( WIKI_PKG_PATH.'lookup_page_inc.php' );
		include( WIKI_PKG_PATH.'display_bitpage_inc.php' );
	}

?>
