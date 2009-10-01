<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/slideshow.php,v 1.22 2009/10/01 13:45:54 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: slideshow.php,v 1.22 2009/10/01 13:45:54 wjames5 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( 'BitPage.php' );

$gBitSystem->verifyPackage( 'wiki' );
//print($GLOBALS["HTTP_REFERER"]);

if (!isset($_SESSION["thedate"])) {
	$thedate = $gBitSystem->getUTCTime();
} else {
	$thedate = $_SESSION["thedate"];
}

require_once ( WIKI_PKG_PATH.'lookup_page_inc.php' );
// If the page doesn't exist then display an error
if (!$gContent->isValid()) {
	$gBitSystem->setHttpStatus( 404 );
	$gBitSystem->fatalError(tra("Page cannot be found"));
}

// Now check permissions to access this page
$gContent->verifyViewPermission();

// Get page data
include( WIKI_PKG_PATH.'lookup_page_inc.php' );
$info = $gContent->mInfo;

// If not locked and last version is user version then can undo
$gBitSmarty->assign('canundo', 'n');

if ($info["flag"] != 'L' && (($gContent->hasUpdatePermission() && $info["user"] == $user) || ($gContent->hasUserPermission( 'p_wiki_remove_page' )))) {
	$gBitSmarty->assign('canundo', 'y');
}

if( $gContent->hasAdminPermission() ) {
	$gBitSmarty->assign('canundo', 'y');
}

//Now process the pages
preg_match_all("/-=([^=]+)=-/", $info["data"], $reqs);
$slides = split("-=[^=]+=-", $info["data"]);

if (count($slides) < 2) {
	$slides = explode(defined('PAGE_SEP') ? PAGE_SEP : "...page...", $info["data"]);

	array_unshift($slides, '');
}

if (!isset($_REQUEST["slide"])) {
	$_REQUEST["slide"] = 0;
}

$gBitSmarty->assign('prev_slide', $_REQUEST["slide"] - 1);
$gBitSmarty->assign('next_slide', $_REQUEST["slide"] + 1);

if (isset($reqs[1][$_REQUEST["slide"]])) {
	$slide_title = $reqs[1][$_REQUEST["slide"]];
} else {
	$slide_title = '';
}

$slide_data = $gContent->parseData( $slides[$_REQUEST["slide"] + 1] );

if (isset($reqs[1][$_REQUEST["slide"] - 1])) {
	$slide_prev_title = $reqs[1][$_REQUEST["slide"] - 1];
} else {
	$slide_prev_title = 'prev';
}

if (isset($reqs[1][$_REQUEST["slide"] + 1])) {
	$slide_next_title = $reqs[1][$_REQUEST["slide"] + 1];
} else {
	$slide_next_title = 'next';
}

$gBitSmarty->assign('slide_prev_title', $slide_prev_title);
$gBitSmarty->assign('slide_next_title', $slide_next_title);

$gBitSmarty->assign('slide_title', $slide_title);
$gBitSmarty->assign('slide_data', $slide_data);

$total_slides = count($slides) - 1;
$current_slide = $_REQUEST["slide"] + 1;
$gBitSmarty->assign('total_slides', $total_slides);
$gBitSmarty->assign('current_slide', $current_slide);

//$gBitSmarty->assign_by_ref('last_modified',date("l d of F, Y  [H:i:s]",$info["last_modified"]));
$gBitSmarty->assign_by_ref('last_modified', $info["last_modified"]);

if (empty($info["user"])) {
	$info["user"] = 'anonymous';
}

$gBitSmarty->assign_by_ref('lastUser', $info["user"]);
$gBitSmarty->display("bitpackage:wiki/slideshow.tpl");
?>
