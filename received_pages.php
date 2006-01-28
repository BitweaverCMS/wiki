<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/received_pages.php,v 1.1.1.1.2.3 2006/01/28 09:19:48 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: received_pages.php,v 1.1.1.1.2.3 2006/01/28 09:19:48 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once (KERNEL_PKG_PATH.'comm_lib.php');
include_once( WIKI_PKG_PATH.'BitPage.php');

$gBitSystem->verifyFeature( 'feature_comm' );
$gBitSystem->verifyPermission( 'bit_p_admin_received_pages' );

if (!isset($_REQUEST["received_page_id"])) {
	$_REQUEST["received_page_id"] = 0;
}
$gBitSmarty->assign('received_page_id', $_REQUEST["received_page_id"]);
if (isset($_REQUEST["accept"])) {
	
	// CODE TO ACCEPT A PAGE HERE
	$commlib->accept_page($_REQUEST["accept"]);
}
if ($_REQUEST["received_page_id"]) {
	$info = $commlib->get_received_page($_REQUEST["received_page_id"]);
} else {
	$info = array();
	$info["title"] = '';
	$info["data"] = '';
	$info["comment"] = '';
}
$gBitSmarty->assign('view', 'n');
if (isset($_REQUEST["view"])) {
	$info = $commlib->get_received_page($_REQUEST["view"]);
	$gBitSmarty->assign('view', 'y');
}
if (isset($_REQUEST["preview"])) {
	$info["title"] = $_REQUEST["title"];
	$info["data"] = $_REQUEST["data"];
	$info["comment"] = $_REQUEST["comment"];
}
$gBitSmarty->assign('title', $info["title"]);
$gBitSmarty->assign('data', $info["data"]);
$gBitSmarty->assign('comment', $info["comment"]);
// Assign parsed
$gBitSmarty->assign('parsed', $wikilib->parseData($info["data"]));
if (isset($_REQUEST["remove"])) {
	
	$commlib->remove_received_page($_REQUEST["remove"]);
}
if (isset($_REQUEST["save"])) {
	
	$commlib->update_received_page($_REQUEST["received_page_id"], $_REQUEST["title"], $_REQUEST["data"], $_REQUEST["comment"]);
	$gBitSmarty->assign('title', $_REQUEST["title"]);
	$gBitSmarty->assign('data', $_REQUEST["data"]);
	$gBitSmarty->assign('comment', $_REQUEST["comment"]);
	$gBitSmarty->assign('received_page_id', $_REQUEST["received_page_id"]);
	$gBitSmarty->assign('parsed', $gBitSystem->parseData($_REQUEST["data"]));
}
if (empty( $_REQUEST["sort_mode"] )) {
	$sort_mode = 'received_date_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $maxRecords;
}
$gBitSmarty->assign_by_ref('offset', $offset);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$gBitSmarty->assign_by_ref('find', $find);

$channels = $wikilib->list_received_pages($offset, $maxRecords, $sort_mode, $find);
$cant_pages = ceil($channels["cant"] / $maxRecords);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $maxRecords));
if ($channels["cant"] > ($offset + $maxRecords)) {
	$gBitSmarty->assign('next_offset', $offset + $maxRecords);
} else {
	$gBitSmarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}
$gBitSmarty->assign_by_ref('channels', $channels["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/received_pages.tpl');
?>
