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
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\Wiki\BitPage;
use Bitweaver\KernelTools;

if( !$gBitSystem->isFeatureActive( 'wiki_multiprint' ) ) {
	$gBitSystem->fatalError( KernelTools::tra("This feature is disabled").": wiki_multiprint" );
}
// Now check permissions if user can view wiki pages
$gContent->verifyViewPermission();

$printpages = isset($_REQUEST["printpages"]) ? unserialize(urldecode($_REQUEST["printpages"])) : [];

$find = $_REQUEST["find"] ?? '';

$gBitSmarty->assign('find', $find);
if (isset($_REQUEST["addpage"])) {
	if (!in_array($_REQUEST["title"], $printpages)) {
		$printpages[] = $_REQUEST["title"];
	}
}
if (isset($_REQUEST["clearpages"])) {
	$printpages = [];
}
$gBitSmarty->assign('printpages', $printpages);
$form_printpages = urlencode(serialize($printpages));
$gBitSmarty->assign('form_printpages', $form_printpages);
$listHash = [
	'max_records' => -1,
	'sort_mode' => 'title_asc',
	'find' => $find,
];
$pages = $gContent->getList( $listHash );
$gBitSmarty->assign('pages', $pages);

// Display the template
$gBitSystem->display( 'bitpackage:wiki/print_pages.tpl', null, array( 'display_mode' => 'display' ));
