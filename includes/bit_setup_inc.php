<?php
namespace Bitweaver\Wiki;
use Bitweaver\KernelTools;

global $gBitSystem, $gBitUser;

$pRegisterHash = [
	'package_name' => 'wiki',
	'package_path' => dirname( dirname( __FILE__ ) ).'/',
	'homeable' => true,
];
// fix to quieten down VS Code which can't see the dynamic creation of these ...
define( 'WIKI_PKG_NAME', $pRegisterHash['package_name'] );
define( 'WIKI_PKG_URL', BIT_ROOT_URL . basename( $pRegisterHash['package_path'] ) . '/' );
define( 'WIKI_PKG_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/' );
define( 'WIKI_PKG_INCLUDE_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/includes/'); 
define( 'WIKI_PKG_CLASS_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/includes/classes/');
define( 'WIKI_PKG_ADMIN_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/admin/'); 

$gBitSystem->registerPackage( $pRegisterHash );

define( 'BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

if( $gBitSystem->isPackageActive( 'wiki' )) {
	if( $gBitUser->hasPermission( 'p_wiki_view_page' )) {
		$menuHash = [
			'package_name'       => WIKI_PKG_NAME,
			'index_url'          => WIKI_PKG_URL.'index.php',
			'menu_template'      => 'bitpackage:wiki/menu_wiki.tpl',
		];
		$gBitSystem->registerAppMenu( $menuHash );
	}

	$gBitSystem->registerNotifyEvent( [ "wiki_page_changes" => KernelTools::tra( "Any wiki page is changed" ) ] );
}