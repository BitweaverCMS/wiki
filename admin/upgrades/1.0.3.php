<?php
global $gBitInstaller;

$infoHash = array(
	'package'     => WIKI_PKG_NAME,
	'version'     => '1.0.3',
	'description' => "Creates another test table 'test_table_2' and then removes both tables again. This way you can rerun this test process. All you need to do is uncomment the version store line in install_upgrade.php.",
	'post_upgrade' => NULL,
);

$gBitInstaller->registerPackageUpgrade( $infoHash, array(

	array( 'DATADICT' => array(
		array( 'DROPTABLE' => array(
			'test_table_1'
		)),
		array( 'CREATE' => array (
			'test_table_2' => "
				test_col_1 C(32) PRIMARY,
				test_col_2 C(64)
				",
			)),
		array( 'DROPTABLE' => array(
			'test_table_2'
		)),
	)),

));

$gBitInstaller->registerPackageDependencies( $infoHash, array(
	'kernel'  => '0.0.2',
	'liberty' => '0.1.1-beta',
));
?>
