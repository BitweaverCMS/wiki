{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.tpl,v 1.9 2007/01/17 20:16:24 spiderr Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'wiki' ) && $modTopPages}
	{bitmodule title="$moduleTitle" name="top_pages"}
		<ol>
			{section name=ix loop=$modTopPages}
				<li><a href="{$modTopPages[ix].display_url}">{$modTopPages[ix].title|escape}</a></li>
			{sectionelse}
				<li></li>
			{/section}
		</ol>
	{/bitmodule}
{/if}
{/strip}
