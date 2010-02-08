<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/print.php,v 1.24 2010/02/08 21:27:27 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: print.php,v 1.24 2010/02/08 21:27:27 wjames5 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );
$gContent->verifyViewPermission();

// If the page doesn't exist then display an error
$requirePage = TRUE;
include( WIKI_PKG_PATH.'lookup_page_inc.php' );

// Check if we have to perform an action for this page
// for example lock/unlock
if (isset($_REQUEST["action"])) {
	if ($_REQUEST["action"] == 'lock') {
		$gContent->lock();
	} elseif ($_REQUEST["action"] == 'unlock') {
		$gContent->unlock_page();
	}
}

// Now increment page hits since we are visiting this page
if ($gBitSystem->isFeatureActive( 'users_count_admin_pageviews' ) || !$gBitUser->isAdmin()) {
	$gContent->addHit();
}
// Get page data
$info = $gContent->mInfo;
if ($gBitSystem->isFeatureActive( 'wiki_copyrights' ) && $gBitSystem->isFeatureActive( 'wiki_copyrights' ) && $gBitSystem->isFeatureActive( 'wiki_license_page' )) {
	// insert license if wiki copyrights enabled
//	$license_info = $wikilib->get_page_info($wiki_license_page);
//	$wikilib->add_hit($wiki_license_page);
	$info["data"] = $info["data"] . "\n<HR>\n" . $license_info["data"];
	$_REQUEST['copyrightpage'] = $page;
}
// Verify lock status
if ($info["flag"] == 'L') {
	$gBitSmarty->assign('lock', true);
} else {
	$gBitSmarty->assign('lock', false);
}
$gBitSmarty->assign_by_ref('last_modified', $info["last_modified"]);
if (empty($info["user"])) {
	$info["user"] = 'anonymous';
}
$gBitSmarty->assign_by_ref('lastUser', $info["user"]);
//Store the page URL to be displayed on print page
$site_http_domain = $gContent->getPreference('site_http_domain', false);
$site_http_port = $gContent->getPreference('site_http_port', 80);
$site_http_prefix = $gContent->getPreference('site_http_prefix', '/');
if ($site_http_domain) {
	$prefix = 'http://' . $site_http_domain;
	if ($site_http_port != 80)
		$prefix .= ':' . $site_http_port;
	$prefix .= $gBitSystem->getConfig( 'site_https_prefix' );
	$gBitSmarty->assign('urlprefix', $prefix);
}

// Display the Index Template
$gBitSmarty->assign('print_page','y');
$gBitSmarty->assign('show_page_bar', 'n');
$gBitSmarty->display("bitpackage:wiki/print.tpl");
?>
