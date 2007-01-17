{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_search_wiki_page.tpl,v 1.2 2007/01/17 20:16:24 spiderr Exp $ *}
{strip}
{if $gBitUser->hasPermission('p_wiki_view_page')}
{bitmodule title="$moduleTitle" name="search_box"}
	{form ipackage=wiki ifile="list_pages.php"}
		<div class="row">
			<input name="find" size="14" type="text" accesskey="s" value="{$find}" />
		</div>
		<div class="row submit">
			<input type="submit" class="wikiaction" name="search" value="{tr}go{/tr}" />
		</div>
	{/form}
{/bitmodule}
{/if}
{/strip}
