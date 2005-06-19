{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_search_wiki_page.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
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
{/strip}
