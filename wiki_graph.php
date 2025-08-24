<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once '../kernel/includes/setup_inc.php';
use Bitweaver\Wiki\BitPage;
include_once WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php';
include_once UTIL_PKG_INCLUDE_PATH.'pear/Image/GraphViz.php';
$graph = new Image_GraphViz();

$params = [
	'graph' => $gBitThemes->getGraphvizGraphAttributes( $_REQUEST ),
	'node'  => $gBitThemes->getGraphvizNodeAttributes( $_REQUEST ),
	'edge'  => $gBitThemes->getGraphvizEdgeAttributes( $_REQUEST ),
];

$linkStructure = $gContent->getLinkStructure( $gContent->mPageName, !empty( $_REQUEST['level'] ) ? $_REQUEST['level'] : 0 );
$gContent->linkStructureGraph( $graph, $linkStructure, $params );
$graph->image( 'png' );
