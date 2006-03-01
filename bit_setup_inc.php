<?php
global $gBitSystem, $gBitUser;

$registerHash = array(
	'package_name' => 'wiki',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

define('BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

if($gBitSystem->isPackageActive( 'wiki' ) ) {
	if ($gBitUser->hasPermission( 'bit_p_view' )) {
		$gBitSystem->registerAppMenu( WIKI_PKG_NAME, ucfirst( WIKI_PKG_DIR ), WIKI_PKG_URL.'index.php', 'bitpackage:wiki/menu_wiki.tpl', 'wiki');
	}

	$gBitSystem->registerNotifyEvent( array( "wiki_page_changes" => tra("Any wiki page is changed") ) );

	// Stuff found in kernel that is package dependent - wolff_borg
	include_once( WIKI_PKG_PATH.'diff.php' );

	$wiki_home_page = $gBitSystem->getConfig("wiki_home_page", 'HomePage');
}
?>
