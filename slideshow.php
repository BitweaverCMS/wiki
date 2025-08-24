<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\HttpStatusCodes;
use Bitweaver\KernelTools;
use Bitweaver\Liberty\LibertyContent;

$gBitSystem->verifyPackage( 'wiki' );
//print($GLOBALS["HTTP_REFERER"]);

$thedate = $_SESSION["thedate"] ?? $gBitSystem->getUTCTime();

require_once WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php';
// If the page doesn't exist then display an error
if (!$gContent->isValid()) {
	$gBitSystem->fatalError( KernelTools::tra("Page cannot be found"), null, null, HttpStatusCodes::HTTP_NOT_FOUND );
}

// Now check permissions to access this page
$gContent->verifyViewPermission();

// Get page data
include WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php';
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
$slides = mb_split("-=[^=]+=-", $info["data"]);

if (count($slides) < 2) {
	$slides = explode(defined('PAGE_SEP') ? PAGE_SEP : "...page...", $info["data"]);

	array_unshift($slides, '');
}

if (!isset($_REQUEST["slide"])) {
	$_REQUEST["slide"] = 0;
}

$gBitSmarty->assign('prev_slide', $_REQUEST["slide"] - 1);
$gBitSmarty->assign('next_slide', $_REQUEST["slide"] + 1);

$slide_title = $reqs[1][$_REQUEST["slide"]] ?? '';

$slide_data = LibertyContent::parseDataHash( $slides[$_REQUEST["slide"] + 1] );
$slide_prev_title = $reqs[1][$_REQUEST["slide"] - 1] ?? 'prev';
$slide_next_title = $reqs[1][$_REQUEST["slide"] + 1] ?? 'next';

$gBitSmarty->assign('slide_prev_title', $slide_prev_title);
$gBitSmarty->assign('slide_next_title', $slide_next_title);

$gBitSmarty->assign('slide_title', $slide_title);
$gBitSmarty->assign('slide_data', $slide_data);

$total_slides = count($slides) - 1;
$current_slide = $_REQUEST["slide"] + 1;
$gBitSmarty->assign('total_slides', $total_slides);
$gBitSmarty->assign('current_slide', $current_slide);

//$gBitSmarty->assign('last_modified',date("l d of F, Y  [H:i:s]",$info["last_modified"]));
$gBitSmarty->assign('last_modified', $info["last_modified"]);

if (empty($info["user"])) {
	$info["user"] = 'anonymous';
}

$gBitSmarty->assign('lastUser', $info["user"]);
$gBitSmarty->display("bitpackage:wiki/slideshow.tpl");
