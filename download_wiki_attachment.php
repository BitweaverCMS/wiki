<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/download_wiki_attachment.php,v 1.4 2006/03/01 20:16:36 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: download_wiki_attachment.php,v 1.4 2006/03/01 20:16:36 spiderr Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
if (!$gBitUser->hasPermission( 'bit_p_wiki_view_attachments' ) && !$gBitUser->hasPermission( 'bit_p_wiki_admin_attachments' )) {
	die;
}
if (!isset($_REQUEST["att_id"])) {
	die;
}
$info = $gBitSystem->get_wiki_attachment($_REQUEST["att_id"]);
$w_use_dir = $gBitSystem->getConfig('w_use_dir', '');
$gBitSystem->add_wiki_attachment_hit($_REQUEST["att_id"]);
$type = &$info["filetype"];
$file = &$info["filename"];
$content = &$info["data"];
//print("File:$file<br/>");
//die;
header ("Content-type: $type");
//header( "Content-Disposition: attachment; filename=$file" );
header ("Content-Disposition: inline; filename=\"$file\"");
if ($info["path"]) {
	readfile ($w_use_dir . $info["path"]);
} else {
	echo "$content";
}
?>
