{strip}
{if $gBitSystem->isPackageActive( 'wiki' )}
	{bitmodule title="$moduleTitle" name="last_modif_pages"}
		<ol>
			{section name=ix loop=$modLastModif}
				<li>
					<a href="{$modLastModif[ix].display_url}" title="{$modLastModif[ix].title} - {displayname user=$modLastModif[ix].modifier_user real_name=$modLastModif[ix].modifier_real_name nolink=1}, {$modLastModif[ix].last_modified|bit_short_date}">
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
