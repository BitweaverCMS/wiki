{if $lastComments}
	{if $nonums eq 'y'}
		{eval var="{tr}Last `$module_rows` wiki comments{/tr}" assign="tpl_module_title"}
	{else}
		{eval var="{tr}Last wiki comments{/tr}" assign="tpl_module_title"}
	{/if}
	{bitmodule title="$moduleTitle" name="wiki_last_comments"}
		<ul class="wiki">
			{section name=ix loop=$lastComments}
				<li class="{cycle values="odd,even"}">
					<a href="{$smarty.const.WIKI_PKG_URL}index.php?content_id={$lastComments[ix].content_id}" title="{$lastComments[ix].comment_date|bit_short_datetime}, by {displayname hash=$lastComments[ix] nolink=1}{if $moretooltips eq 'y'} on page {$lastComments[ix].page}{/if}">
						{if $moretooltips ne 'y'}
						<b>{$lastComments[ix].content_title}</b>:
						{/if} {$lastComments[ix].title|escape}
					</a>
				</li>
			{sectionelse}
				<li></li>
			{/section}
		</ul>
	{/bitmodule}
{/if}
