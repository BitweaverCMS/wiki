<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/wiki_rss.php,v 1.1.2.3 2005/10/30 21:03:50 lsces Exp $
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

$rss->title = $gBitSystem->getPreference( 'title_rss_wiki', $gBitSystem->mPrefs['siteTitle'].' - '.tra( 'Wiki' ) );
$rss->description = $gBitSystem->getPreference( 'desc_rss_wiki', $gBitSystem->mPrefs['siteTitle'].' - '.tra( 'RSS Feed' ) );

// check permission to view wiki pages
if( !$gBitUser->hasPermission( 'bit_p_view' ) ) {
	require_once( RSS_PKG_PATH."rss_error.php" );
} else {
	// check if we want to use the cache file
	$cacheFile = TEMP_PKG_PATH.RSS_PKG_NAME.'/'.WIKI_PKG_NAME.'_'.$version.'.xml';
	$rss->useCached( $cacheFile ); // use cached version if age < 1 hour

	$wiki = new BitPage();
	$feeds = $wiki->getList( 0, $gBitSystem->getPreference( 'max_rss_wiki', 10 ), 'last_modified_desc', NULL, NULL, FALSE, FALSE, TRUE );
	$feeds = $feeds['data'];

	// get all the data ready for the feed creator
	foreach( $feeds as $feed ) {
		$item = new FeedItem();
		$item->title = $feed['title'];
		$item->link = BIT_BASE_URI.$wiki->getDisplayUrl( $feed['title'] );
		$item->description = $wiki->parseData( $feed['data'], $feed['format_guid'] );

		$item->date = ( int )$feed['last_modified'];
		$item->source = 'http://'.$_SERVER['HTTP_HOST'].BIT_ROOT_URL;
		$item->author = $gBitUser->getDisplayName( FALSE, array( 'real_name' => $feed['modifier_real_name'], 'login' => $feed['modifier_user'] ) );

		$item->descriptionTruncSize = $gBitSystem->getPreference( 'rssfeed_truncate', 1000 );
		$item->descriptionHtmlSyndicated = FALSE;

		// pass the item on to the rss feed creator
		$rss->addItem( $item );
	}

	// finally we are ready to serve the data
	echo $rss->saveFeed( $rss_version_name, $cacheFile );
}
?>
