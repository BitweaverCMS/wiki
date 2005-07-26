<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/rankings.php,v 1.1.1.1.2.3 2005/07/26 15:50:47 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: rankings.php,v 1.1.1.1.2.3 2005/07/26 15:50:47 drewslater Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( KERNEL_PKG_PATH.'rank_lib.php' );

$gBitSystem->verifyPackage( 'wiki' );
if ($feature_wiki_rankings != 'y') {
	$gBitSmarty->assign('msg', tra("This feature is disabled").": feature_wiki_rankings");
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!$gBitUser->hasPermission( 'bit_p_view' )) {
	$gBitSmarty->assign('msg', tra("Permission denied you cannot view this section"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["limit"])) {
	$limit = 10;
} else {
	$limit = $_REQUEST["limit"];
}
$allrankings = array(
	array(
	'name' => tra('Most often viewed pages'),
	'value' => 'wiki_ranking_top_pages'
),
	array(
	'name' => tra('Most recently modified pages'),
	'value' => 'wiki_ranking_last_pages'
),
	array(
	'name' => tra('Most relevant pages'),
	'value' => 'wiki_ranking_top_pagerank'
),
	array(
	'name' => tra('Most active authors'),
	'value' => 'wiki_ranking_top_authors'
)
);
$gBitSmarty->assign('allrankings', $allrankings);
if (!isset($_REQUEST["which"])) {
	$which = 'wiki_ranking_top_pages';
} else {
	$which = $_REQUEST["which"];
}
$gBitSmarty->assign('which', $which);
$gBitSmarty->assign_by_ref('limit', $limit);
// Rankings:
// Top Pages
// Last pages
// Top Authors
$rankings = array();
$rk = $ranklib->$which($limit);
$rank["data"] = $rk["data"];
$rank["title"] = $rk["title"];
$rank["y"] = $rk["y"];
$rankings[] = $rank;
$gBitSmarty->assign_by_ref('rankings', $rankings);
$gBitSmarty->assign('rpage', WIKI_PKG_URL.'rankings.php');
// Display the template
$gBitSystem->display( 'bitpackage:kernel/ranking.tpl');
?>
