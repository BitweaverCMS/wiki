<?php
global $gBitInstaller;

$gBitInstaller->registerPackageUpgrade( WIKI_PKG_NAME, '1.0.2-beta', array(

array( 'ALTER' => array(
	'something' => array(
		'content_id' => array( '`content_id`', 'I4' ),
	),
)),

));
?>
