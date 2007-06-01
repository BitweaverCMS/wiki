<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_comm_received_objects.php,v 1.4 2007/06/01 16:01:33 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_comm_received_objects.php,v 1.4 2007/06/01 16:01:33 squareing Exp $
 * @package wiki
 * @subpackage modules
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
$wikilib = new WikiLib();
$ranking = $wikilib->list_received_pages(0, -1, $sort_mode = 'title_asc', '');
$gBitSmarty->assign('modReceivedPages', $ranking["cant"]);
?>
