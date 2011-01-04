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
require_once( '../kernel/setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitBook.php');

$book = new BitBook();

$listHash = array();
$listHash['content_type_guid'] = BITBOOK_CONTENT_TYPE_GUID;
$channels = $book->getList( $listHash );

$cant_pages = ceil($channels["cant"] / $listHash['max_records']);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($listHash['offset'] / $listHash['max_records']));

if ($channels["cant"] > ($listHash['offset'] + $listHash['max_records'])) {
	$gBitSmarty->assign('next_offset', $listHash['offset'] + $listHash['max_records']);
} else {
	$gBitSmarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($listHash['offset'] > 0) {
	$gBitSmarty->assign('prev_offset', $listHash['offset'] - $listHash['max_records']);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

$gBitSmarty->assign_by_ref('channels', $channels["data"]);

$gBitSystem->display( 'bitpackage:wiki/list_books.tpl', NULL, array( 'display_mode' => 'display' ));

?>
