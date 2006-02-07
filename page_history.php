<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/page_history.php,v 1.8 2006/02/07 01:19:19 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_history.php,v 1.8 2006/02/07 01:19:19 spiderr Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_history' );
$gBitSystem->verifyPermission( 'bit_p_view', tra( "Permission denied you cannot browse this page history" ) );

// Get the page from the request var or default it to HomePage
include( WIKI_PKG_PATH.'lookup_page_inc.php' );
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

//vd($gContent->mPageId);vd($gContent->mInfo);
if( !$gContent->isValid() || empty( $gContent->mInfo ) ) {
	$gBitSystem->fatalError( "Unknown page" );
}

$gBitSmarty->assign('source', 0);
// If we have to include a preview please show it
$gBitSmarty->assign('preview', false);
$gBitSmarty->assign('compare', 'n');
$gBitSmarty->assign('diff2', 'n');
if (isset($_REQUEST["delete"]) && isset($_REQUEST["hist"])) {
	foreach (array_keys($_REQUEST["hist"])as $version) {
		$gContent->expungeVersion( $version );
	}
} elseif (isset($_REQUEST['source'])) {
	$gBitSmarty->assign('source', $_REQUEST['source']);
	if ($_REQUEST['source'] == 'current') {
		$gBitSmarty->assign('sourcev', nl2br(htmlentities($gContent->mInfo['data'])));
	} else {
		$version = $gContent->getHistory($_REQUEST["source"]);
		$gBitSmarty->assign('sourcev', nl2br(htmlentities($version[0]["data"])));
	}
} elseif (isset($_REQUEST["preview"])) {
	if( $version = $gContent->getHistory( $_REQUEST["preview"] ) ) {
		$gBitSmarty->assign_by_ref('parsed', $gContent->parseData( $version[0]["data"], $version[0]["format_guid"] ) );
		$gBitSmarty->assign_by_ref('version', $_REQUEST["preview"]);
	}
} elseif( isset( $_REQUEST["diff2"] ) ) {
	$from_version = $_REQUEST["diff2"];
	$from_page = $gContent->getHistory( $from_version );
	$from_lines = explode("\n",$from_page[0]["data"]);
	$to_version = $gContent->mInfo["version"];
	$to_lines = explode("\n",$gContent->mInfo["data"]);

	include_once( WIKI_PKG_PATH.'diff.php');        
	$diffx = new WikiDiff($from_lines,$to_lines);
	$fmt = new WikiUnifiedDiffFormatter;
	$html = $fmt->format($diffx, $from_lines);
	$gBitSmarty->assign('diffdata', $html);
	$gBitSmarty->assign('diff2', 'y');
	$gBitSmarty->assign('version_from', $from_version);
	$gBitSmarty->assign('version_to', $to_version);

} elseif( isset( $_REQUEST["compare"] ) ) {
	$from_version = $_REQUEST["compare"];
	$from_page = $gContent->getHistory($from_version);
	$gBitSmarty->assign('compare', 'y');
	$gBitSmarty->assign_by_ref('diff_from', $gContent->parseData( $from_page[0]["data"], $from_page[0]["format_guid"] ) );
	$gBitSmarty->assign_by_ref('diff_to', $gContent->parseData() );
	$gBitSmarty->assign_by_ref('version_from', $from_version);
} elseif (isset($_REQUEST["rollback"])) {
	if( $version = $gContent->getHistory( $_REQUEST["preview"] ) ) {
		$gBitSmarty->assign_by_ref('parsed', $gContent->parseData( $version[0]["data"], $version[0]["format_guid"] ) );
		$gBitSmarty->assign_by_ref('version', $_REQUEST["preview"]);
	}
}

// pagination stuff
$gBitSmarty->assign( 'page', $page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1 );
$offset = ( $page - 1 ) * $gBitSystem->getPreference( 'max_records' );
$history = $gContent->getHistory( NULL, NULL, $offset, $gBitSystem->getPreference( 'max_records' ) );
$gBitSmarty->assign_by_ref( 'history', $history );

//vd($gContent->getHistoryCount());

// calculate page number
$numPages = ceil( $gContent->getHistoryCount() / $gBitSystem->getPreference('max_records', 20) );
$gBitSmarty->assign( 'numPages', $numPages );


// Display the template
$gBitSmarty->assign_by_ref( 'gContent', $gContent );
$gBitSystem->display( 'bitpackage:wiki/page_history.tpl');
?>
