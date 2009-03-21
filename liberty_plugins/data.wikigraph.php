<?php
/**
 * @version  $Revision: 1.5 $
 * @package  liberty
 * @subpackage plugins_data
 */
/**
 * definitions
 */
global $gBitSystem, $gLibertySystem;

// only include this plugin if wiki is active and we have GraphViz
if( @include_once( 'Image/GraphViz.php' )) {

define( 'PLUGIN_GUID_DATAWIKIGRAPH', 'datawikigraph' );
$pluginParams = array (
	'tag'           => 'wikigraph',
	'auto_activate' => FALSE,
	'requires_pair' => TRUE,
	'load_function' => 'data_wikigraph',
	'title'         => 'WikiGraph',
	'help_page'     => 'DataPluginWikiGraph',
	'description'   => tra( "Inserts a graph for visual navigation. The graph shows the page and every page that can be reached from that page. It requies the Image_GraphViz pear plugin and graphviz to be installed: <strong>pear install Image_GraphViz</strong>" ),
	'help_function' => 'data_wikigraph_help',
	'syntax'        => "{wikigraph level= title= }".tra( "Wiki page name" )."{/wikigraph}",
	'plugin_type'   => DATA_PLUGIN
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_DATAWIKIGRAPH, $pluginParams );
$gLibertySystem->registerDataTag( $pluginParams['tag'], PLUGIN_GUID_DATAWIKIGRAPH );

function data_wikigraph_help() {
	$help =
		'<table class="data help">'
			.'<tr>'
				.'<th>'.tra( "Key" ).'</th>'
				.'<th>'.tra( "Type" ).'</th>'
				.'<th>'.tra( "Comments" ).'</th>'
			.'</tr>'
			.'<tr class="odd">'
				.'<td>level</td>'
				.'<td>'.tra( "numeric").'<br />'.tra( "(optional)" ).'</td>'
				.'<td>'.tra( "The number of levels that will be followed from the starting page." ).' '.tra( "Default").': 0</td>'
			.'</tr>'
			.'<tr class="even">'
				.'<td>title</td>'
				.'<td>'.tra( "string").'<br />'.tra( "(optional)" ).'</td>'
				.'<td>'.tra( "Title of the graph.").' '.tra( "Default ").': Wiki-Graph</td>'
			.'</tr>'
			.'<tr class="odd">'
				.'<td>nodesep</td>'
				.'<td>'.tra( "numeric").'<br />'.tra( "(optional)" ).'</td>'
				.'<td>'.tra( "Distance between nodes in inches.").' '.tra( "Default").': 1.2</td>'
			.'</tr>'
			.'<tr class="even">'
				.'<td>rankdir</td>'
				.'<td>'.tra( "string").'<br />'.tra( "(optional)" ).'</td>'
				.'<td>'.tra( "Direction of graph layout - can be Left to Right (LR), Right to Left (RL), Top to Bottom (TB), Bottom to Top (BT).").' '.tra( "Default").': TB</td>'
			.'</tr>'
			.'<tr class="odd">'
				.'<td>bgcolor</td>'
				.'<td>'.tra( "html colour").'<br />'.tra( "(optional)" ).'</td>'
				.'<td>'.tra( "Background colour of the graph.").' '.tra( "Default").': transparent</td>'
			.'</tr>'
			.'</table>'
		.tra( "Example: " )."{wikigraph level=1}Welcome{/wikigraph}";
	return $help;
}

function data_wikigraph( $pData, $pParams ) {
	global $gContent, $gBitThemes;
	$ret = " ";

	// check to see if we have pear available.
	if( $error = pear_check( "Image/GraphViz.php" )) {
		return $error;
	}

	if( !empty( $gContent ) && is_object( $gContent )) {
		$querystring = "";

		$title = ( !empty( $pParams['title'] ) ? $pParams['title'] : 'Wiki-Graph' );
		unset( $pParams['title'] );

		foreach( $pParams as $param => $value ) {
			$querystring .= "&amp;{$param}={$value}";
		}

		if( empty( $pData ) ) {
			$pData = (( is_object( $gContent ) || !empty( $gContent->mPageName )) ? $gContent->mPageName : NULL );
		}

		if( !empty( $pData ) ) {
			$params = array(
				'graph' => $gBitThemes->getGraphvizGraphAttributes( $pParams ),
				'node'  => $gBitThemes->getGraphvizNodeAttributes( $pParams ),
				'edge'  => $gBitThemes->getGraphvizEdgeAttributes( $pParams ),
			);

			$mapname = md5( microtime() );
			$mapdata = $gContent->linkStructureMap( $pData, ( isset( $pParams['level'] ) ? $pParams['level'] : 0 ), $params );

			$ret = "
				<div align='center'>
				<img border='0' src=\"".WIKI_PKG_URL."wiki_graph.php?page=".urlencode( $pData )."{$querystring}\" alt='{$title}' usemap='#$mapname' />
				<map name='$mapname'>$mapdata</map>
				</div>";
			$ret = preg_replace( "/\n|\r/", '', $ret );
		}
	}
	return $ret;
}

} // graphviz check
?>
