<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/print.php,v 1.1.1.1.2.3 2005/08/25 20:17:06 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: print.php,v 1.1.1.1.2.3 2005/08/25 20:17:06 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyPackage( 'wiki' );

// If the page doesn't exist then display an error
$requirePage = TRUE;
include( WIKI_PKG_PATH.'lookup_page_inc.php' );

// Now check permissions to access this page
if (!$gContent->hasUserPermission( 'bit_p_view' )) {
	$gBitSmarty->assign('msg', tra("Permission denied you cannot view this page"));
	$gBitSystem->display( 'error.tpl' );
	die;
}

require_once ( WIKI_PKG_PATH.'page_setup_inc.php' );
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
if ($count_admin_pvs == 'y' || $bit_p_admin!='y') {
  $gContent->addHit();
}
// Get page data
$info = $gContent->mInfo;
if (isset($wiki_feature_copyrights) && $wiki_feature_copyrights == 'y' && isset($wikiLicensePage)) {
	// insert license if wiki copyrights enabled
//	$license_info = $wikilib->get_page_info($wikiLicensePage);
//	$wikilib->add_hit($wikiLicensePage);
	$info["data"] = $info["data"] . "\n<HR>\n" . $license_info["data"];
	$_REQUEST['copyrightpage'] = $page;
}
// Verify lock status
if ($info["flag"] == 'L') {
	$gBitSmarty->assign('lock', true);
} else {
	$gBitSmarty->assign('lock', false);
}
$gBitSmarty->assign('cached_page','n');
if(isset($gContent->mInfo['wiki_cache']) && $gContent->mInfo['wiki_cache']>0) {$wiki_cache=$gContent->mInfo['wiki_cache'];}
if($wiki_cache>0) {
 $cache_info = $wikilib->get_cache_info($page);
 $now = $gBitSystem->getUTCTime();
 if($cache_info['cache_timestamp']+$wiki_cache > $now) {
   $pdata = $cache_info['cache'];
   $gBitSmarty->assign('cached_page','y');
 } else {
   $pdata = $gContent->parseData();
   $wikilib->update_cache($page,$pdata);
 }
} else {
 $pdata = $gContent->parseData();
}
$gBitSmarty->assign_by_ref('parsed', $pdata);
$gBitSmarty->assign_by_ref('last_modified', $info["last_modified"]);
if (empty($info["user"])) {
	$info["user"] = 'anonymous';
}
$gBitSmarty->assign_by_ref('lastUser', $info["user"]);
//Store the page URL to be displayed on print page
$http_domain = $wikilib->getPreference('http_domain', false);
$http_port = $wikilib->getPreference('http_port', 80);
$http_prefix = $wikilib->getPreference('http_prefix', '/');
if ($http_domain) {
	$prefix = 'http://' . $http_domain;
	if ($http_port != 80)
		$prefix .= ':' . $http_port;
	$prefix .= $https_prefix;
	$gBitSmarty->assign('urlprefix', $prefix);
}

// Display the Index Template
$gBitSmarty->assign('print_page','y');
$gBitSystem->display( 'bitpackage:wiki/show_page.tpl');
$gBitSmarty->assign('show_page_bar', 'n');
$gBitSmarty->assign('print_page', 'y');
$gBitSmarty->display("bitpackage:wiki/print.tpl");
?>
