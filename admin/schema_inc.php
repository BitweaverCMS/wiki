<?php

$tables = array(

'tiki_actionlog' => "
	page_id I4 NOTNULL,
	user_id I4 NOTNULL,
	action C(255) NOTNULL,
	last_modified I8,
	title C(160),
	ip C(15),
	comment C(200)
",

'tiki_copyrights' => "
	copyright_id I4 AUTO PRIMARY,
	page_id I4 NOTNULL,
	title C(200),
	year I8,
	authors C(200),
	copyright_order I8,
	user_id I4
",

'tiki_history' => "
	page_id I4 PRIMARY,
	version I4 PRIMARY,
	last_modified I8 NOTNULL,
	format_guid C(16) NOTNULL,
	description C(200),
	user_id C(40),
	ip C(15),
	comment C(200),
	data X
	CONSTRAINTS ', CONSTRAINT `tiki_history_page_ref` FOREIGN KEY (`page_id`) REFERENCES `".BIT_DB_PREFIX."tiki_pages`( `page_id` )'
",

'tiki_links' => "
	from_content_id I4 PRIMARY,
	to_content_id I4 PRIMARY
",

'tiki_page_footnotes' => "
	user_id C(40) PRIMARY,
	page_id I4 NOTNULL,
	data X
",

'tiki_pages' => "
	page_id I4 PRIMARY,
	content_id I4 NOTNULL,
	version I4 NOTNULL,
	page_size I4 DEFAULT 0,
	description C(200),
	comment C(200),
	flag C(1),
	points I4,
	votes I4,
	cache X,
	wiki_cache I8,
	cache_timestamp I8,
	page_rank N(4,3)
",

'tiki_received_pages' => "
	received_page_id I4 AUTO PRIMARY,
	title C(160) NOTNULL,
	data X,
	description C(200),
	comment C(200),
	received_from_site C(200),
	received_from_user C(200),
	received_date I8
",

'tiki_tags' => "
	page_id I4 PRIMARY,
	tag_name C(80) PRIMARY,
	title C(160),
	user_id I4 NOTNULL,
	hits I4,
	description C(200),
	data X,
	last_modified I8,
	comment C(200),
	version I4 NOTNULL,
	ip C(15),
	flag C(1)
",

'tiki_semaphores' => "
	sem_name C(250) PRIMARY,
	user_id I4 NOTNULL,
	created I8
",

'tiki_extwiki' => "
	extwiki_id I4 AUTO PRIMARY,
	name C(200) NOTNULL,
	extwiki C(255)
"

);

global $gBitInstaller;

$gBitInstaller->makePackageHomeable(WIKI_PKG_NAME);

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( WIKI_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( WIKI_PKG_NAME, array(
	'description' => "A wiki is 'the simplest online database that could possibly work.' No HTML or programming knowledge is needed to contribute to a wiki.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'experimental',
	'dependencies' => '',
) );

// ### Indexes
$indices = array (
	'tiki_pages_content_idx' => array( 'table' => 'tiki_pages', 'cols' => 'content_id', 'opts' => 'UNIQUE' ),
	'tiki_pages_page_rank_idx' => array( 'table' => 'tiki_pages', 'cols' => 'page_rank', 'opts' => NULL ),
	'tiki_page_footnotes_page_idx' => array( 'table' => 'tiki_page_footnotes', 'cols' => 'page_id', 'opts' => NULL )
);
$gBitInstaller->registerSchemaIndexes( WIKI_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'tiki_pages_page_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( WIKI_PKG_NAME, $sequences );


// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( WIKI_PKG_NAME, array(
	array('bit_p_edit_dynvar', 'Can edit dynamic variables', 'editors', WIKI_PKG_NAME),
	array('bit_p_edit', 'Can edit pages', 'registered', WIKI_PKG_NAME),
	array('bit_p_view', 'Can view page/pages', 'basic', WIKI_PKG_NAME),
	array('bit_p_remove', 'Can remove', 'editors', WIKI_PKG_NAME),
	array('bit_p_rollback', 'Can rollback pages', 'editors', WIKI_PKG_NAME),
	array('bit_p_admin_wiki', 'Can admin the wiki', 'editors', WIKI_PKG_NAME),
	array('bit_p_wiki_admin_attachments', 'Can admin attachments to wiki pages', 'editors', WIKI_PKG_NAME),
	array('bit_p_wiki_view_attachments', 'Can view wiki attachments and download', 'registered', WIKI_PKG_NAME),
	array('bit_p_upload_picture', 'Can upload pictures to wiki pages', 'registered', WIKI_PKG_NAME),
	array('bit_p_minor', 'Can save as minor edit', 'registered', WIKI_PKG_NAME),
	array('bit_p_rename', 'Can rename pages', 'editors', WIKI_PKG_NAME),
	array('bit_p_lock', 'Can lock pages', 'editors', WIKI_PKG_NAME),

	array('bit_p_edit_books', 'Can create and edit books', 'registered', WIKI_PKG_NAME),
	array('bit_p_admin_books', 'Can administer books', 'editors', WIKI_PKG_NAME),
	array('bit_p_edit_copyrights', 'Can edit copyright notices', 'registered', WIKI_PKG_NAME)
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( WIKI_PKG_NAME, array(
	array(WIKI_PKG_NAME, 'anonCanEdit','n'),
	array(WIKI_PKG_NAME, 'feature_autolinks','y'),
	array(WIKI_PKG_NAME, 'feature_backlinks','y'),
	array(WIKI_PKG_NAME, 'feature_dump','y'),
	array(WIKI_PKG_NAME, 'feature_history','y'),
	array(WIKI_PKG_NAME, 'feature_lastChanges','y'),
	array(WIKI_PKG_NAME, 'feature_likePages','y'),
	array(WIKI_PKG_NAME, 'feature_allow_dup_wiki_page_names','y'),
	array(WIKI_PKG_NAME, 'feature_listPages','y'),
	array(WIKI_PKG_NAME, 'feature_page_title','y'),
	array(WIKI_PKG_NAME, 'feature_ranking','n'),
	array(WIKI_PKG_NAME, 'feature_sandbox','y'),
	array(WIKI_PKG_NAME, 'feature_warn_on_edit','n'),
	array(WIKI_PKG_NAME, 'feature_wiki','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_attachments','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_books','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_comments','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_description','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_discuss','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_footnotes','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_icache','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_monosp','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_multiprint','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_notepad','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_generate_pdf',''),
	array(WIKI_PKG_NAME, 'feature_wiki_pictures','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_plurals','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_rankings','y'),
	array(WIKI_PKG_NAME, 'feature_wiki_tables','new'),
	array(WIKI_PKG_NAME, 'feature_wiki_templates','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_undo','n'),
	array(WIKI_PKG_NAME, 'feature_wiki_usrlock','n'),
	array(WIKI_PKG_NAME, 'feature_wikiwords','y'),
	array(WIKI_PKG_NAME, 'keep_versions','1'),
	array(WIKI_PKG_NAME, 'maxVersions','0'),
	array(WIKI_PKG_NAME, 'w_use_db','y'),
	array(WIKI_PKG_NAME, 'w_use_dir',''),
	array(WIKI_PKG_NAME, 'warn_on_edit_time','2'),
	array(WIKI_PKG_NAME, 'wiki_bot_bar','n'),
	array(WIKI_PKG_NAME, 'wiki_cache','0'),
	array(WIKI_PKG_NAME, 'wiki_creator_admin','n'),
	array(WIKI_PKG_NAME, 'wiki_feature_copyrights','n'),
	array(WIKI_PKG_NAME, 'wiki_forum',''),
	array(WIKI_PKG_NAME, 'wiki_forum_id',''),
	array(WIKI_PKG_NAME, 'wiki_left_column','y'),
	array(WIKI_PKG_NAME, 'wiki_list_backlinks','y'),
	array(WIKI_PKG_NAME, 'wiki_list_comment','y'),
	array(WIKI_PKG_NAME, 'wiki_list_creator','y'),
	array(WIKI_PKG_NAME, 'wiki_list_hits','y'),
	array(WIKI_PKG_NAME, 'wiki_list_lastmodif','y'),
	array(WIKI_PKG_NAME, 'wiki_list_lastver','y'),
	array(WIKI_PKG_NAME, 'wiki_list_links','y'),
	array(WIKI_PKG_NAME, 'wiki_list_name','y'),
	array(WIKI_PKG_NAME, 'wiki_list_size','y'),
	array(WIKI_PKG_NAME, 'wiki_list_status','y'),
	array(WIKI_PKG_NAME, 'wiki_list_user','y'),
	array(WIKI_PKG_NAME, 'wiki_list_versions','y'),
	array(WIKI_PKG_NAME, 'wiki_page_regex','strict'),
	array(WIKI_PKG_NAME, 'wiki_right_column','y'),
	array(WIKI_PKG_NAME, 'wiki_spellcheck','n'),
	array(WIKI_PKG_NAME, 'wiki_top_bar','n'),
	array(WIKI_PKG_NAME, 'wiki_uses_slides','n'),
	array(WIKI_PKG_NAME, 'wikibook_show_path','y'),
	array(WIKI_PKG_NAME, 'wikibook_show_navigation','y'),
	array(WIKI_PKG_NAME, 'wikiHomePage','Welcome'),
	array(WIKI_PKG_NAME, 'wikiLicensePage',''),
	array(WIKI_PKG_NAME, 'wikiSubmitNotice',''),
) );

?>
