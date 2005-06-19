<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/export_wiki_pages.php,v 1.1 2005/06/19 06:12:44 bitweaver Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );
include_once( UTIL_PKG_PATH.'zip_lib.php' );
include_once( WIKI_PKG_PATH.'export_lib.php' );
if (!$gBitUser->hasPermission( 'bit_p_admin_wiki' ))
	die;
if (!isset($_REQUEST["page_id"])) {
	$exportName = 'export_'.date( 'Y-m-d_H:i' ).'.tar';
	$exportlib->MakeWikiZip( TEMP_PKG_PATH.$exportName );
	header ("location: ".TEMP_PKG_URL.$exportName );
} else {
	if (isset($_REQUEST["all"]))
		$all = 0;
	else
		$all = 1;
	$data = $exportlib->export_wiki_page($_REQUEST["page_id"], $all);
	$pageId = $_REQUEST["page_id"];
	header ("Content-type: application/unknown");
	header ("Content-Disposition: inline; filename=$pageId");
	echo $data;
}
?>
