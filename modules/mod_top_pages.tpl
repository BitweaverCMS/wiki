{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.tpl,v 1.7 2005/10/12 15:14:13 spiderr Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'wiki' )}
	{bitmodule title="$moduleTitle" name="top_pages"}
		<ol>
			{section name=ix loop=$modTopPages}
				<li><a href="{$modTopPages[ix].display_url}">{$modTopPages[ix].title}</a></li>
			{sectionelse}
				<li></li>
			{/section}
		</ol>
	{/bitmodule}
{/if}
{/strip}
