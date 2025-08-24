<?php
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

$formWikiLists = [
	"wiki_list_page_id" => [
		'label' => 'Page ID',
		'note' => 'Page ID of the wikipage.',
	],
	"wiki_list_content_id" => [
		'label' => 'Content ID',
		'note' => 'Content ID of the wikipage.',
	],
	"wiki_list_name" => [
		'label' => 'Name',
		'note' => 'Name of the wikipage.',
	],
	"wiki_list_hits" => [
		'label' => 'Hits',
		'note' => 'How often the page has been viewed.',
	],
	"wiki_list_lastmodif" => [
		'label' => 'Last modification date',
		'note' => 'Date of the last modification of a page.',
	],
	"wiki_list_creator" => [
		'label' => 'Creator',
		'note' => 'Name of the creator of a page.',
	],
	"wiki_list_user" => [
		'label' => 'User',
		'note' => 'Last user to modify the page.',
	],
	"wiki_list_lastver" => [
		'label' => 'Last version number',
		'note' => 'Shows the currently active version number.',
	],
	"wiki_list_comment" => [
		'label' => 'Comment',
		'note' => 'Display the comment added on the last commit.',
	],
	"wiki_list_status" => [
		'label' => 'Status',
		'note' => 'Displays wether the page is locked or open for modifications.',
	],
	"wiki_list_versions" => [
		'label' => 'Versions',
		'note' => 'Display the number of available versions of a page.',
	],
	"wiki_list_links" => [
		'label' => 'Links',
		'note' => 'Displays the number of links within a page.',
	],
	"wiki_list_backlinks" => [
		'label' => 'Backlinks',
		'note' => 'Number of pages that link to a page.',
	],
	"wiki_list_format_guid" => [
		'label' => 'Format GUID',
		'note' => 'Display the format GUID the page was saved in.',
	],
	"wiki_list_size" => [
		'label' => 'Size',
		'note' => 'Size of page in bytes.',
	],
];
$gBitSmarty->assign( 'formWikiLists',$formWikiLists );

if (isset($_REQUEST["wikilistconf"])) {

	foreach( $formWikiLists as $item => $data ) {
		simple_set_toggle( $item, WIKI_PKG_NAME );
	}
}

$formWikiFeatures = [
	"wiki_backlinks" => [
		'label' => 'Backlinks',
		'note' => 'Display a dropdown list of pages that link to a page.',
	],
	"wiki_like_pages" => [
		'label' => 'Like Pages',
		'note' => 'Display a list of pages that have a common word in the names.',
	],
	"wiki_allow_dup_page_names" => [
		'label' => 'Allow Duplicate Page Names',
		'note' => 'Allow Wiki Pages with duplicate page names',
	],
	"wiki_history" => [
		'label' => 'History',
		'note' => 'Allow access to the page\'s history.',
	],
	"wiki_list_orphans" => [
		'label' => 'List Orphans',
		'note' => 'Allow access to a listing of all orphaned wikipages.',
	],
	"wiki_attachments" => [
		'label' => 'Attachments',
		'note' => 'Allow the attachment of files to a wikipage.',
	],
	"wiki_comments" => [
		'label' => 'Comments',
		'note' => 'Allow the addition of user comments at the end of every wikipage.',
	],
	"wiki_undo" => [
		'label' => 'Undo',
		'note' => 'Reverts a wikipage to the previous version held in its page history.',
	],
	"wiki_rankings" => [
		'label' => 'Rankings',
		'note' => 'Creates a ranking system based on the number of times a page is accessed.',
	],
	"wiki_usrlock" => [
		'label' => 'Users can lock pages',
		'note' => 'Users who have the right permissions can lock pages preventing changes by other users.',
	],
	"wiki_creator_admin" => [
		'label' => 'Page creators are admin of their pages',
		'note' => 'Users who create a page, have all permissions regarding that particular page.',
	],
	"wiki_url_import" => [
		'label' => 'Allow URL Import',
		'note' => 'Allow urls to be imported and saved to the wiki.',
	],
	"wiki_preserve_leading_blanks" => [
		'label' => 'Preserve leading blanks',
		'note' => 'Preserve leading blanks on Wiki Pages, provided for compatiblity with sites migrated from Tikiwiki.',
	],
];

$gBitSmarty->assign( 'formWikiFeatures',$formWikiFeatures );
if( !empty( $_REQUEST["wikifeatures"] )) {
	foreach( $formWikiFeatures as $item => $data ) {
		simple_set_toggle( $item, WIKI_PKG_NAME );
	}
	simple_set_int( 'wiki_section_edit', WIKI_PKG_NAME );

	if (isset($_REQUEST["warn_on_edit"]) && $_REQUEST["warn_on_edit"][0] == "y") {
		$gBitSystem->storeConfig("warn_on_edit", 'y', WIKI_PKG_NAME);
		$gBitSmarty->assign("warn_on_edit", 'y');
	} else {
		$gBitSystem->storeConfig("warn_on_edit", 'n', WIKI_PKG_NAME);
		$gBitSmarty->assign("warn_on_edit", 'n');
	}
	//$gBitSystem->storeConfig("wiki_link_type", $_REQUEST["link_type"], WIKI_PKG_NAME);

	/* not sure if the following are still required */
	$gBitSystem->storeConfig('wiki_tables', $_REQUEST['wiki_tables'], WIKI_PKG_NAME);
	$gBitSmarty->assign('wiki_tables', $_REQUEST['wiki_tables']);
	if (isset($_REQUEST["wiki_user_versions"]) && $_REQUEST["wiki_user_versions"] == "y") {
		$gBitSystem->storeConfig("wiki_user_versions", 'y', WIKI_PKG_NAME);
		$gBitSmarty->assign("wiki_user_versions", 'y');
	} else {
		$gBitSystem->storeConfig("wiki_user_versions", 'n', WIKI_PKG_NAME);
		$gBitSmarty->assign("wiki_user_versions", 'n');
	}
}
$formWikiInOut = [
	"wiki_monosp" => [
		'label' => 'Automonospaced text',
		'note' => 'When adding a space at the beginning of a line, the given line uses a monospace font.',
	],
	"wiki_words" => [
		'label' => 'WikiWords',
		'note' => 'Automagically change words with CamelCaps or under_scores to links to internal wiki pages.',
	],
	"wiki_plurals" => [
		'label' => 'Link plural WikiWords to their singular form',
		'note' => 'If you use WikiWords as page name in a text, it will be linked to WikiWord.',
	],
	"wiki_page_title" => [
		'label' => 'Page Title',
		'note' => 'Display the page title at the top of every wikipage.',
	],
	"wiki_description" => [
		'label' => 'Description',
		'note' => 'Display a brief page description just below the title of the page.',
	],
	"wiki_hide_date" => [
		'label' => 'Hide Date',
		'note' => 'Hide the date and creation / modification information.',
	],
	"wiki_footnotes" => [
		'label' => 'Footnotes',
		'note' => 'Allow the addition of footnotes to wikipages.',
	],
	"wiki_uses_slides" => [
		'label' => 'Use Slideshows',
		'note' => 'If a wikipage is plit into a number of pages, this can be viewed as a slideshow, without menus or excess data on the page. can be useful for presentations and the like.',
	],
	"wiki_multiprint" => [
		'label' => 'Print Multiple Pages',
		'note' => 'Allow joining of pages for printing purposes.',
	],
];
$gBitSmarty->assign( 'formWikiInOut',$formWikiInOut );

if( !empty( $_REQUEST["wikiinout"] )) {
	foreach( $formWikiInOut as $item => $data ) {
		simple_set_toggle( $item, WIKI_PKG_NAME );
	}
}

$formWikiBooks = [
	"wiki_books" => [
		'label' => 'WikiBooks',
		'note' => 'Allow the creation and use of WikiBooks - hierarchial collections of wiki pages',
	],
	"wiki_book_show_path" => [
		'label' => 'Show book path',
		'note' => 'If this settings is enabled, the path pointing to the currently viewed page will be displayed at the top of the page.<br />Alternatively, you can turn on the module "<a href="'.KERNEL_PKG_URL.'admin/index.php?page=layout">liberty -&gt; structure navigation</a>".',
	],
	"wiki_book_show_navigation" => [
		'label' => 'Show book navigation links',
		'note' => 'Book navigation links allow you to navigate books more easily providing the following links:<br /><strong>previous | parent page | next</strong>.<br />Alternatively, you can turn on the module "<a href="'.KERNEL_PKG_URL.'admin/index.php?page=layout">liberty -&gt; structure navigation</a>".',
	],
	"wikibook_use_icons" => [
		'label' => 'Use navigation icons instead of words',
		'note' => 'This option will remove the names of the navigation controls and replace them with appropriate icons for navigation. This can be useful if you feel that navigation is too cluttered when showing that many words.',
	],
	"wikibook_hide_add_content" => [
		'label' => 'Hide Edit "Structure Content"',
		'note' => 'Hide the tabbed panel to add content to the structure. This might be hidden for performance reasons.',
	],
];
$gBitSmarty->assign( 'formWikiBooks',$formWikiBooks );

if( !empty( $_REQUEST["wikibooks"] )) {
	foreach( $formWikiBooks as $item => $data ) {
		simple_set_toggle( $item, WIKI_PKG_NAME );
	}
}

$formWikiWatch = [
	"wiki_watch_author" => [
		'label' => 'Page author watch',
		'note' => 'Automatically set a watch for the author of a page.',
	],
	"wiki_watch_editor" => [
		'label' => 'Page editor watch',
		'note' => 'Automatically set a watch for the editor of a page.',
	],
//	"wiki_watch_comments" => [
//		'label' => 'Comment watch',
//		'note' => 'Allow watching of comments (who knows if this works).',
//	],
];
$gBitSmarty->assign( 'formWikiWatch',$formWikiWatch );

if( !empty( $_REQUEST["wikiwatch"] )) {
	foreach( $formWikiWatch  as $item => $data ) {
		simple_set_toggle( $item, WIKI_PKG_NAME );
	}
}

if( !empty( $_REQUEST["setwikihome"] )) {
	$gBitSystem->storeConfig( 'wiki_home_page', $_REQUEST["wiki_home_page"], WIKI_PKG_NAME );
	$gBitSmarty->assign('wiki_home_page', $_REQUEST["wiki_home_page"]);

	simple_set_toggle( 'wiki_disable_auto_home',WIKI_PKG_NAME );
//	$gBitSystem->storeConfig( 'wiki_disable_auto_home', $_REQUEST["wiki_disable_auto_home"], WIKI_PKG_NAME );
//	$gBitSmarty->assign('wiki_disable_auto_home', $_REQUEST["wiki_disable_auto_home"]);
}

if( !empty( $_REQUEST["setwikiregex"] )) {
	$gBitSystem->storeConfig( 'wiki_page_regex', $_REQUEST["wiki_page_regex"], WIKI_PKG_NAME );
	$gBitSmarty->assign( 'wiki_page_regex', $_REQUEST["wiki_page_regex"] );
} else {
	$gBitSmarty->assign( 'wiki_page_regex', $gBitSystem->getConfig( 'wiki_page_regex', 'strict' ) );
}

if( !empty( $_REQUEST["wikisetcopyright"] )) {
	simple_set_toggle( 'wiki_copyrights',WIKI_PKG_NAME );
	if( !empty( $_REQUEST["wiki_license_page"] )) {
		$gBitSystem->storeConfig("wiki_license_page", $_REQUEST["wiki_license_page"], WIKI_PKG_NAME);
		$gBitSmarty->assign( 'wiki_license_page', $_REQUEST["wiki_license_page"] );
	}
	if( !empty( $_REQUEST["wiki_submit_notice"] )) {
		$gBitSystem->storeConfig("wiki_submit_notice", $_REQUEST["wiki_submit_notice"], WIKI_PKG_NAME);
		$gBitSmarty->assign( 'wiki_submit_notice', $_REQUEST["wiki_submit_notice"] );
	}
}
$gBitSmarty->assign("wiki_copyrights", $gBitSystem->getConfig( "wiki_copyrights" ));
$gBitSmarty->assign('wiki_license_page', $gBitSystem->getConfig( "wiki_license_page" ));
$gBitSmarty->assign('wiki_submit_notice', $gBitSystem->getConfig( "wiki_submit_notice" ));
