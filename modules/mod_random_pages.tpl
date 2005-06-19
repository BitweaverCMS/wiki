{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
{if $gBitSystemPrefs.package_wiki eq 'y'}
	{bitmodule title="$moduleTitle" name="random_pages"}
		<ol class="wiki">
			{section name=ix loop=$modRandomPages}
				<li><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page={$modRandomPages[ix]}">{$modRandomPages[ix]}</a></li>
			{sectionelse}
				<li></li>
			{/section}
		</ol>
	{/bitmodule}
{/if}
{/strip}