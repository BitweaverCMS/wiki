<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/Attic/received_pages.php,v 1.1 2005/06/19 06:12:45 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once (KERNEL_PKG_PATH.'comm_lib.php');
include_once( WIKI_PKG_PATH.'BitPage.php');
if ($feature_comm != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_comm");
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!$gBitUser->hasPermission( 'bit_p_admin_received_pages' )) {
	$smarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!isset($_REQUEST["received_page_id"])) {
	$_REQUEST["received_page_id"] = 0;
}
$smarty->assign('received_page_id', $_REQUEST["received_page_id"]);
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
$smarty->assign('view', 'n');
if (isset($_REQUEST["view"])) {
	$info = $commlib->get_received_page($_REQUEST["view"]);
	$smarty->assign('view', 'y');
}
if (isset($_REQUEST["preview"])) {
	$info["title"] = $_REQUEST["title"];
	$info["data"] = $_REQUEST["data"];
	$info["comment"] = $_REQUEST["comment"];
}
$smarty->assign('title', $info["title"]);
$smarty->assign('data', $info["data"]);
$smarty->assign('comment', $info["comment"]);
// Assign parsed
$smarty->assign('parsed', $wikilib->parseData($info["data"]));
if (isset($_REQUEST["remove"])) {
	
	$commlib->remove_received_page($_REQUEST["remove"]);
}
if (isset($_REQUEST["save"])) {
	
	$commlib->update_received_page($_REQUEST["received_page_id"], $_REQUEST["title"], $_REQUEST["data"], $_REQUEST["comment"]);
	$smarty->assign('title', $_REQUEST["title"]);
	$smarty->assign('data', $_REQUEST["data"]);
	$smarty->assign('comment', $_REQUEST["comment"]);
	$smarty->assign('received_page_id', $_REQUEST["received_page_id"]);
	$smarty->assign('parsed', $gBitSystem->parseData($_REQUEST["data"]));
}
if (empty( $_REQUEST["sort_mode"] )) {
	$sort_mode = 'received_date_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$smarty->assign_by_ref('sort_mode', $sort_mode);
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $maxRecords;
}
$smarty->assign_by_ref('offset', $offset);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign_by_ref('find', $find);

$channels = $wikilib->list_received_pages($offset, $maxRecords, $sort_mode, $find);
$cant_pages = ceil($channels["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $maxRecords));
if ($channels["cant"] > ($offset + $maxRecords)) {
	$smarty->assign('next_offset', $offset + $maxRecords);
} else {
	$smarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$smarty->assign('prev_offset', -1);
}
$smarty->assign_by_ref('channels', $channels["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/received_pages.tpl');
?>
