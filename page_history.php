<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/page_history.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'hist_lib.php');
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
if ($feature_history != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_history");
	$gBitSystem->display( 'error.tpl' );
	exit;
}

// Get the page from the request var or default it to HomePage
include( WIKI_PKG_PATH.'lookup_page_inc.php' );
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

//vd($gContent->mPageId);vd($gContent->mInfo);
if( !$gContent->isValid() || empty( $gContent->mInfo ) ) {
	$gBitSystem->fatalError( "Unknown page" );
}

// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$smarty->assign('msg', tra("Permission denied you cannot browse this page history"));
	$gBitSystem->display( 'error.tpl' );
	exit;
}
$smarty->assign('source', 0);
// If we have to include a preview please show it
$smarty->assign('preview', false);
$smarty->assign('diff', false);
$smarty->assign('diff2', 'n');
if (isset($_REQUEST["delete"]) && isset($_REQUEST["hist"])) {
	foreach (array_keys($_REQUEST["hist"])as $version) {
		$gContent->expungeVersion( $version );
	}
} elseif (isset($_REQUEST['source'])) {
	$smarty->assign('source', $_REQUEST['source']);
    if ($_REQUEST['source'] == 'current') {
        $smarty->assign('sourcev', nl2br(htmlentities($gContent->mInfo['data'])));
    } else {
        $version = $gContent->getHistory($_REQUEST["source"]);
        $smarty->assign('sourcev', nl2br(htmlentities($version[0]["data"])));
    }
} elseif (isset($_REQUEST["preview"])) {
	if( $version = $gContent->getHistory( $_REQUEST["preview"] ) ) {
		$smarty->assign_by_ref('parsed', $gContent->parseData( $version[0]["data"], $version[0]["format_guid"] ) );
		$smarty->assign_by_ref('version', $_REQUEST["preview"]);
	}
} elseif( isset( $_REQUEST["diff2"] ) ) {
	$diff = $gContent->getHistory( $_REQUEST["diff2"] );
	$html = $gBitSystem->diff2($diff[0]["data"], $gContent->mInfo["data"]);
	$smarty->assign('diffdata', $html);
	$smarty->assign('diff2', 'y');
	$smarty->assign_by_ref('version', $_REQUEST["diff2"]);
	$smarty->assign_by_ref('parsed', $gContent->parseData( $diff[0]["data"], $diff[0]["format_guid"] ) );
} elseif( isset( $_REQUEST["diff"] ) ) {
	// We are going to change this to "compare" instead of diff
	$diff = $gContent->getHistory( $_REQUEST["diff"]);
	$smarty->assign_by_ref('diff', $gContent->parseData( $diff[0]["data"], $diff[0]["format_guid"] ) );
	$smarty->assign_by_ref('parsed', $gContent->parseData() );
	$smarty->assign_by_ref('version', $_REQUEST["diff"]);
} elseif (isset($_REQUEST["rollback"])) {
	if( $version = $gContent->getHistory( $_REQUEST["preview"] ) ) {
		$smarty->assign_by_ref('parsed', $gContent->parseData( $version[0]["data"], $version[0]["format_guid"] ) );
		$smarty->assign_by_ref('version', $_REQUEST["preview"]);
	}
}

// pagination stuff
$smarty->assign( 'page', $page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1 );
$offset = ( $page - 1 ) * $gBitSystem->mPrefs['maxRecords'];
$history = $gContent->getHistory( NULL, NULL, $offset, $gBitSystem->mPrefs['maxRecords'] );
$smarty->assign_by_ref( 'history', $history );

//vd($gContent->getHistoryCount());

// calculate page number
$numPages = ceil( $gContent->getHistoryCount() / $gBitSystem->mPrefs['maxRecords'] );
$smarty->assign( 'numPages', $numPages );


// Display the template
$smarty->assign_by_ref( 'gContent', $gContent );
$gBitSystem->display( 'bitpackage:wiki/page_history.tpl');
?>
