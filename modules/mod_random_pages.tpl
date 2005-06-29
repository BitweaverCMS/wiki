{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.tpl,v 1.1.1.1.2.1 2005/06/29 05:46:03 spiderr Exp $ *}
{strip}
{if $gBitSystemPrefs.package_wiki eq 'y'}
	{bitmodule title="$moduleTitle" name="random_pages"}
		<ol class="wiki">
			{foreach from=$modRandomPages key=contentId item=pageHash}
				<li><a href="{$modRandomPages.$contentId.display_url}">{$modRandomPages.$contentId.title}</a></li>
			{foreachelse}
				<li></li>
			{/foreach}
		</ol>
	{/bitmodule}
{/if}
{/strip}
