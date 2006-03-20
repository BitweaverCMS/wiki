<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/wiki_graph.php,v 1.3 2006/03/20 15:58:38 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: wiki_graph.php,v 1.3 2006/03/20 15:58:38 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once( '../bit_setup_inc.php' );
include_once( UTIL_PKG_PATH.'GraphViz.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
if(!isset($_REQUEST['level'])) $_REQUEST['level'] = 0;
if(!isset($_REQUEST['nodesep'])) $_REQUEST['nodesep'] = ".1";
if(!isset($_REQUEST['rankdir'])) $_REQUEST['rankdir'] = "LR";
if(!isset($_REQUEST['bgcolor'])) $_REQUEST['bgcolor'] = "transparent";   # general background color #rrvvbb or 'transparent'
if(!isset($_REQUEST['size'])) $_REQUEST['size'] = "";                    # "x,y" in inches
if(!isset($_REQUEST['fontsize'])) $_REQUEST['fontsize'] = "9";
if(!isset($_REQUEST['fontname'])) $_REQUEST['fontname'] = "Helvetica";
if(!isset($_REQUEST['shape'])) $_REQUEST['shape'] = "box";            # plaintext ellipse circle egg triangle box diamond trapezium parallelogram house hexagon octagon
if(!isset($_REQUEST['nodestyle'])) $_REQUEST['nodestyle'] = "filled";
if(!isset($_REQUEST['nodecolor'])) $_REQUEST['nodecolor'] = "#aeaeae";
if(!isset($_REQUEST['nodefillcolor'])) $_REQUEST['nodefillcolor'] = "#FFFFFF";
if(!isset($_REQUEST['nodewidth'])) $_REQUEST['nodewidth'] = ".1";
if(!isset($_REQUEST['nodeheight'])) $_REQUEST['nodeheight'] = ".1";
if(!isset($_REQUEST['edgecolor'])) $_REQUEST['edgecolor'] = "#999999";
if(!isset($_REQUEST['edgestyle'])) $_REQUEST['edgestyle'] = "solid";
$garg = array(
	'att' => array(
		'level' => $_REQUEST['level'],
		'nodesep' => $_REQUEST['nodesep'],
		'rankdir' => $_REQUEST['rankdir'],
		'bgcolor' => $_REQUEST['bgcolor'],
		'size' => $_REQUEST['size']
	),
	'node' => array(
		'fontsize' => $_REQUEST['fontsize'],
		'fontname' => $_REQUEST['fontname'],
		'shape' => $_REQUEST['shape'],
		'style' => $_REQUEST['nodestyle'],
		'color' => $_REQUEST['nodecolor'],
		'fillcolor' => $_REQUEST['nodefillcolor'],
		'width' => $_REQUEST['nodewidth'],
		'height' => $_REQUEST['nodeheight']
	),
	'edge' => array(
		'color' => $_REQUEST['edgecolor'],
		'style' => $_REQUEST['edgestyle']
	)
);
$str = $wikilib->wiki_get_link_structure($_REQUEST['page'], $_REQUEST['level']);
$graph = new Image_GraphViz();
$wikilib->wiki_page_graph($str, $graph, $garg);
$graph->image( 'png' );
?>
