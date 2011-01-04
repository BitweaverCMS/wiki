{strip}
{if $gBitSystem->isPackageActive( 'wiki' ) && $modRandomPages}
	{bitmodule title="$moduleTitle" name="random_pages"}
		<ol class="wiki">
			{foreach from=$modRandomPages key=contentId item=pageHash}
				<li><a href="{$modRandomPages.$contentId.display_url}">{$modRandomPages.$contentId.title|escape}</a></li>
			{foreachelse}
				<li></li>
			{/foreach}
		</ol>
	{/bitmodule}
{/if}
{/strip}
