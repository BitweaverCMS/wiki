<?php
	global $gBitSystem, $gBitUser, $gBitSmarty;
	$gBitSystem->registerPackage( 'wiki', dirname( __FILE__).'/' );

	define('BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

	if($gBitSystem->isPackageActive( 'wiki' ) ) {
		if ($gBitUser->hasPermission( 'bit_p_view' )) {
			$gBitSystem->registerAppMenu( WIKI_PKG_NAME, ucfirst( WIKI_PKG_DIR ), WIKI_PKG_URL.'index.php', 'bitpackage:wiki/menu_wiki.tpl', 'wiki');
		}

		$gBitSystem->registerNotifyEvent( array( "wiki_page_changes" => tra("Any wiki page is changed") ) );

		// Stuff found in kernel that is package dependent - wolff_borg
		include_once( WIKI_PKG_PATH.'diff.php' );

		$wikiHomePage = $gBitSystem->getPreference("wikiHomePage", 'HomePage');
	}
?>
