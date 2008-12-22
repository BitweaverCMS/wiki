<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/admin/upgrades/1.0.0.php,v 1.5 2008/12/22 12:37:46 squareing Exp $
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => WIKI_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "Minor fix to user_id column type in wiki_footnotes.",
	'post_upgrade' => NULL,
);

// all we are doing is change the column type of user_id for wiki_footnotes.
// postgresql < 8.2 doesn't allow easy column type changing
// and therefore we need to undergo this annoying dance.
$gBitInstaller->registerPackageUpgrade( $infoHash, array(

array( 'DATADICT' => array(
	// rename original column
	array( 'RENAMECOLUMN' => array(
		'wiki_footnotes' => array(
			'`user_id`' => "`temp_column` VARCHAR(40)",
		),
	)),
	// insert new column
	array( 'ALTER' => array(
		'wiki_footnotes' => array(
			'user_id' => array( '`user_id`', 'I4' ),
	))),
)),

// copy data into new column
array( 'QUERY' =>
	// postgres > 8.2 needs to have the type cast
	array(
		'PGSQL' => array( "UPDATE `".BIT_DB_PREFIX."wiki_footnotes` SET `user_id` = `temp_column`::integer" ),
		'SQL92' => array( "UPDATE `".BIT_DB_PREFIX."wiki_footnotes` SET `user_id` = `temp_column`" ),
	),
),

array( 'DATADICT' => array(
	// drop old column
	array( 'DROPCOLUMN' => array(
		'wiki_footnotes' => array( '`temp_column`' ),
	)),
	// reconstruct constraints, sequences and indexes
)),

));
?>
