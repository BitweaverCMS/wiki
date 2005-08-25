<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/slideshow.php,v 1.1.1.1.2.4 2005/08/25 17:04:06 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: slideshow.php,v 1.1.1.1.2.4 2005/08/25 17:04:06 lsces Exp $
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

// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$_REQUEST["page"] = $wikiHomePage;

	$page = $wikiHomePage;
	$gBitSmarty->assign('page', $wikiHomePage);
} else {
	$page = $_REQUEST["page"];

	$gBitSmarty->assign_by_ref('page', $_REQUEST["page"]);
}

require_once ( WIKI_PKG_PATH.'page_setup_inc.php' );

// Check if we have to perform an action for this page
// for example lock/unlock
if ($gBitUser->hasPermission( 'bit_p_admin_wiki' )) {
	if (isset($_REQUEST["action"])) {
		if ($_REQUEST["action"] == 'lock') {
			$wikilib->lock_page($page);
		} elseif ($_REQUEST["action"] == 'unlock') {
			$wikilib->unlock_page($page);
		}
	}
}

// If the page doesn't exist then display an error
if (!$wikilib->pageExists($page)) {
	$gBitSmarty->assign('msg', tra("Page cannot be found"));

	$gBitSystem->display( 'error.tpl' );
	die;
}

// Now check permissions to access this page
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$gBitSmarty->assign('msg', tra("Permission denied you cannot view this page"));

	$gBitSystem->display( 'error.tpl' );
	die;
}

// BreadCrumbNavigation here
// Get the number of pages from the default or userPreferences
// Remember to reverse the array when posting the array
$anonpref = $wikilib->getPreference('userbreadCrumb', 4);

if( $gBitUser->isRegistered() ) {
	$userbreadCrumb = $wikilib->getPreference('userbreadCrumb', $anonpref, $user );
} else {
	$userbreadCrumb = $anonpref;
}

if (!isset($_SESSION["breadCrumb"])) {
	$_SESSION["breadCrumb"] = array();
}

if (!in_array($page, $_SESSION["breadCrumb"])) {
	if (count($_SESSION["breadCrumb"]) > $userbreadCrumb) {
		array_shift ($_SESSION["breadCrumb"]);
	}

	array_push($_SESSION["breadCrumb"], $page);
} else {
	// If the page is in the array move to the last position
	$pos = array_search($page, $_SESSION["breadCrumb"]);

	unset ($_SESSION["breadCrumb"][$pos]);
	array_push($_SESSION["breadCrumb"], $page);
}

//print_r($_SESSION["breadCrumb"]);

// Now increment page hits since we are visiting this page
//if ($count_admin_pvs == 'y' || !$gBitUser->isAdmin()) {
//	$wikilib->add_hit($page);
//}

// Get page data
include( WIKI_PKG_PATH.'lookup_page_inc.php' );
$info = $gContent->mInfo;

// Verify lock status
if ($info["flag"] == 'L') {
	$gBitSmarty->assign('lock', true);
} else {
	$gBitSmarty->assign('lock', false);
}

// If not locked and last version is user version then can undo
$gBitSmarty->assign('canundo', 'n');

if ($info["flag"] != 'L' && (($gBitUser->hasPermission( 'bit_p_edit' ) && $info["user"] == $user) || ($gBitUser->hasPermission( 'bit_p_remove' )))) {
	$gBitSmarty->assign('canundo', 'y');
}

if ($gBitUser->hasPermission( 'bit_p_admin_wiki' )) {
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

$section = 'wiki';

$gBitSmarty->assign('wiki_extras', 'y');



// Display the Index Template
$gBitSmarty->assign('show_page_bar', 'y');
$gBitSmarty->display("bitpackage:wiki/slideshow.tpl");

?>
