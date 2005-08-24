<?php
	global $gBitSystem, $gBitUser, $gBitSmarty;
	$gBitSystem->registerPackage( 'wiki', dirname( __FILE__).'/' );

	define('BITPAGE_CONTENT_TYPE_GUID', 'bitpage' );

	if($gBitSystem->isPackageActive( 'wiki' ) ) {
		if ($gBitUser->hasPermission( 'bit_p_view' )) {
			$gBitSystem->registerAppMenu( 'wiki', 'Wiki', WIKI_PKG_URL.'index.php', 'bitpackage:wiki/menu_wiki.tpl', 'wiki');
		}

		$gBitSystem->registerNotifyEvent( array( "wiki_page_changes" => tra("Any wiki page is changed") ) );

		// Stuff found in kernel that is package dependent - wolff_borg
		include_once( WIKI_PKG_PATH.'diff.php' );

		$wikiHomePage = $gBitSystem->getPreference("wikiHomePage", 'HomePage');
		$anonCanEdit = $gBitSystem->getPreference("anonCanEdit", 'n');

		$gBitSmarty->assign('anonCanEdit', $anonCanEdit);
		$gBitSmarty->assign('wikiHomePage', $wikiHomePage);
/*
		$gBitSmarty->assign('wiki_spellcheck', 'n');
		$gBitSmarty->assign('wiki_creator_admin', 'n');
		$gBitSmarty->assign('wiki_uses_slides', 'n');
		$gBitSmarty->assign('wiki_watch_author', 'n');
		$gBitSmarty->assign('wiki_watch_comments', 'y');
		$gBitSmarty->assign('wiki_watch_editor', 'n');
		$gBitSmarty->assign('wiki_list_name', 'y');
		$gBitSmarty->assign('wiki_list_hits', 'y');
		$gBitSmarty->assign('wiki_list_lastmodif', 'y');
		$gBitSmarty->assign('wiki_list_creator', 'y');
		$gBitSmarty->assign('wiki_list_user', 'y');
		$gBitSmarty->assign('wiki_list_lastver', 'y');
		$gBitSmarty->assign('wiki_list_comment', 'y');
		$gBitSmarty->assign('wiki_list_status', 'y');
		$gBitSmarty->assign('wiki_list_versions', 'y');
		$gBitSmarty->assign('wiki_list_links', 'y');
		$gBitSmarty->assign('wiki_list_backlinks', 'y');
		$gBitSmarty->assign('wiki_list_size', 'y');
		$gBitSmarty->assign('wiki_feature_copyrights', 'n');
		$gBitSmarty->assign('wiki_cache', 0);
		$gBitSmarty->assign('w_use_db', 'y');
		$gBitSmarty->assign('w_use_dir', '');
		$gBitSmarty->assign('keep_versions', 1);
		$gBitSmarty->assign('userbreadCrumb', 4);
		$gBitSmarty->assign('warn_on_edit_time', 2);
		$gBitSmarty->assign('wiki_extras', 'n');
		$gBitSmarty->assign('lock', false);
		$gBitSmarty->assign('dblclickedit', 'n');
*/
	}

?>
