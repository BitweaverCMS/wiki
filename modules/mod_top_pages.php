<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.php,v 1.1.1.1.2.5 2005/09/11 08:43:32 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_top_pages.php,v 1.1.1.1.2.5 2005/09/11 08:43:32 squareing Exp $
 * @package wiki
 * @subpackage modules
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
global $gQueryUser, $module_rows, $module_params;

$modWiki = new BitPage();
$modRank = $modWiki->getList( 0, !empty( $module_rows ) ? $module_rows : 10, 'hits_desc', !empty( $module_params['user_pages'] ) ? $gQueryUser->mUserId : NULL );
$gBitSmarty->assign( 'modTopPages', $modRank["data"] );
?>
