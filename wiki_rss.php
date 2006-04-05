<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/wiki_rss.php,v 1.11 2006/04/05 06:31:58 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * Initialization
 */
require_once( "../bit_setup_inc.php" );
require_once( RSS_PKG_PATH."rss_inc.php" );
require_once( WIKI_PKG_PATH."BitPage.php" );

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyPackage( 'rss' );

$rss->title = $gBitSystem->getConfig( 'title_rss_wiki', $gBitSystem->getConfig( 'site_title' ).' - '.tra( 'Wiki' ) );
$rss->description = $gBitSystem->getConfig( 'desc_rss_wiki', $gBitSystem->getConfig( 'site_title' ).' - '.tra( 'RSS Feed' ) );

// check permission to view wiki pages
if( !$gBitUser->hasPermission( 'bit_p_view' ) ) {
	require_once( RSS_PKG_PATH."rss_error.php" );
} else {
	// check if we want to use the cache file
	$cacheFile = TEMP_PKG_PATH.RSS_PKG_NAME.'/'.WIKI_PKG_NAME.'_'.$rss_version_name.'.xml';
	$rss->useCached( $rss_version_name, $cacheFile ); // use cached version if age < 1 hour

	$wiki = new BitPage();
	$feeds = $wiki->getList( 0, $gBitSystem->getConfig( 'max_rss_wiki', 10 ), 'last_modified_desc', NULL, NULL, FALSE, FALSE, TRUE );
	$feeds = $feeds['data'];

	// set the rss link
	$rss->link = 'http://'.$_SERVER['HTTP_HOST'].WIKI_PKG_URL;

	// get all the data ready for the feed creator
	foreach( $feeds as $feed ) {
		$item = new FeedItem();
		$item->title = $feed['title'];
		$item->link = BIT_BASE_URI.$wiki->getDisplayUrl( $feed['title'] );
		$item->description = $wiki->parseData( $feed );

		$item->date = ( int )$feed['last_modified'];
		$item->source = 'http://'.$_SERVER['HTTP_HOST'].BIT_ROOT_URL;
		$item->author = $gBitUser->getDisplayName( FALSE, array( 'real_name' => $feed['modifier_real_name'], 'login' => $feed['modifier_user'] ) );

		$item->descriptionTruncSize = $gBitSystem->getConfig( 'rssfeed_truncate', 5000 );
		$item->descriptionHtmlSyndicated = FALSE;

		// pass the item on to the rss feed creator
		$rss->addItem( $item );
	}

	// finally we are ready to serve the data
	echo $rss->saveFeed( $rss_version_name, $cacheFile );
}
?>
