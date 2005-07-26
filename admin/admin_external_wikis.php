<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/admin/admin_external_wikis.php,v 1.1.1.1.2.1 2005/07/26 15:50:48 drewslater Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../../bit_setup_inc.php' );
include_once( KERNEL_PKG_PATH.'admin_lib.php' );
if (!$gBitUser->isAdmin()) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' );
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

$channels = $adminlib->list_extwiki($offset, $maxRecords, $sort_mode, $find);
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
$gBitSystem->display( 'bitpackage:wiki/admin_external_wikis.tpl');
?>