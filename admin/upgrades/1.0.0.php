<?php
/**
 * @version $Header: 
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => WIKI_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "Minor fix to table column type.",
	'post_upgrade' => NULL,
);
$gBitInstaller->registerPackageUpgrade( $infoHash, array(

array( 'DATADICT' => array(
	array( 'ALTER' => array(
		'wiki_footnotes' => array(
			'user_id' => array( '`user_id`', 'I4' ),
	))),
)),

));
?>
