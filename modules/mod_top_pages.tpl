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
