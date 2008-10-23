<?php
global $gBitInstaller;

$gBitInstaller->registerPackageUpgrade( WIKI_PKG_NAME, '1.0.3', array(

array( 'ALTER' => array(
	'something' => array(
		'content_id' => array( '`content_id`', 'I4' ),
	),
)),

));

$gBitInstaller->registerPackageDependencies( WIKI_PKG_NAME, '1.0.3', array(
	'kernel' => '0.0.2',
	'liberty' => '0.1.1-beta',
));
?>
