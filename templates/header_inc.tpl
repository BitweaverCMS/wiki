{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/header_inc.tpl,v 1.6 2006/05/04 19:10:07 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $gBitSystem->isFeatureActive( 'wiki_rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'wiki' and $gBitUser->hasPermission( 'p_wiki_view_page' )}
	<link rel="alternate" type="application/rss+xml" title="{tr}Wiki{/tr} RSS" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version=rss20" />
	<link rel="alternate" type="application/rss+xml" title="{tr}Wiki{/tr} ATOM" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version=atom" />
{/if}
{/strip}
