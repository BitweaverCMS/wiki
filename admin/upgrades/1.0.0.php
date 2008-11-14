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
	array( 'PGSQL' => array(
		"UPDATE `".BIT_DB_PREFIX."wiki_footnotes` SET `user_id` = `temp_column`::integer",
	)),
	array( 'SQL92' => array(
		"UPDATE `".BIT_DB_PREFIX."wiki_footnotes` SET `user_id` = `temp_column`",
	)),
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
