<?php
require_once( WIKI_PKG_PATH.'BitPage.php' );
global $wikilib;
$ranking = $wikilib->list_received_pages(0, -1, $sort_mode = 'title_asc', '');
$smarty->assign('modReceivedPages', $ranking["cant"]);
?>
