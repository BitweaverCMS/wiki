{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $gBitSystem->isFeatureActive( 'wiki_rss' ) and $gBitSystem->getActivePackage() eq 'wiki' and $gBitUser->hasPermission( 'p_wiki_view_page' )}
	<link rel="alternate" type="application/rss+xml"
		title="{$gBitSystem->getConfig('fisheye_rss_title',"Wiki RSS")}"
		href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version={$gBitSystem->getConfig('rssfeed_default_version',0)}
	{if $gBitSystem->getConfig( 'rssfeed_httpauth' ) and $gBitUser->isRegistered()} &httpauth=y{/if}" />
{/if}
{/strip}
