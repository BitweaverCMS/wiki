<?php
// $Header: /cvsroot/bitweaver/_bit_wiki/admin/admin_wiki_inc.php,v 1.1.1.1.2.9 2005/09/18 16:36:52 spiderr Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// FIXME: including wikilib is NOT the way to fix this.
require_once( WIKI_PKG_PATH.'BitPage.php' );

$formWikiLists = array(
	"wiki_list_name" => array(
		'label' => 'Name',
		'note' => 'Name of the wikipage.',
	),
	"wiki_list_hits" => array(
		'label' => 'Hits',
		'note' => 'How often the page has been viewed.',
	),
	"wiki_list_lastmodif" => array(
		'label' => 'Last modification date',
		'note' => 'Date of the last modification of a page.',
	),
	"wiki_list_creator" => array(
		'label' => 'Creator',
		'note' => 'Name of the creator of a page.',
	),
	"wiki_list_user" => array(
		'label' => 'User',
		'note' => 'Last user to modify the page.',
	),
	"wiki_list_lastver" => array(
		'label' => 'Last version number',
		'note' => 'Shows the currently active version number.',
	),
	"wiki_list_comment" => array(
		'label' => 'Comment',
		'note' => 'Display the comment added on the last commit.',
	),
	"wiki_list_status" => array(
		'label' => 'Status',
		'note' => 'Displays wether the page is locked or open for modifications.',
	),
	"wiki_list_versions" => array(
		'label' => 'Versions',
		'note' => 'Display the number of available versions of a page.',
	),
	"wiki_list_links" => array(
		'label' => 'Links',
		'note' => 'Displays the number of links within a page.',
	),
	"wiki_list_backlinks" => array(
		'label' => 'Backlinks',
		'note' => 'Number of pages that link to a page.',
	),
	"wiki_list_format_guid" => array(
		'label' => 'Format GUID',
		'note' => 'Display the format GUID the page was saved in.',
	),
	"wiki_list_size" => array(
		'label' => 'Size',
		'note' => 'Size of page in bytes.',
	),
);
$gBitSmarty->assign( 'formWikiLists',$formWikiLists );

if (isset($_REQUEST["wikilistconf"])) {

	foreach( $formWikiLists as $item => $data ) {
		simple_set_toggle( $item );
	}
}

$formWikiFeatures = array(
	"feature_backlinks" => array(
		'label' => 'Backlinks',
		'note' => 'Display a dropdown list of pages that link to a page.',
	),
	"feature_likePages" => array(
		'label' => 'Like Pages',
		'note' => 'Display a list of pages that have a common word in the names.',
	),
	"feature_allow_dup_wiki_page_names" => array(
		'label' => 'Allow Duplicate Page Names',
		'note' => 'Allow Wiki Pages with dupliczte page names',
	),
	"feature_history" => array(
		'label' => 'History',
		'note' => 'Allow access to the page\'s history.',
	),
	"feature_listPages" => array(
		'label' => 'List Pages',
		'note' => 'Allow access to a listing of all wikipages.',
	),
	"feature_wiki_attachments" => array(
		'label' => 'Attachments',
		'note' => 'Allow the attachment of files to a wikipage.',
	),
	"feature_wiki_comments" => array(
		'label' => 'Comments',
		'note' => 'Allow the addition of user comments at the end of every wikipage.',
	),
	"feature_dump" => array(
		'label' => 'Dump',
		'note' => 'Allow the creation of a dump of a page.',
	),
	"feature_sandbox" => array(
		'label' => 'Sandbox',
		'note' => 'The Sandbox is a wikipage that can be modified by any user to practise the wiki syntax. This page has no history nor is the contents searchable.',
	),
	"feature_wiki_undo" => array(
		'label' => 'Undo',
		'note' => '???',
	),
	"feature_wiki_templates" => array(
		'label' => 'Use templates',
		'note' => 'Allow the usage of templates.',
	),
	"feature_wiki_rankings" => array(
		'label' => 'Rankings',
		'note' => 'Creates a ranking system based on the number of times a page is accessed.',
	),
	"feature_wiki_usrlock" => array(
		'label' => 'Users can lock pages',
		'note' => 'Users who have the right permissions can lock pages preventing changes by other users.',
	),
	"wiki_creator_admin" => array(
		'label' => 'Page creators are admin of their pages',
		'note' => 'Users who create a page, have all permissions regarding that particular page.',
	),
	"feature_wiki_url_import" => array(
		'label' => 'Allow URL Import',
		'note' => 'Allow urls to be imported and saved to the wiki.',
	),
	"feature_wiki_icache" => array(
		'label' => 'Individual WikiPage Cache',
		'note' => 'Allow individual cache settings for wikipages.',
	),
);

$gBitSmarty->assign( 'formWikiFeatures',$formWikiFeatures );
if (isset($_REQUEST["wikifeatures"])) {

	foreach( $formWikiFeatures as $item => $data ) {
		simple_set_toggle( $item,'wiki' );
	}
	if (isset($_REQUEST["feature_warn_on_edit"]) && $_REQUEST["feature_warn_on_edit"] == "y") {
		$gBitSystem->storePreference("feature_warn_on_edit", 'y');
		$gBitSmarty->assign("feature_warn_on_edit", 'y');
	} else {
		$gBitSystem->storePreference("feature_warn_on_edit", 'n');
		$gBitSmarty->assign("feature_warn_on_edit", 'n');
	}
	$gBitSystem->storePreference("warn_on_edit_time", $_REQUEST["warn_on_edit_time"]);
	$gBitSmarty->assign('warn_on_edit_time', $_REQUEST["warn_on_edit_time"]);
	$gBitSystem->storePreference('wiki_cache', $_REQUEST["wiki_cache"]);
	$gBitSmarty->assign('wiki_cache', $_REQUEST["wiki_cache"]);

	/*
	  if(isset($_REQUEST["feature_wiki_notepad"]) && $_REQUEST["feature_wiki_notepad"]=="y") {
		$gBitSystem->storePreference("feature_wiki_notepad",'y');
		$gBitSmarty->assign("feature_wiki_notepad",'y');
	  } else {
		$gBitSystem->storePreference("feature_wiki_notepad",'n');
		$gBitSmarty->assign("feature_wiki_notepad",'n');
	  }
	*/
	/* not sure if the following are still required */
	$gBitSystem->storePreference('feature_wiki_tables', $_REQUEST['feature_wiki_tables']);
	$gBitSmarty->assign('feature_wiki_tables', $_REQUEST['feature_wiki_tables']);
	if (isset($_REQUEST["feature_userVersions"]) && $_REQUEST["feature_userVersions"] == "y") {
		$gBitSystem->storePreference("feature_userVersions", 'y');
		$gBitSmarty->assign("feature_userVersions", 'y');
	} else {
		$gBitSystem->storePreference("feature_userVersions", 'n');
		$gBitSmarty->assign("feature_userVersions", 'n');
	}
}
$formWikiInOut = array(
	"feature_wiki_monosp" => array(
		'label' => 'Automonospaced text',
		'note' => 'When adding a space at the beginning of a line, the given line uses a monospace font.',
	),
	"wiki_spellcheck" => array(
		'label' => 'Spellchecking',
		'note' => 'Allow the usage of a spellchecking facility.',
	),
	"feature_wikiwords" => array(
		'label' => 'WikiWords',
		'note' => 'Automagically change words with CamelCaps or under_scores to links to internal wiki pages.',
	),
	"feature_wiki_plurals" => array(
		'label' => 'Link plural WikiWords to their singular form',
		'note' => 'If you use WikiWords as page name in a text, it will be linked to WikiWord.',
	),
	"feature_page_title" => array(
		'label' => 'Page Title',
		'note' => 'Display the page title at the top of every wikipage.',
	),
	"feature_wiki_description" => array(
		'label' => 'Description',
		'note' => 'Display a brief page description just below the title of the page.',
	),
	"hide_wiki_date" => array(
		'label' => 'Hide Date',
		'note' => 'Hide the date and creation / modification information.',
	),
	"feature_wiki_footnotes" => array(
		'label' => 'Footnotes',
		'note' => 'Allow the addition of footnotes to wikipages.',
	),
	"wiki_uses_slides" => array(
		'label' => 'Use Slideshows',
		'note' => 'If a wikipage is plit into a number of pages, this can be viewed as a slideshow, without menus or excess data on the page. can be useful for presentations and the like.',
	),
	"wiki_uses_s5" => array(
		'label' => 'Use S5 Slideshows',
		'note' => 'Any WikiPage can be turned into a full featured slideshow. Slides are separated at every H1 heading ( ! - wiki syntax ) and can be viewed using Firefox ( requires javascript ) or Opera ( need to press F11 to start the show ). Further information can be found at <a href="http://www.meyerweb.com/eric/tools/s5/">S5</a>',
	),
	"feature_wiki_multiprint" => array(
		'label' => 'Print Multiple Pages',
		'note' => 'Allow joining of pages for printing purposes.',
	),
);
$gBitSmarty->assign( 'formWikiInOut',$formWikiInOut );

if (isset($_REQUEST["wikiinout"])) {

	foreach( $formWikiInOut as $item => $data ) {
		simple_set_toggle( $item,'wiki' );
	}
}

$formWikiBooks = array(
	"feature_wiki_books" => array(
		'label' => 'WikiBooks',
		'note' => 'Allow the creation and use of WikiBooks - hierarchial collections of wiki pages',
	),
	"wikibook_show_path" => array(
		'label' => 'Show book path',
		'note' => 'If this settings is enabled, the path pointing to the currently viewed page will be displayed at the top of the page.<br />Alternatively, you can turn on the module "<a href="'.KERNEL_PKG_URL.'admin/index.php?page=layout">liberty -&gt; structure navigation</a>".',
	),
	"wikibook_show_navigation" => array(
		'label' => 'Show book navigation links',
		'note' => 'Book navigation links allow you to navigate books more easily providing the following links:<br /><strong>previous | parent page | next</strong>.<br />Alternatively, you can turn on the module "<a href="'.KERNEL_PKG_URL.'admin/index.php?page=layout">liberty -&gt; structure navigation</a>".',
	),
	"wikibook_use_icons" => array(
		'label' => 'Use navigation icons instead of words',
		'note' => 'This option will remove the names of the navigation controls and replace them with appropriate icons for navigation. This can be useful if you feel that navigation is too cluttered when showing that many words.',
	),
	"wikibook_hide_add_content" => array(
		'label' => 'Hide Edit "Structure Content"',
		'note' => 'Hide the tabbed panel to add content to the structure. This might be hidden for performance reasons.',
	),
);
$gBitSmarty->assign( 'formWikiBooks',$formWikiBooks );

if (isset($_REQUEST["wikibooks"])) {

	foreach( $formWikiBooks as $item => $data ) {
		simple_set_toggle( $item,'wiki' );
	}
}

$formWikiWatch = array(
	"wiki_watch_author" => array(
		'label' => 'Page author watch',
		'note' => 'Automatically set a watch for the author of a page.',
	),
	"wiki_watch_editor" => array(
		'label' => 'Page editor watch',
		'note' => 'Automatically set a watch for the editor of a page.',
	),
	"wiki_watch_comments" => array(
		'label' => 'Comment watch',
		'note' => 'Allow watching of comments (who knows if this works).',
	),
);
$gBitSmarty->assign( 'formWikiWatch',$formWikiWatch );

if (isset($_REQUEST["wikiwatch"])) {

	foreach( $formWikiWatch  as $item => $data ) {
		simple_set_toggle( $item,'wiki' );
	}
}

if (isset($_REQUEST["dump"])) {

	include (UTIL_PKG_PATH."tar.class.php");
	error_reporting (E_ERROR | E_WARNING);
	$wikilib->dumpPages();
}
if( file_exists( $wikilib->getDumpFile() ) ) {
	$gBitSmarty->assign('dumpUrl', $wikilib->getDumpUrl() );
}
if (isset($_REQUEST["createtag"])) {

	// Check existance
	if ($adminlib->tag_exists($_REQUEST["tagname"])) {
		$gBitSmarty->assign('msg', tra("Tag already exists"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	$adminlib->create_tag($_REQUEST["tagname"]);
}
if (isset($_REQUEST["restoretag"])) {

	// Check existance
	if (!$adminlib->tag_exists($_REQUEST["restagname"])) {
		$gBitSmarty->assign('msg', tra("Tag not found"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	$adminlib->restore_tag($_REQUEST["restagname"]);
}
if (isset($_REQUEST["removetag"])) {

	// Check existance
	$adminlib->remove_tag($_REQUEST["remtagname"]);
}
if (isset($_REQUEST["setwikihome"])) {

	$gBitSystem->storePreference('wikiHomePage', $_REQUEST["wikiHomePage"]);
	$gBitSmarty->assign('wikiHomePage', $_REQUEST["wikiHomePage"]);
}
if (isset($_REQUEST["wikidiscussprefs"])) {

	if (isset($_REQUEST["feature_wiki_discuss"])) {
		$gBitSystem->storePreference('feature_wiki_discuss', 'y');
		$gBitSmarty->assign('feature_wiki_discuss', 'y');
	} else {
		$gBitSystem->storePreference("feature_wiki_discuss", 'n');
		$gBitSmarty->assign('feature_wiki_discuss', 'n');
	}
	if (isset($_REQUEST["wiki_forum"])) {
		$gBitSystem->storePreference('wiki_forum', $_REQUEST["wiki_forum"]);
		$gBitSmarty->assign('wiki_forum', $_REQUEST["wiki_forum"]);
		$wiki_forum_id = $gBitSystem->mDb->getOne("select `forumID` from `tiki_forums` where `name`='" . $_REQUEST["wiki_forum"] . "'");
		$gBitSystem->storePreference('wiki_forum_id', $wiki_forum_id);
		$gBitSmarty->assign('wiki_forum_id', $wiki_forum_id);
	}
}
if (isset($_REQUEST["setwikiregex"])) {

	$gBitSystem->storePreference('wiki_page_regex', $_REQUEST["wiki_page_regex"]);
	$gBitSmarty->assign( 'wiki_page_regex', $_REQUEST["wiki_page_regex"] );
} else {
    $gBitSmarty->assign( 'wiki_page_regex', $gBitSystem->getPreference( 'wiki_page_regex', 'strict' ) );
}
if (isset($_REQUEST["wikisetprefs"])) {

	if (isset($_REQUEST["maxVersions"])) {
		$gBitSystem->storePreference("maxVersions", $_REQUEST["maxVersions"]);
	}
	if (isset($_REQUEST["keep_versions"])) {
		$gBitSystem->storePreference("keep_versions", $_REQUEST["keep_versions"]);
		$gBitSmarty->assign('keep_versions', $_REQUEST["keep_versions"]);
	}
}
if (isset($_REQUEST["wikisetcopyright"])) {

	simple_set_toggle( 'wiki_feature_copyrights','wiki' );
	if (isset($_REQUEST["wikiLicensePage"])) {
		$gBitSystem->storePreference("wikiLicensePage", $_REQUEST["wikiLicensePage"]);
		$gBitSmarty->assign('wikiLicensePage', $_REQUEST["wikiLicensePage"]);
	}
	if (isset($_REQUEST["wikiSubmitNotice"])) {
		$gBitSystem->storePreference("wikiSubmitNotice", $_REQUEST["wikiSubmitNotice"]);
		$gBitSmarty->assign('wikiSubmitNotice', $_REQUEST["wikiSubmitNotice"]);
	}
}
if ( $gBitSystem->isPackageActive( 'tiki_forums' ) ) {
	$commentslib = new Comments();
	$all_forums = $commentslib->list_forums(0, -1, 'name_asc', '');
	$gBitSmarty->assign_by_ref("all_forums", $all_forums["data"]);
}
$tags = $adminlib->get_tags();
$gBitSmarty->assign_by_ref("tags", $tags);
$gBitSmarty->assign("maxVersions", $gBitSystem->getPreference("maxVersions", 0));
$gBitSmarty->assign("keep_versions", $gBitSystem->getPreference("keep_versions", 1));

$gBitSmarty->assign("wiki_feature_copyrights", $gBitSystem->getPreference("wiki_feature_copyrights"));
$gBitSmarty->assign('wikiLicensePage', $gBitSystem->getPreference("wikiLicensePage"));
$gBitSmarty->assign('wikiSubmitNotice', $gBitSystem->getPreference("wikiSubmitNotice"));


?>
