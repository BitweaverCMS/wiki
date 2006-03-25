{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.tpl,v 1.8 2006/03/25 20:55:08 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'wiki' )}
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
