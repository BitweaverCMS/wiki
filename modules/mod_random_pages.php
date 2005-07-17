<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.php,v 1.3 2005/07/17 17:36:46 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_random_pages.php,v 1.3 2005/07/17 17:36:46 squareing Exp $
 * @package wiki
 * @subpackage modules
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
global $wikilib;

$ranking = $wikilib->get_random_pages($module_rows);
$smarty->assign('modRandomPages', $ranking);
?>
