<?php
/**
 * @version  $Revision: 1.1 $
 * @package  liberty
 * @subpackage plugins_data
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2004, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See copyright.txt for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Author (TikiWiki): Unknown
// | Reworked & Undoubtedly Screwed-Up for (Bitweaver)
// | by: StarRider <starrrider@sourceforge.net>
// | Reworked from: wikiplugin_wikilist.php - see deprecated code below
// +----------------------------------------------------------------------+
// $Id: data.wikilist.php,v 1.1 2008/07/08 07:04:18 squareing Exp $

/**
 * definitions
 */
define( 'PLUGIN_GUID_DATAWIKILIST', 'datawikilist' );
global $gLibertySystem;
$pluginParams = array (
	'tag' => 'WIKILIST',
	'auto_activate' => FALSE,
	'requires_pair' => TRUE,
	'load_function' => 'data_wikilist',
	'title' => 'WikiList - This plugin is not yet functional.', // Remove this line when the plugin becomes operational
//	'title' => 'WikiList',                                                                             // and Remove the comment from the start of this line
	'help_page' => 'DataPluginWikiList',
	'description' => tra("Displays an alphabetically sorted list of WikiPages"),
	'help_function' => 'data_wikilist_help',
	'syntax' => "{WIKILIST num= alpha= total= list= }Group Name{WIKILIST} ",
	'path' => LIBERTY_PKG_PATH.'plugins/data.wikilist.php',
	'security' => 'registered',
	'plugin_type' => DATA_PLUGIN
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_DATAWIKILIST, $pluginParams );
$gLibertySystem->registerDataTag( $pluginParams['tag'], PLUGIN_GUID_DATAWIKILIST );

// Help Function
function data_wikilist_help() {
		$help = '
		<table class="plugin help">
			<tr>
				<th>'.tra( 'key' ).'</th>
				<th>'.tra( 'type' ).'</th>
				<th>'.tra( 'comments' ).'</th>
			</tr>
			<tr class="odd">
				<td>num</td>
				<td>'.tra( '0 or 1' ).'</td>
				<td>'.tra( 'Adds numbering to the list. Default = 0 (no numbering).' ).'</td>
			</tr>
			<tr class="even">
				<td>alpha</td>
				<td>'.tra( '0 or 1' ).'</td>
				<td>'.tra( 'Sorts names alphabetically and groups them by the beginning letter. Default = 1 (sorting and grouping are active).' ).'</td>
			</tr>
			<tr class="odd">
				<td>list</td>
				<td>'.tra( 'all / userpages / wiki' ).'</td>
				<td>'.tra( 'Defines the type of pages to be shown. Wiki and user pages will only show those type of pages. Default = all.' ).'</td>
			</tr>
			<tr class="odd">
				<td>total</td>
				<td>'.tra( '0 or 1' ).'</td>
				<td>'.tra( 'Shows total number of users in list at the end. Default = On (1)' ).'</td>
			</tr>
			<tr class="odd">
				<td>GroupName</td>
				<td>'.tra( 'not a parameter' ).'</td>
				<td>'.tra( 'Given between {WIKILIST} blocks. If no GroupName is given, All Users is assumed.' ).'</td>
			</tr>
		</table>
		Example: {WIKILIST}';
	return $help;
}

// Load Function
function data_wikilist($data, $params) {
	$ret = "This plugin has not been completed as yet. ";
	return $ret;
}
/******************************************************************************
The code below is from the deprecated WIKILIST plugin. All comments and the help routines have been removed. - StarRider

// Displays an alphabetically sorted list of WikiPages
// Use:
// {WIKILIST(num=>1,alpha=>1,total=>1,list=>all)}{WIKILIST}
// num=>1		--> writes a number in front of every name							default = 0
// alpha=>1		--> shows names in groups of beginning letters						default = 1
// list=>																			default = all
//		all			--> shows the wiki list and appends the userPage list
//		userpages	--> shows all userPages without showing the wiki list
//		wiki		--> shows the wiki list without showing the userpages
// total=>1		--> shows total number of users in list at the end					default = 1
//
// If no groupname is given, plugin returns all users


// function used to sort an array - NOT case-sensitive
function wikiplugin_compare_wikipages($a, $b) {
	return strcmp(strtolower($a), strtolower($b));
}

function wikiplugin_wikilist($data, $params) {
	global $gBitSystem;
	global $hotwords;
	// turn off $hotwords to avoid conflicts
	$hotwords = 'n';

	extract ($params, EXTR_SKIP);
	if(!isset($alpha))		{ $alpha = 1; }
	if(!isset($userpages))	{ $userpages = "all"; }
	if(!isset($num))		{ $num = 0; }
	if(!isset($total))		{ $total = 1; }

	$ret = "";
	$pagedata = $gBitSystem->list_pages(0);

	foreach ($pagedata['data'] as $pagedata_temp) {
		$wiki_pages_all[] = $pagedata_temp['pageName'];
	}

	// sort the pages
	usort($wiki_pages_all, "wikiplugin_compare_wikipages");

	// sort the userpages from the rest of the wikipages
	foreach ($wiki_pages_all as $pagename) {
		if(strstr($pagename,"userPage") == false){
			$wiki_pages[] = $pagename;
		}
		else {
			$user_pages[] = $pagename;
		}
	}

	if ($list != "userpages") {
		$wp_list_count = 0;
		foreach ($wiki_pages as $pagename) {
			if ($wp_list_count >= 1) {
				$prev_pagename = $wiki_pages[$wp_list_count-1];
			}
			else {
				$prev_pagename = 0;
			}
			$wp_list_count++;

			if ($alpha != 0) {
				if (strtolower($prev_pagename[0]) != strtolower($pagename[0])) {
					$ret .= ("-=".strtoupper($pagename[0])."=-\n");
				}
			}

			if ($num != 0) {
				$ret .= ($wp_list_count." ");
			}

			$ret .= ("((".$pagename."))\n");
		}

		if ($total != 0) {
			$ret .= ("<br />".tra("Total").": ".$wp_list_count."\n");
		}
	}

	if ($list != "wiki") {
		$ret .= ("-=userPages=-\n");

		$wp_list_count = 0;
		foreach ($user_pages as $user_title) {
			$wp_list_count++;
			if ($num != 0) {
				$ret .= ($wp_list_count." ");
			}

			$ret .= ("((".$user_title."))\n");
		}

		if ($total != 0) {
			$ret .= ("<br />".tra("Total").": ".$wp_list_count."\n");
		}
	}

	return $ret;
}
*/
?>
