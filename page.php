<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page.php,v 1.4 2006/01/27 21:57:53 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page.php,v 1.4 2006/01/27 21:57:53 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once (HTML_PKG_PATH.'htmlpages_lib.php');

$gBitSystem->verifyFeature( 'feature_html_pages' );
$gBitSystem->verifyPermission( 'bit_p_view_html_pages' );

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
