<?php
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
// Initialization
require_once( '../../kernel/setup_inc.php' );
include_once( KERNEL_PKG_PATH.'admin_lib.php' );
if (!$gBitUser->isAdmin()) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'admin' ));
	die;
}
if (!isset($_REQUEST["extwiki_id"])) {
	$_REQUEST["extwiki_id"] = 0;
}
$gBitSmarty->assign('extwiki_id', $_REQUEST["extwiki_id"]);
if ($_REQUEST["extwiki_id"]) {
	$info = $adminlib->get_extwiki($_REQUEST["extwiki_id"]);
} else {
	$info = array();
	$info["extwiki"] = '';
	$info['name'] = '';
}
$gBitSmarty->assign('info', $info);
if (isset($_REQUEST["remove"])) {
	
	$adminlib->remove_extwiki($_REQUEST["remove"]);
}
if (isset($_REQUEST["save"])) {
	
	$adminlib->replace_extwiki($_REQUEST["extwiki_id"], $_REQUEST["extwiki"], $_REQUEST['name']);
	$info = array();
	$info["extwiki"] = '';
	$info['name'] = '';
	$gBitSmarty->assign('info', $info);
	$gBitSmarty->assign('name', '');
}
if (empty( $_REQUEST["sort_mode"] )) {
	$sort_mode = 'received_date_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$gBitSmarty->assignByRef('sort_mode', $sort_mode);
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $max_records;
}
$gBitSmarty->assignByRef('offset', $offset);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$gBitSmarty->assignByRef('find', $find);

$channels = $adminlib->list_extwiki($offset, $max_records, $sort_mode, $find);
$cant_pages = ceil($channels["cant"] / $max_records);
$gBitSmarty->assignByRef('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $max_records));
if ($channels["cant"] > ($offset + $max_records)) {
	$gBitSmarty->assign('next_offset', $offset + $max_records);
} else {
	$gBitSmarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $max_records);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}
$gBitSmarty->assignByRef('channels', $channels["data"]);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/admin_external_wikis.tpl', NULL, array( 'display_mode' => 'admin' ));
?>
