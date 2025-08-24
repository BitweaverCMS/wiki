<?php

$tables = [

'wiki_pages' => "
	page_id I4 PRIMARY,
	content_id I4 NOTnull,
	wiki_page_size I4 DEFAULT 0,
	edit_comment C(200),
	flag C(1)
	CONSTRAINT ', CONSTRAINT `wiki_pages_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )'
",

'wiki_footnotes' => "
	user_id I4 PRIMARY,
	page_id I4 NOTnull,
	data X
	CONSTRAINT ', CONSTRAINT `wiki_footnotes_page_ref` FOREIGN KEY (`page_id`) REFERENCES `".BIT_DB_PREFIX."wiki_pages` (`page_id`)
  				, CONSTRAINT `wiki_footnotes_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

];

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( WIKI_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( WIKI_PKG_NAME, [ 
	'description' => "A wiki is 'the simplest online database that could possibly work.' No HTML or programming knowledge is needed to contribute to a wiki.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
] );

// ### Indexes
$indices = [
	'wiki_pages_content_idx' => [  'table' => 'wiki_pages', 'cols' => 'content_id', 'opts' => 'UNIQUE' ],
	'wiki_page_footnotes_page_idx' => [  'table' => 'wiki_footnotes', 'cols' => 'page_id', 'opts' => null ]
];
$gBitInstaller->registerSchemaIndexes( WIKI_PKG_NAME, $indices );

// ### Sequences
$sequences = [
	'wiki_pages_page_id_seq' => [  'start' => 1 ]
];
$gBitInstaller->registerSchemaSequences( WIKI_PKG_NAME, $sequences );


// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( WIKI_PKG_NAME, [
	[ 'p_wiki_admin_book', 'Can administer books', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_admin', 'Can admin the wiki', 'admin', WIKI_PKG_NAME ],
	[ 'p_wiki_update_book', 'Can edit books', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_create_book', 'Can create books', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_edit_copyright', 'Can edit copyright notices', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_edit_dynvar', 'Can edit dynamic variables', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_update_page', 'Can edit pages', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_create_page', 'Can create pages', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_list_pages', 'Can list pages', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_lock_page', 'Can lock pages', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_remove_page', 'Can remove a wiki page', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_rename_page', 'Can rename pages', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_rollback', 'Can rollback pages', 'editors', WIKI_PKG_NAME ],
	[ 'p_wiki_save_minor', 'Can save as minor edit', 'editor', WIKI_PKG_NAME ],
	[ 'p_wiki_view_history', 'Can view page history', 'basic', WIKI_PKG_NAME ],
	[ 'p_wiki_view_page', 'Can view page/pages', 'basic', WIKI_PKG_NAME ],
] );

// ### Default Preferences
$gBitInstaller->registerPreferences( WIKI_PKG_NAME, [ 
	//[  WIKI_PKG_NAME, 'warn_on_edit','n' ],
	//[  WIKI_PKG_NAME, 'wiki_allow_dup_page_names','y' ],
	[  WIKI_PKG_NAME, 'wiki_attachments','y' ],
	[  WIKI_PKG_NAME, 'wiki_backlinks','y' ],
	[  WIKI_PKG_NAME, 'wiki_book_show_navigation','y' ],
	[  WIKI_PKG_NAME, 'wiki_book_show_path','y' ],
	[  WIKI_PKG_NAME, 'wiki_books','y' ],
	//[  WIKI_PKG_NAME, 'wiki_comments','n' ],
	//[  WIKI_PKG_NAME, 'wiki_copyrights','n' ],
	//[  WIKI_PKG_NAME, 'wiki_creator_admin','n' ],
	[  WIKI_PKG_NAME, 'wiki_description','y' ],
	[  WIKI_PKG_NAME, 'wiki_dump','y' ],
	//[  WIKI_PKG_NAME, 'wiki_footnotes','n' ],
	//[  WIKI_PKG_NAME, 'wiki_hide_date','n' ],
	[  WIKI_PKG_NAME, 'wiki_history','y' ],
	[  WIKI_PKG_NAME, 'wiki_home_page','Welcome' ],
	[  WIKI_PKG_NAME, 'wiki_last_changes','y' ],
	//[  WIKI_PKG_NAME, 'wiki_license_page','' ],
	[  WIKI_PKG_NAME, 'wiki_like_pages','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_backlinks','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_comment','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_creator','y' ],
	//[  WIKI_PKG_NAME, 'wiki_list_format_guid','' ],
	[  WIKI_PKG_NAME, 'wiki_list_hits','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_lastmodif','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_lastver','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_links','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_name','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_orphans','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_size','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_status','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_user','y' ],
	[  WIKI_PKG_NAME, 'wiki_list_versions','y' ],
	//[  WIKI_PKG_NAME, 'wiki_monosp','n' ],
	//[  WIKI_PKG_NAME, 'wiki_multiprint','n' ],
	[  WIKI_PKG_NAME, 'wiki_page_regex','strict' ],
	[  WIKI_PKG_NAME, 'wiki_page_title','y' ],
	[  WIKI_PKG_NAME, 'wiki_pictures','y' ],
	[  WIKI_PKG_NAME, 'wiki_plurals','y' ],
	//[  WIKI_PKG_NAME, 'wiki_preserve_leading_blanks','n' ],
	//[  WIKI_PKG_NAME, 'wiki_ranking','n' ],
	[  WIKI_PKG_NAME, 'wiki_rankings','y' ],
	//[  WIKI_PKG_NAME, 'wiki_section_edit','n' ],
	//[  WIKI_PKG_NAME, 'wiki_submit_notice','' ],
	[  WIKI_PKG_NAME, 'wiki_tables','new' ],
	//[  WIKI_PKG_NAME, 'wiki_undo','n' ],
	//[  WIKI_PKG_NAME, 'wiki_url_import','n' ],
	//[  WIKI_PKG_NAME, 'wiki_user_versions','n' ],
	//[  WIKI_PKG_NAME, 'wiki_uses_slides','n' ],
	//[  WIKI_PKG_NAME, 'wiki_usrlock','n' ],
	[  WIKI_PKG_NAME, 'wiki_warn_on_edit_time','2' ],
	//[  WIKI_PKG_NAME, 'wiki_watch_author','n' ],
	//[  WIKI_PKG_NAME, 'wiki_watch_comments','n' ],
	//[  WIKI_PKG_NAME, 'wiki_watch_editor','n' ],
	//[  WIKI_PKG_NAME, 'wiki_words','y' ],
	//[  WIKI_PKG_NAME, 'wikibook_hide_add_content','n' ],
	//[  WIKI_PKG_NAME, 'wikibook_use_icons','n' ],
] );

if( defined( 'RSS_PKG_NAME' )) {
	$gBitInstaller->registerPreferences( WIKI_PKG_NAME, [ 
		[  RSS_PKG_NAME, WIKI_PKG_NAME.'_rss', 'y' ],
	] );
}

// ### Register content types
$gBitInstaller->registerContentObjects( WIKI_PKG_NAME, [ 
	'BitPage' => WIKI_PKG_CLASS_PATH.'BitPage.php',
	'BitBook' => WIKI_PKG_CLASS_PATH.'BitBook.php',
] );

// Requirements
$gBitInstaller->registerRequirements( WIKI_PKG_NAME, [ 
    'liberty' => [ 'min' => '5.0.0' ],
] );
