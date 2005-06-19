<?php
/** $Header: /cvsroot/bitweaver/_bit_wiki/modules/Attic/mod_breadcrumb.php,v 1.1 2005/06/19 06:12:45 bitweaver Exp $
 * \param maxlen = max number of displayed characters for the page name
 */
if (!isset($_SESSION["breadCrumb"])) {
	$_SESSION["breadCrumb"] = array();
}
$bbreadCrumb = array_reverse($_SESSION["breadCrumb"]);
$smarty->assign('breadCrumb', $bbreadCrumb);
$smarty->assign('maxlen', isset($module_params["maxlen"]) ? $module_params["maxlen"] : 0);
?>