<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_wiki_last_comments.php,v 1.1.1.1.2.2 2005/07/26 15:50:48 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_wiki_last_comments.php,v 1.1.1.1.2.2 2005/07/26 15:50:48 drewslater Exp $
 * @package wiki
 * @subpackage modules
 */

/**
 * Show last comments on wiki pages
 */
global $gQueryUserId;
/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyComment.php' );
$cmt = new LibertyComment();
$lastComments = $cmt->getList( array( 'max_records' => $module_rows, 'user_id' => $gQueryUserId, 'content_type_guid' => BITPAGE_CONTENT_TYPE_GUID ) );
$gBitSmarty->assign('lastComments', $lastComments);
$gBitSmarty->assign('moretooltips', isset($module_params["moretooltips"]) ? $module_params["moretooltips"] : 'n');
?>
