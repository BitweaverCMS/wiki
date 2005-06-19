{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
{if $gBitSystemPrefs.package_wiki eq 'y'}
	{if $nonums eq 'y'}
		{eval var="{tr}Top `$module_rows` Pages{/tr}" assign="tpl_module_title"}
	{else}
		{eval var="{tr}Top Pages{/tr}" assign="tpl_module_title"}
	{/if}
	{bitmodule title="$moduleTitle" name="top_pages"}
		<ol class="wiki">
			{section name=ix loop=$modTopPages}
				<li><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page={$modTopPages[ix].page_name}">{$modTopPages[ix].page_name}</a></li>
			{sectionelse}
				<li></li>
			{/section}
		<ol>
	{/bitmodule}
{/if}
{/strip}