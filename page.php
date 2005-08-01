<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page.php,v 1.3 2005/08/01 18:42:04 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page.php,v 1.3 2005/08/01 18:42:04 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once (HTML_PKG_PATH.'htmlpages_lib.php');
if ($feature_html_pages != 'y') {
	$gBitSmarty->assign('msg', tra("This feature is disabled").": feature_html_pages");
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!$gBitUser->hasPermission( 'bit_p_view_html_pages' )) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!isset($_REQUEST["title"])) {
	$gBitSmarty->assign('msg', tra("No page indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
$page_data = $htmlpageslib->get_html_page($_REQUEST["title"]);
$gBitSmarty->assign('type', $page_data["type"]);
$gBitSmarty->assign('refresh', $page_data["refresh"]);
$gBitSmarty->assign('title', $_REQUEST["title"]);
$parsed = $htmlpageslib->parse_html_page($_REQUEST["title"], $page_data["content"]);
$gBitSmarty->assign_by_ref('parsed', $parsed);
$section = 'html_pages';

// Display the template
$gBitSystem->display( 'bitpackage:wiki/page.tpl');
?>