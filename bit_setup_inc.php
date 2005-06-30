<?php
	global $gBitSystem, $gBitUser, $smarty;
	$gBitSystem->registerPackage( 'wiki', dirname( __FILE__).'/' );

	define('BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

	if($gBitSystem->isPackageActive( 'wiki' ) ) {
		$gBitSystem->registerAppMenu( 'wiki', 'Wiki', WIKI_PKG_URL.'index.php', 'bitpackage:wiki/menu_wiki.tpl', 'wiki');

		$gBitSystem->registerNotifyEvent( array( "wiki_page_changes" => tra("Any wiki page is changed") ) );

		// Stuff found in kernel that is package dependent - wolff_borg
		include_once( WIKI_PKG_PATH.'diff.php' );

		$wikiHomePage = $gBitSystem->getPreference("wikiHomePage", 'HomePage');
		$anonCanEdit = $gBitSystem->getPreference("anonCanEdit", 'n');

		$smarty->assign('anonCanEdit', $anonCanEdit);
		$smarty->assign('wikiHomePage', $wikiHomePage);
/*
		$smarty->assign('wiki_spellcheck', 'n');
		$smarty->assign('wiki_creator_admin', 'n');
		$smarty->assign('wiki_uses_slides', 'n');
		$smarty->assign('wiki_watch_author', 'n');
		$smarty->assign('wiki_watch_comments', 'y');
		$smarty->assign('wiki_watch_editor', 'n');
		$smarty->assign('wiki_list_name', 'y');
		$smarty->assign('wiki_list_hits', 'y');
		$smarty->assign('wiki_list_lastmodif', 'y');
		$smarty->assign('wiki_list_creator', 'y');
		$smarty->assign('wiki_list_user', 'y');
		$smarty->assign('wiki_list_lastver', 'y');
		$smarty->assign('wiki_list_comment', 'y');
		$smarty->assign('wiki_list_status', 'y');
		$smarty->assign('wiki_list_versions', 'y');
		$smarty->assign('wiki_list_links', 'y');
		$smarty->assign('wiki_list_backlinks', 'y');
		$smarty->assign('wiki_list_size', 'y');
		$smarty->assign('wiki_feature_copyrights', 'n');
		$smarty->assign('wiki_cache', 0);
		$smarty->assign('w_use_db', 'y');
		$smarty->assign('w_use_dir', '');
		$smarty->assign('keep_versions', 1);
		$smarty->assign('userbreadCrumb', 4);
		$smarty->assign('warn_on_edit_time', 2);
		$smarty->assign('wiki_extras', 'n');
		$smarty->assign('lock', false);
		$smarty->assign('dblclickedit', 'n');
*/
	}

?>
