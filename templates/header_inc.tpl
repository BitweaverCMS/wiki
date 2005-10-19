{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/header_inc.tpl,v 1.1.2.1 2005/10/19 22:30:00 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'wiki' and $gBitUser->hasPermission( 'bit_p_view' )}
	<link rel="alternate" type="application/rss+xml" title="{$siteTitle} - wiki" href="{$smarty.const.WIKI_PKG_URL}wiki_rss.php" />
{/if}
{/strip}
