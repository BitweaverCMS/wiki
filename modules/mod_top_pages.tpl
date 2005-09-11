{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_top_pages.tpl,v 1.1.1.1.2.5 2005/09/11 08:43:32 squareing Exp $ *}
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
