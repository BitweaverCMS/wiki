<?php
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
// Initialization

/**
 * required setup
 */
require_once '../../kernel/includes/setup_inc.php';
use Bitweaver\KernelTools;
include_once KERNEL_PKG_INCLUDE_PATH.'admin_lib.php';

if (!$gBitUser->isAdmin()) {
	$gBitSmarty->assign('msg', KernelTools::tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' , null, [ 'display_mode' => 'admin' ] );
	die;
}
if (!isset($_REQUEST["extwiki_id"])) {
	$_REQUEST["extwiki_id"] = 0;
}
$gBitSmarty->assign('extwiki_id', $_REQUEST["extwiki_id"]);
if ($_REQUEST["extwiki_id"]) {
	$info = $adminlib->get_extwiki($_REQUEST["extwiki_id"]);
} else {
	$info = [];
	$info["extwiki"] = '';
	$info['name'] = '';
}
$gBitSmarty->assign('info', $info);
if (isset($_REQUEST["remove"])) {
	
	$adminlib->remove_extwiki($_REQUEST["remove"]);
}
if (isset($_REQUEST["save"])) {
	
	$adminlib->replace_extwiki($_REQUEST["extwiki_id"], $_REQUEST["extwiki"], $_REQUEST['name']);
	$info = [];
	$info["extwiki"] = '';
	$info['name'] = '';
	$gBitSmarty->assign('info', $info);
	$gBitSmarty->assign('name', '');
}

$sort_mode = $_REQUEST["sort_mode"] ?? 'received_date_desc';
$gBitSmarty->assign('sort_mode', $sort_mode);

$offset = $_REQUEST["offset"] ?? 0;
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $max_records;
}
$gBitSmarty->assign('offset', $offset);

$find = $_REQUEST["find"] ?? '';
$gBitSmarty->assign('find', $find);

$channels = $adminlib->list_extwiki($offset, $max_records, $sort_mode, $find);
$cant_pages = ceil($channels["cant"] / $max_records);
$gBitSmarty->assign('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + $offset / $max_records );
if ($channels["cant"] > ($offset + $max_records)) {
	$gBitSmarty->assign('next_offset', $offset + $max_records );
} else {
	$gBitSmarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $max_records );
} else {
	$gBitSmarty->assign('prev_offset', -1);
}
$gBitSmarty->assign('channels', $channels["data"] );

// Display the template
$gBitSystem->display( 'bitpackage:wiki/admin_external_wikis.tpl', null, array( 'display_mode' => 'admin' ));
