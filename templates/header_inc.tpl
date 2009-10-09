{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/header_inc.tpl,v 1.9 2009/10/09 04:24:43 wjames5 Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $gBitSystem->isFeatureActive( 'wiki_rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'wiki' and $gBitUser->hasPermission( 'p_wiki_view_page' )}
<link rel="alternate" type="application/rss+xml" title="{$gBitSystem->getConfig('fisheye_rss_title',"{tr}Wiki{/tr} RSS")}" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version={$gBitSystem->getConfig('rssfeed_default_version',0)}{if $gBitSystem->getConfig( 'rssfeed_httpauth' ) && $gBitUser->isRegistered()}&httpauth=y{/if}" />
{/if}
{/strip}
