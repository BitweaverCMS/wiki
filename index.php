<?php
// Initialization
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );

	if( !empty( $_REQUEST['structure_id'] ) ) {
		include( LIBERTY_PKG_PATH.'display_structure_inc.php' );
	} else {
		global $siteTitle;
		if ( !isset( $_REQUEST['page'] ) and !isset( $_REQUEST['page_id'] ) ) {
			$_REQUEST['page'] = $wikiHomePage;
		}
		$gHome = new BitPage();
		$wikiHome = $gBitSystem->getPreference("wikiHomePage", 'HomePage');
		if( !($gHome->pageExists( $wikiHome )) ) {
			$homeHash = array( 'title' => (isset( $wikiHome ) ? $wikiHome : 'HomePage'),
							   'creator_user_id' => ROOT_USER_ID,
							   'modifier_user_id' => ROOT_USER_ID,
							   'edit' => 'Welcome to '.(!empty( $siteTitle ) ? $siteTitle : 'our site') );
			$gHome->store( $homeHash );
		}

		include( WIKI_PKG_PATH.'lookup_page_inc.php' );
		include( WIKI_PKG_PATH.'display_bitpage_inc.php' );
	}

?>
