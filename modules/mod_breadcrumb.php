<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/modules/Attic/mod_breadcrumb.php,v 1.2 2005/06/28 07:46:28 spiderr Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_breadcrumb.php,v 1.2 2005/06/28 07:46:28 spiderr Exp $
 * @package wiki
 * @subpackage modules
 */

/**
 * @param maxlen = max number of displayed characters for the page name
 */
if (!isset($_SESSION["breadCrumb"])) {
	$_SESSION["breadCrumb"] = array();
}
$bbreadCrumb = array_reverse($_SESSION["breadCrumb"]);
$smarty->assign('breadCrumb', $bbreadCrumb);
$smarty->assign('maxlen', isset($module_params["maxlen"]) ? $module_params["maxlen"] : 0);
?>