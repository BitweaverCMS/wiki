<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_last_modif_pages.php,v 1.1.1.1.2.1 2005/06/27 17:48:06 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_last_modif_pages.php,v 1.1.1.1.2.1 2005/06/27 17:48:06 lsces Exp $
 * @package wiki
 * @subpackage modules
 */
global $gQueryUserId, $module_rows, $module_params, $wikilib;

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
$ranking = $wikilib->getList(0, $module_rows, 'last_modified_desc', NULL, $gQueryUserId );

$smarty->assign('modLastModif', $ranking["data"]);
$smarty->assign('maxlen', isset($module_params["maxlen"]) ? $module_params["maxlen"] : 0);
?>
