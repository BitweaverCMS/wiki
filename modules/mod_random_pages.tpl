{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_random_pages.tpl,v 1.2 2005/07/17 17:36:46 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'wiki' )}
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
