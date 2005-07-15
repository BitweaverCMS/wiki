{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_last_modif_pages.tpl,v 1.1.1.1.2.1 2005/07/15 12:01:29 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'wiki' )}
	{if $nonums eq 'y'}
		{eval var="{tr}Last `$module_rows` changes{/tr}" assign="tpl_module_title"}
	{else}
		{eval var="{tr}Last changes{/tr}" assign="tpl_module_title"}
	{/if}
	{bitmodule title="$moduleTitle" name="last_modif_pages"}
		<ol>
			{section name=ix loop=$modLastModif}
				<li>
					<a href="{$modLastModif[ix].display_url}" title="{$modLastModif[ix].title} - {$modLastModif[ix].last_modified|bit_short_datetime}, by {displayname user=$modLastModif[ix].modifier_user real_name=$modLastModif[ix].modifier_real_name nolink=1}{if (strlen($modLastModif[ix].title) > $maxlen) AND ($maxlen > 0)}, {$modLastModif[ix].title}{/if}">
						{if $maxlen gt 0}
							{$modLastModif[ix].title|truncate:$maxlen:"...":true}
						{else}
							{$modLastModif[ix].title}
						{/if}
					</a>
				</li>
			{sectionelse}
				<li></li>
			{/section}
		</ol>
	{/bitmodule}
{/if}
{/strip}
