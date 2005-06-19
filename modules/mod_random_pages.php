<?php
global $wikilib;
$ranking = $wikilib->get_random_pages($module_rows);
$smarty->assign('modRandomPages', $ranking);
?>