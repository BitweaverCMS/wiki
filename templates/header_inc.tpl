{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/header_inc.tpl,v 1.3 2005/10/29 17:57:43 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'wiki' and $gBitUser->hasPermission( 'bit_p_view' )}
	<link rel="alternate" type="application/rss+xml" title="{tr}Wiki{/tr} RSS" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version=rss20" />
	<link rel="alternate" type="application/rss+xml" title="{tr}Wiki{/tr} ATOM" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php?version=atom" />
{/if}
{/strip}
