<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/edit.php,v 1.1.1.1.2.23 2005/10/10 21:11:40 jht001 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: edit.php,v 1.1.1.1.2.23 2005/10/10 21:11:40 jht001 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( WIKI_PKG_PATH.'BitBook.php');
include_once( LIBERTY_PKG_PATH.'edit_help_inc.php' );

$gBitSystem->verifyPackage( 'wiki' );

include( WIKI_PKG_PATH.'lookup_page_inc.php' );

// Get plugins with descriptions
global $wikilib, $gLibertySystem;

#edit preview needs this
if (!isset($_REQUEST['title']) && isset($gContent->mInfo['title'])) {
	$_REQUEST['title'] = $gContent->mInfo['title'];
}

$sandbox = FALSE;
if ( (!empty($_REQUEST['page']) && $_REQUEST['page'] == 'SandBox') ||
	(!empty($_REQUEST['title']) && $_REQUEST['title'] == 'SandBox') ) {
	$gContent->mInfo['title'] = 'SandBox';
	$sandbox = TRUE;
}


if( $sandbox && !$gBitSystem->isFeatureActive( 'feature_sandbox' ) ) {
	$gBitSystem->fatalError( "The SandBox is disabled" );
} elseif( !$sandbox && !$gContent->hasUserPermission( 'bit_p_edit' ) ) {
	$gBitSystem->fatalError( 'Permission denied you cannot edit the page named "'.$gContent->getTitle().'"' );
}

if( $gContent->isLocked() ) {
	$gBitSystem->fatalError( 'Cannot edit page because it is locked' );
}

// see if we should show the attachments tab at all
foreach( $gLibertySystem->mPlugins as $plugin ) {
	if( ( $plugin['plugin_type'] == 'storage' ) && ( $plugin['is_active'] == 'y' ) ) {
		$gBitSmarty->assign( 'show_attachments','y' );
	}
}

function compare_import_versions($a1, $a2) {
	return $a1["version"] - $a2["version"];
}

/**
 * \brief Parsed HTML tree walker (used by HTML sucker)
 *
 * This is initial implementation (stupid... w/o any intellegence (almost :))
 * It is rapidly designed version... just for test: 'can this feature be useful'.
 * Later it should be replaced by well designed one :) don't bash me now :)
 *
 * \param &$c array -- parsed HTML
 * \param &$src string -- output string
 * \param &$p array -- ['stack'] = closing strings stack,
					   ['listack'] = stack of list types currently opened
					   ['first_td'] = flag: 'is <tr> was just before this <td>'
 */
function walk_and_parse(&$c, &$src, &$p)
{
	for ($i=0; $i <= $c["contentpos"]; $i++)
	{
		// If content type 'text' output it to destination...
		if ($c[$i]["type"] == "text") $src .= $c[$i]["data"];
		elseif ($c[$i]["type"] == "tag")
		{
			if ($c[$i]["data"]["type"] == "open")
			{
				// Open tag type
				switch ($c[$i]["data"]["name"])
				{
				case "br": $src .= "\n"; break;
				case "title": $src .= "\n!"; $p['stack'][] = array('tag' => 'title', 'string' => "\n"); break;
				case "p": $src .= "\n"; $p['stack'][] = array('tag' => 'p', 'string' => "\n"); break;
				case "b": $src .= '__'; $p['stack'][] = array('tag' => 'b', 'string' => '__'); break;
				case "i": $src .= "''"; $p['stack'][] = array('tag' => 'i', 'string' => "''"); break;
				case "u": $src .= "=="; $p['stack'][] = array('tag' => 'u', 'string' => "=="); break;
				case "center": $src .= '::'; $p['stack'][] = array('tag' => 'center', 'string' => '::'); break;
				case "code": $src .= '-+';  $p['stack'][] = array('tag' => 'code', 'string' => '+-'); break;
				// headers detection looks like real suxx code...
				// but possible it run faster :) I don't know where is profiler in PHP...
				case "h1": $src .= "\n!"; $p['stack'][] = array('tag' => 'h1', 'string' => "\n"); break;
				case "h2": $src .= "\n!!"; $p['stack'][] = array('tag' => 'h2', 'string' => "\n"); break;
				case "h3": $src .= "\n!!!"; $p['stack'][] = array('tag' => 'h3', 'string' => "\n"); break;
				case "h3": $src .= "\n!!!!"; $p['stack'][] = array('tag' => 'h4', 'string' => "\n"); break;
				case "h5": $src .= "\n!!!!!"; $p['stack'][] = array('tag' => 'h5', 'string' => "\n"); break;
				case "h6": $src .= "\n!!!!!!"; $p['stack'][] = array('tag' => 'h6', 'string' => "\n"); break;
				case "pre": $src .= '~pp~'; $p['stack'][] = array('tag' => 'pre', 'string' => '~/pp~'); break;
				// Table parser
				case "table": $src .= '||'; $p['stack'][] = array('tag' => 'table', 'string' => '||'); break;
				case "tr": $p['first_td'] = true; break;
				case "td": $src .= $p['first_td'] ? '' : '|'; $p['first_td'] = false; break;
				// Lists parser
				case "ul": $p['listack'][] = '*'; break;
				case "ol": $p['listack'][] = '#'; break;
				case "li":
					// Generate wiki list item according to current list depth.
					// (ensure '*/#' starts from begining of line)
					for ($l = ''; strlen($l) < count($p['listack']); $l .= end($p['listack']));
					$src .= "\n$l ";
					break;
				case "font":
					// If color attribute present in <font> tag
					if (isset($c[$i]["pars"]["color"]["value"]))
					{
						$src .= '~~'.$c[$i]["pars"]["color"]["value"].':';
						$p['stack'][] = array('tag' => 'font', 'string' => '~~');
					}
					break;
				case "img":
					// If src attribute present in <img> tag
					if (isset($c[$i]["pars"]["src"]["value"]))
						// Note what it produce (img) not {img}! Will fix this below...
						$src .= '(img src='.$c[$i]["pars"]["src"]["value"].')';
					break;
				case "a":
					// If href attribute present in <a> tag
					if (isset($c[$i]["pars"]["href"]["value"]))
					{
						$src .= '['.$c[$i]["pars"]["href"]["value"].'|';
						$p['stack'][] = array('tag' => 'a', 'string' => ']');
					}
					break;
				}
			}
			else
			{
				// This is close tag type. Is that smth we r waiting for?
				switch ($c[$i]["data"]["name"])
				{
				case "ul":
					if (end($p['listack']) == '*') array_pop($p['listack']);
					break;
				case "ol":
					if (end($p['listack']) == '#') array_pop($p['listack']);
					break;
				default:
					$e = end($p['stack']);
					if ($c[$i]["data"]["name"] == $e['tag'])
					{
						$src .= $e['string'];
						array_pop($p['stack']);
					}
					break;
				}
			}
		}
		// Recursive call on tags with content...
		if (isset($c[$i]["content"]))
		{
//			if (substr($src, -1) != " ") $src .= " ";
			walk_and_parse($c[$i]["content"], $src, $p);
		}
	}
}
if( isset( $_REQUEST["suck_url"] ) ) {
	// Suck another page and append to the end of current
	require_once( UTIL_PKG_PATH.'htmlparser/html_parser_inc.php' );
	$suck_url = isset($_REQUEST["suck_url"]) ? $_REQUEST["suck_url"] : '';
	$parsehtml = isset ($_REQUEST["parsehtml"]) ? ($_REQUEST["parsehtml"] == 'on' ? 'y' : 'n')  : 'n';
	if (isset($_REQUEST['do_suck']) && strlen($suck_url) > 0)
	{
		// \note by zaufi
		//   This is ugly implementation of wiki HTML import.
		//   I think it should be plugable import/export converters with ability
		//   to choose from edit form what converter to use for operation.
		//   In case of import converter, it can try to guess what source
		//   file is (using mime type from remote server response).
		//   Of couse converters may have itsown configuration panel what should be
		//   pluged into wiki page edit form too... (like HTML importer may have
		//   flags 'strip HTML tags' and 'try to convert HTML to wiki' :)
		//   At least one export filter for wiki already coded :) -- PDF exporter...
		$sdta = @file_get_contents($suck_url);
		if (isset($php_errormsg) && strlen($php_errormsg))
		{
			$gBitSystem->fatalError( 'Can\'t import remote HTML page' );
		}
		// Need to parse HTML?
		if ($parsehtml == 'y')
		{
			// Read compiled (serialized) grammar
			$grammarfile = HTML_PKG_PATH.'htmlgrammar.cmp';
			if (!$fp = @fopen($grammarfile,'r'))
			{
				$gBitSystem->fatalError( 'Can\'t parse remote HTML page' );
			}
			$grammar = unserialize(fread($fp, filesize($grammarfile)));
			fclose($fp);
			// create parser object, insert html code and parse it
			$htmlparser = new HtmlParser($sdta, $grammar, '', 0);
			$htmlparser->Parse();
			// Should I try to convert HTML to wiki?
			$parseddata = '';
			$p =  array('stack' => array(), 'listack' => array(), 'first_td' => false);
			walk_and_parse($htmlparser->content, $parseddata, $p);
			// Is some tags still opened? (It can be if HTML not valid, but this is not reason
			// to produce invalid wiki :)
			while (count($p['stack']))
			{
				$e = end($p['stack']);
				$sdta .= $e['string'];
				array_pop($p['stack']);
			}
			// Unclosed lists r ignored... wiki have no special start/end lists syntax....
			// OK. Things remains to do:
			// 1) fix linked images
			$parseddata = preg_replace(',\[(.*)\|\(img src=(.*)\)\],mU','{img src=$2 link=$1}', $parseddata);
			// 2) fix remains images (not in links)
			$parseddata = preg_replace(',\(img src=(.*)\),mU','{img src=$1}', $parseddata);
			// 3) remove empty lines
			$parseddata = preg_replace(",[\n]+,mU","\n", $parseddata);
			// Reassign previous data
			$sdta = $parseddata;
		}
		$_REQUEST['edit'] .= $sdta;
	}
}
//

//include_once( WIKI_PKG_PATH.'page_setup_inc.php' );
// Now check permissions to access this page

if(isset($gContent->mInfo['wiki_cache']) && $gContent->mInfo['wiki_cache']!=0) {
  $wiki_cache = $gContent->mInfo['wiki_cache'];
  $gBitSmarty->assign('wiki_cache',$wiki_cache);
}

if( !empty( $gContent->mInfo ) ) {
	$formInfo = $gContent->mInfo;
	$formInfo['edit'] = !empty( $gContent->mInfo['data'] ) ? $gContent->mInfo['data'] : '';
	$formInfo['comment'] = '';
}

$gBitSmarty->assign('footnote', '');
$gBitSmarty->assign('has_footnote', 'n');
if ($gBitSystem->isFeatureActive( 'feature_wiki_footnotes' ) ) {
	if( $gBitUser->mUserId ) {
		$footnote = $gContent->getFootnote( $gBitUser->mUserId );
		$gBitSmarty->assign('footnote', $footnote);
		if ($footnote)
			$gBitSmarty->assign('has_footnote', 'y');
		$gBitSmarty->assign('parsed_footnote', $wikilib->parseData($footnote));
		if (isset($_REQUEST['footnote'])) {

			$gBitSmarty->assign('parsed_footnote', $wikilib->parseData($_REQUEST['footnote']));
			$gBitSmarty->assign('footnote', $_REQUEST['footnote']);
			$gBitSmarty->assign('has_footnote', 'y');
			if( empty( $_REQUEST['footnote'] ) ) {
				$gContent->expungeFootnote( $gBitUser->mUserId );
			} else {
				$gContent->storeFootnote( $gBitUser->mUserId, $_REQUEST['footnote'] );
			}
		}
	}
}
if (isset($_REQUEST["template_id"]) && $_REQUEST["template_id"] > 0) {
	$template_data = $wikilib->get_template($_REQUEST["template_id"]);
	$_REQUEST["edit"] = $template_data["content"];
	$_REQUEST["preview"] = 1;
}

if(isset($_REQUEST["edit"])) {
	$formInfo['edit'] = $_REQUEST["edit"];
}
if(isset($_REQUEST['title'])) {
	$formInfo['title'] = $_REQUEST['title'];
}
if(isset($_REQUEST["description"])) {
	$formInfo['description'] = $_REQUEST["description"];
}
if (isset($_REQUEST["comment"])) {
	$formInfo['comment'] = $_REQUEST["comment"];
}
else {
	# comment needs to get set to null when page is updated and user didn't supply a comment
	# but if comment is a null value then the comment column in the page table row is not updated
	# and retains its now incorrect value
	# so set to blank
	# there must be a better way to fix this...
	$formInfo['comment'] = ' ';
	}

$cat_obj_type = BITPAGE_CONTENT_TYPE_GUID;

if( $gBitSystem->isFeatureActive( 'wiki_feature_copyrights' ) ) {
	if (isset($_REQUEST['copyrightTitle'])) {
		$gBitSmarty->assign('copyrightTitle', $_REQUEST["copyrightTitle"]);
	}
	if (isset($_REQUEST['copyrightYear'])) {
		$gBitSmarty->assign('copyrightYear', $_REQUEST["copyrightYear"]);
	}
	if (isset($_REQUEST['copyrightAuthors'])) {
		$gBitSmarty->assign('copyrightAuthors', $_REQUEST["copyrightAuthors"]);
	}
}


function htmldecode($string) {
   $string = strtr($string, array_flip(get_html_translation_table(HTML_ENTITIES)));
   $string = preg_replace("/&#([0-9]+);/me", "chr('\\1')", $string);
   return $string;
}
function parse_output(&$obj, &$parts,$i) {
	if( !empty( $obj->parts ) ) {
		for($i=0; $i<count($obj->parts); $i++) {
			parse_output($obj->parts[$i], $parts,$i);
		}
	} else {
		$ctype = $obj->ctype_primary.'/'.$obj->ctype_secondary;
		switch($ctype) {
			case 'application/x-tikiwiki':
				$aux["body"] = $obj->body;
				$ccc=$obj->headers["content-type"];
				$items = split(';',$ccc);
				foreach($items as $item) {
					$portions = split('=',$item);
					if(isset($portions[0])&&isset($portions[1])) {
						$aux[trim($portions[0])]=trim($portions[1]);
					}
				}
				$parts[]=$aux;
		}
	}
}

// Pro
// Check if the page has changed
if (isset($_REQUEST["fCancel"])) {
	if( !empty( $gContent->mContentId ) ) {
		header("Location: ".$gContent->getDisplayUrl() );
	} else {
		header("Location: ".WIKI_PKG_URL );
	}
	die;
} elseif (isset($_REQUEST["fSavePage"])) {

	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	// add permisions here otherwise return error!
	if( $gBitSystem->isFeatureActive( 'wiki_feature_copyrights' )
		&& isset($_REQUEST['copyrightAuthors'])
		&& !empty($_REQUEST['copyrightYear'])
		&& !empty($_REQUEST['copyrightTitle'])
	) {
		require_once( WIKI_PKG_PATH.'copyrights_lib.php' );
		$copyrightYear = $_REQUEST['copyrightYear'];
		$copyrightTitle = $_REQUEST['copyrightTitle'];
		$copyrightAuthors = $_REQUEST['copyrightAuthors'];
		$copyrightslib->add_copyright( $gContent->mPageId, $copyrightTitle, $copyrightYear, $copyrightAuthors, $gBitUser->mUserId );
	}
	// Parse $edit and eliminate image references to external URIs (make them internal)
	if ( $gBitSystem->isPackageActive( 'imagegals' ) ) {
		include_once( IMAGEGALS_PKG_PATH.'imagegal_lib.php' );
		$edit = $imagegallib->capture_images($edit);
	}

	if ( $gContent->mPageId )
	{	if(isset($_REQUEST['isminor'])&&$_REQUEST['isminor']=='on') {
			$_REQUEST['minor']=true;
		} else {
			$_REQUEST['minor']=false;
//			$links = $gContent->get_links($edit);
//			$wikilib->cache_links($links);
//			$gContent->storeLinks($links);
		}
	} else {
//		$links = $gContent->get_links($_REQUEST["edit"]);
//		$notcachedlinks = $gContent->get_links_nocache($_REQUEST["edit"]);
//		$cachedlinks = array_diff($links, $notcachedlinks);
//		$gContent->cache_links($cachedlinks);
//		$gContent->storeLinks($cachedlinks);
	}

	if( $gContent->store( $_REQUEST ) ) {
		if ( $gBitSystem->isFeatureActive( 'wiki_watch_author' ) ) {
			$gBitUser->storeWatch( "wiki_page_changed", $gContent->mPageId, $gContent->mContentTypeGuid, $_REQUEST['title'], $gContent->getDisplayUrl() );
		}

		header("Location: ".$gContent->getDisplayUrl() );
	} else {
		$formInfo = $_REQUEST;
		$formInfo['data'] = &$_REQUEST['edit'];
	}
} elseif( !empty( $_REQUEST['edit'] ) ) {
	// perhaps we have a javascript non-saving form submit
	$formInfo = $_REQUEST;
	$formInfo['data'] = &$_REQUEST['edit'];
}
if ($gBitSystem->isFeatureActive( 'feature_wiki_templates' ) && $gBitUser->hasPermission( 'bit_p_use_content_templates' )) {
	$templates = $wikilib->list_templates('wiki', 0, -1, 'name_asc', '');
}
$gBitSmarty->assign_by_ref('templates', $templates["data"]);

if(isset($_REQUEST["preview"])) {
	$gBitSmarty->assign('preview',1);
	$gBitSmarty->assign('title',!empty($_REQUEST["title"])?$_REQUEST["title"]:$gContent->mPageName);

	$parsed = $gContent->parseData($formInfo['edit'], (!empty( $_REQUEST['format_guid'] ) ? $_REQUEST['format_guid'] :
		( isset($gContent->mInfo['format_guid']) ? $gContent->mInfo['format_guid'] : 'tikiwiki' ) ) );
	/* SPELLCHECKING INITIAL ATTEMPT */
	//This nice function does all the job!
	if ($wiki_spellcheck == 'y') {
		if (isset($_REQUEST["spellcheck"]) && $_REQUEST["spellcheck"] == 'on') {
			$parsed = $gBitSystem->spellcheckreplace($edit_data, $parsed, $gBitLanguage->mLanguage, 'editwiki');
			$gBitSmarty->assign('spellcheck', 'y');
		} else {
			$gBitSmarty->assign('spellcheck', 'n');
		}
	}
	$gBitSmarty->assign_by_ref('parsed', $parsed);

	$gContent->invokeServices( 'content_preview_function' );
} else {
	$gContent->invokeServices( 'content_edit_function' );
}

// Configure quicktags list
if ($gBitSystem->isPackageActive( 'quicktags' ) ) {
	include_once( QUICKTAGS_PKG_PATH.'quicktags_inc.php' );
}

if ($gBitSystem->isFeatureActive( 'feature_theme_control' ) ) {
	include( THEMES_PKG_PATH.'tc_inc.php' );
}

if( $gContent->isInStructure() ) {
	$gBitSmarty->assign('showstructs', $gContent->getStructures() );
}

// Flag for 'page bar' that currently 'Edit' mode active
// so no need to show comments & attachments, but need
// to show 'wiki quick help'
$gBitSmarty->assign('edit_page', 'y');

// WYSIWYG and Quicktag variable
$gBitSmarty->assign( 'textarea_id', 'editwiki' );

// formInfo might be set due to a error on submit
if( empty( $formInfo ) ) {
	$formInfo = &$gContent->mInfo;
}

// make original page title available for template
$formInfo['original_title'] = (!empty($gContent->mInfo['title'])) ? $gContent->mInfo['title']  : "" ;

$gBitSmarty->assign_by_ref( 'pageInfo', $formInfo );
$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
$gBitSmarty->assign( (!empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'body').'TabSelect', 'tdefault' );
$gBitSmarty->assign('show_page_bar', 'y');

$gBitSystem->display( 'bitpackage:wiki/edit_page.tpl', 'Edit: '.$gContent->getTitle() );
?>
