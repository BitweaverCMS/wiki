{strip}
{if $gBitSystem->isPackageActive( 'wiki' ) && $modLastModif}
	{bitmodule title="$moduleTitle" name="last_modif_pages"}
		<ol>
			{section name=ix loop=$modLastModif}
				<li>
					<a href="{$modLastModif[ix].display_url}" title="{$modLastModif[ix].title|escape} - {displayname user=$modLastModif[ix].modifier_user real_name=$modLastModif[ix].modifier_real_name nolink=1}, {$modLastModif[ix].last_modified|bit_short_date}">
						{if $maxlen gt 0}
							{$modLastModif[ix].title|escape|truncate:$maxlen:"...":true}
						{else}
							{$modLastModif[ix].title|escape}
						{/if}
					</a>
				</li>
			{sectionelse}
				<li></li>
			{/section}
		</ol>
		{if $gBitUser->hasPermission( 'p_wiki_list_pages') }
			<a class="more" href="{$smarty.const.WIKI_PKG_URL}list_pages.php?sort_mode=last_modified_desc">{tr}Show More{/tr}&hellip;</a>
		{/if}
	{/bitmodule}
{/if}
{/strip}
