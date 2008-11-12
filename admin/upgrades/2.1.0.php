<?php
/**
 * @version $Header: 
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => WIKI_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "This upgrade replaces unused meta tables with new ones. These meta tables are used to store meta data of uploaded files.",
	'post_upgrade' => NULL,
);
$gBitInstaller->registerPackageUpgrade( $infoHash, array(

array( 'DATADICT' => array(
	array( 'ALTER' => array(
		'wiki_footnotes' => array(
			'user_id' => array( '`user_id`', 'I4' ), // , 'NOTNULL' ),
	))),
)),

array( 'PHP' => '
	// make sure plugins are up to date.
	global $gLibertySystem;
	$gLibertySystem->scanAllPlugins();
'
)

));
?>
