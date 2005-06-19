<?php
//
// $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_wiki_last_comments.php,v 1.1 2005/06/19 06:12:45 bitweaver Exp $
// \brief Show last comments on wiki pages
//
global $gQueryUserId;
require_once( LIBERTY_PKG_PATH.'LibertyComment.php' );
$cmt = new LibertyComment();
$lastComments = $cmt->getList( array( 'max_records' => $module_rows, 'user_id' => $gQueryUserId, 'content_type_guid' => BITPAGE_CONTENT_TYPE_GUID ) );
$smarty->assign('lastComments', $lastComments);
$smarty->assign('moretooltips', isset($module_params["moretooltips"]) ? $module_params["moretooltips"] : 'n');
?>
