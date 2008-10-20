<?php

$tables = array(

'wiki_footnotes' => "
	user_id C(40) PRIMARY,
	page_id I4 NOTNULL,
	data X
",

'wiki_pages' => "
	page_id I4 PRIMARY,
	content_id I4 NOTNULL,
	wiki_page_size I4 DEFAULT 0,
	edit_comment C(200),
	flag C(1)
",

'wiki_received_pages' => "
	received_page_id I4 AUTO PRIMARY,
	title C(160) NOTNULL,
	data X,
	description C(200),
	received_comment C(200),
	received_from_site C(200),
	received_from_user C(200),
	received_date I8
",

'wiki_tags' => "
	page_id I4 PRIMARY,
	tag_name C(80) PRIMARY,
	title C(160),
	user_id I4 NOTNULL,
	hits I4,
	description C(200),
	data X,
	last_modified I8,
	tag_comment C(200),
	version I4 NOTNULL,
	ip C(15),
	flag C(1)
",

'wiki_ext' => "
	extwiki_id I4 AUTO PRIMARY,
	name C(200) NOTNULL,
	extwiki C(255)
"

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( WIKI_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( WIKI_PKG_NAME, array(
	'description' => "A wiki is 'the simplest online database that could possibly work.' No HTML or programming knowledge is needed to contribute to a wiki.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array (
	'wiki_pages_content_idx' => array( 'table' => 'wiki_pages', 'cols' => 'content_id', 'opts' => 'UNIQUE' ),
	'wiki_page_footnotes_page_idx' => array( 'table' => 'wiki_footnotes', 'cols' => 'page_id', 'opts' => NULL )
);
$gBitInstaller->registerSchemaIndexes( WIKI_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'wiki_pages_page_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( WIKI_PKG_NAME, $sequences );


// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( WIKI_PKG_NAME, array(
	array('p_wiki_admin_book', 'Can administer books', 'editors', WIKI_PKG_NAME),
	array('p_wiki_admin', 'Can admin the wiki', 'admin', WIKI_PKG_NAME),
	array('p_wiki_update_book', 'Can edit books', 'registered', WIKI_PKG_NAME),
	array('p_wiki_create_book', 'Can create books', 'registered', WIKI_PKG_NAME),
	array('p_wiki_edit_copyright', 'Can edit copyright notices', 'registered', WIKI_PKG_NAME),
	array('p_wiki_edit_dynvar', 'Can edit dynamic variables', 'editors', WIKI_PKG_NAME),
	array('p_wiki_edit_page', 'Can edit pages', 'registered', WIKI_PKG_NAME),
	array('p_wiki_create_page', 'Can create pages', 'registered', WIKI_PKG_NAME),
	array('p_wiki_list_pages', 'Can list pages', 'registered', WIKI_PKG_NAME),
	array('p_wiki_lock_page', 'Can lock pages', 'editors', WIKI_PKG_NAME),
	array('p_wiki_remove_page', 'Can remove a wiki page', 'editors', WIKI_PKG_NAME),
	array('p_wiki_rename_page', 'Can rename pages', 'editors', WIKI_PKG_NAME),
	array('p_wiki_rollback', 'Can rollback pages', 'editors', WIKI_PKG_NAME),
	array('p_wiki_save_minor', 'Can save as minor edit', 'registered', WIKI_PKG_NAME),
	array('p_wiki_view_history', 'Can view page history', 'basic', WIKI_PKG_NAME),
	array('p_wiki_view_page', 'Can view page/pages', 'basic', WIKI_PKG_NAME),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( WIKI_PKG_NAME, array(
	//array( WIKI_PKG_NAME, 'warn_on_edit','n'),
	//array( WIKI_PKG_NAME, 'wiki_allow_dup_page_names','y'),
	array( WIKI_PKG_NAME, 'wiki_attachments','y'),
	array( WIKI_PKG_NAME, 'wiki_backlinks','y'),
	array( WIKI_PKG_NAME, 'wiki_book_show_navigation','y'),
	array( WIKI_PKG_NAME, 'wiki_book_show_path','y'),
	array( WIKI_PKG_NAME, 'wiki_books','y'),
	//array( WIKI_PKG_NAME, 'wiki_comments','n'),
	//array( WIKI_PKG_NAME, 'wiki_copyrights','n'),
	//array( WIKI_PKG_NAME, 'wiki_creator_admin','n'),
	array( WIKI_PKG_NAME, 'wiki_description','y'),
	array( WIKI_PKG_NAME, 'wiki_dump','y'),
	//array( WIKI_PKG_NAME, 'wiki_footnotes','n'),
	//array( WIKI_PKG_NAME, 'wiki_hide_date','n'),
	array( WIKI_PKG_NAME, 'wiki_history','y'),
	array( WIKI_PKG_NAME, 'wiki_home_page','Welcome'),
	array( WIKI_PKG_NAME, 'wiki_last_changes','y'),
	//array( WIKI_PKG_NAME, 'wiki_license_page',''),
	array( WIKI_PKG_NAME, 'wiki_like_pages','y'),
	array( WIKI_PKG_NAME, 'wiki_list_backlinks','y'),
	array( WIKI_PKG_NAME, 'wiki_list_comment','y'),
	array( WIKI_PKG_NAME, 'wiki_list_creator','y'),
	//array( WIKI_PKG_NAME, 'wiki_list_format_guid',''),
	array( WIKI_PKG_NAME, 'wiki_list_hits','y'),
	array( WIKI_PKG_NAME, 'wiki_list_lastmodif','y'),
	array( WIKI_PKG_NAME, 'wiki_list_lastver','y'),
	array( WIKI_PKG_NAME, 'wiki_list_links','y'),
	array( WIKI_PKG_NAME, 'wiki_list_name','y'),
	array( WIKI_PKG_NAME, 'wiki_list_orphans','y'),
	array( WIKI_PKG_NAME, 'wiki_list_pages','y'),
	array( WIKI_PKG_NAME, 'wiki_list_size','y'),
	array( WIKI_PKG_NAME, 'wiki_list_status','y'),
	array( WIKI_PKG_NAME, 'wiki_list_user','y'),
	array( WIKI_PKG_NAME, 'wiki_list_versions','y'),
	//array( WIKI_PKG_NAME, 'wiki_monosp','n'),
	//array( WIKI_PKG_NAME, 'wiki_multiprint','n'),
	array( WIKI_PKG_NAME, 'wiki_page_regex','strict'),
	array( WIKI_PKG_NAME, 'wiki_page_title','y'),
	array( WIKI_PKG_NAME, 'wiki_pictures','y'),
	array( WIKI_PKG_NAME, 'wiki_plurals','y'),
	//array( WIKI_PKG_NAME, 'wiki_preserve_leading_blanks','n'),
	//array( WIKI_PKG_NAME, 'wiki_ranking','n'),
	array( WIKI_PKG_NAME, 'wiki_rankings','y'),
	array( WIKI_PKG_NAME, 'wiki_sandbox','y'),
	//array( WIKI_PKG_NAME, 'wiki_section_edit','n'),
	//array( WIKI_PKG_NAME, 'wiki_submit_notice',''),
	array( WIKI_PKG_NAME, 'wiki_tables','new'),
	//array( WIKI_PKG_NAME, 'wiki_undo','n'),
	//array( WIKI_PKG_NAME, 'wiki_url_import','n'),
	//array( WIKI_PKG_NAME, 'wiki_user_versions','n'),
	//array( WIKI_PKG_NAME, 'wiki_uses_s5','n'),
	//array( WIKI_PKG_NAME, 'wiki_uses_slides','n'),
	//array( WIKI_PKG_NAME, 'wiki_usrlock','n'),
	array( WIKI_PKG_NAME, 'wiki_warn_on_edit_time','2'),
	//array( WIKI_PKG_NAME, 'wiki_watch_author','n'),
	//array( WIKI_PKG_NAME, 'wiki_watch_comments','n'),
	//array( WIKI_PKG_NAME, 'wiki_watch_editor','n'),
	array( WIKI_PKG_NAME, 'wiki_words','y'),
	//array( WIKI_PKG_NAME, 'wikibook_hide_add_content','n'),
	//array( WIKI_PKG_NAME, 'wikibook_use_icons','n'),
) );

if( defined( 'RSS_PKG_NAME' )) {
	$gBitInstaller->registerPreferences( WIKI_PKG_NAME, array(
		array( RSS_PKG_NAME, WIKI_PKG_NAME.'_rss', 'y'),
	));
}

// ### Register content types
$gBitInstaller->registerContentObjects( WIKI_PKG_NAME, array( 
	'BitPage'=>WIKI_PKG_PATH.'BitPage.php',
	'BitBook'=>WIKI_PKG_PATH.'BitBook.php',
));
?>
