<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/admin/upgrades/1.0.1.php,v 1.2 2008/12/22 12:36:38 squareing Exp $
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => WIKI_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "Drop unused tables.",
	'post_upgrade' => NULL,
);
$gBitInstaller->registerPackageUpgrade( $infoHash, array(

array( 'DATADICT' => array(
	array( 'DROPTABLE' => array(
		'wiki_received_pages',
		'wiki_tags',
		'wiki_ext',
	)),
)),

));
?>
