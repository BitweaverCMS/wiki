<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/wiki_rss.php,v 1.1.2.9 2006/06/11 01:57:12 wolff_borg Exp $
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

$rss->title = $gBitSystem->getPreference( 'title_rss_wiki', $gBitSystem->getPreference( 'siteTitle' ).' - '.tra( 'Wiki' ) );
$rss->description = $gBitSystem->getPreference( 'desc_rss_wiki', $gBitSystem->getPreference( 'siteTitle' ).' - '.tra( 'RSS Feed' ) );

// check permission to view wiki pages
if( !$gBitUser->hasPermission( 'bit_p_view' ) ) {
	require_once( RSS_PKG_PATH."rss_error.php" );
} else {
	// check if we want to use the cache file
	$cacheFile = TEMP_PKG_PATH.RSS_PKG_NAME.'/'.WIKI_PKG_NAME.'_'.$rss_version_name.'.xml';
	$rss->useCached( $rss_version_name, $cacheFile ); // use cached version if age < 1 hour

	$wiki = new BitPage();
	$feeds = $wiki->getList( 0, $gBitSystem->getPreference( 'max_rss_wiki', 10 ), 'last_modified_desc', NULL, NULL, FALSE, FALSE, TRUE );
	$feeds = $feeds['data'];

	// set the rss link
	$rss->link = WIKI_PKG_URI;

	// get all the data ready for the feed creator
	foreach( $feeds as $feed ) {
		$item = new FeedItem();
		$item->title = $feed['title'];
		$item->link = httpPrefix().$wiki->getDisplayUrl( $feed['title'] );
		$item->description = $wiki->parseData( $feed['data'], $feed['format_guid'] );

		$item->date = ( int )$feed['last_modified'];
		$item->source = WIKI_PKG_URI;
		$item->author = $gBitUser->getDisplayName( FALSE, array( 'real_name' => $feed['modifier_real_name'], 'login' => $feed['modifier_user'] ) );

		$item->descriptionTruncSize = $gBitSystem->getPreference( 'rssfeed_truncate', 5000 );
		$item->descriptionHtmlSyndicated = FALSE;

		// pass the item on to the rss feed creator
		$rss->addItem( $item );
	}

	// finally we are ready to serve the data
	echo $rss->saveFeed( $rss_version_name, $cacheFile );
}
?>
