<?php
global $gBitInstaller;

$infoHash = array(
	'package'     => WIKI_PKG_NAME,
	'version'     => '1.0.2-beta',
	'description' => "Creates a test table 'test_table_1'.",
	'post_upgrade' => NULL,
);


$gBitInstaller->registerPackageUpgrade( $infoHash, array(

array( 'DATADICT' => array(
	array( 'CREATE' => array (
		'test_table_1' => "
			test_col_1 C(32) PRIMARY,
			test_col_2 C(64)
		",
	)),
)),

));
?>
