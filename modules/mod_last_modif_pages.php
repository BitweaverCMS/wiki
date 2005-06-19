<?php
global $gQueryUserId, $module_rows, $module_params, $wikilib;

require_once( WIKI_PKG_PATH.'BitPage.php' );
$ranking = $wikilib->getList(0, $module_rows, 'last_modified_desc', NULL, $gQueryUserId );

$smarty->assign('modLastModif', $ranking["data"]);
$smarty->assign('maxlen', isset($module_params["maxlen"]) ? $module_params["maxlen"] : 0);
?>
