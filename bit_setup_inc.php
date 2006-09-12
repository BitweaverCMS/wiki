<?php
global $gBitSystem, $gBitUser;

$registerHash = array(
	'package_name' => 'wiki',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

define('BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

if($gBitSystem->isPackageActive( 'wiki' ) ) {
	if ($gBitUser->hasPermission( 'p_wiki_view_page' )) {
		$menuHash = array(
			'package_name'  => WIKI_PKG_NAME,
			'index_url'     => WIKI_PKG_URL.'index.php',
			'menu_template' => 'bitpackage:wiki/menu_wiki.tpl',
		);
		$gBitSystem->registerAppMenu( $menuHash );
	}

	$gBitSystem->registerNotifyEvent( array( "wiki_page_changes" => tra("Any wiki page is changed") ) );

	// Stuff found in kernel that is package dependent - wolff_borg
	include_once( WIKI_PKG_PATH.'diff.php' );

	$wiki_home_page = $gBitSystem->getConfig("wiki_home_page", 'HomePage');
}
?>
