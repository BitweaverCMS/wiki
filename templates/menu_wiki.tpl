{strip}
{if $packageMenuTitle}<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>{/if}
<ul class="{$packageMenuClass}">
	{if $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}index.php">{booticon iname="fa-house" iexplain="`$smarty.const.WIKI_PKG_DIR` Home" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}list_pages.php">{booticon iname="fa-list-ul" iexplain="List Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_create_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php">{booticon iname="fa-file" iexplain="Create Page" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_view_page' ) and $gBitSystem->isFeatureActive( 'wiki_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}books.php">{booticon iname="fa-book" iexplain="`$smarty.const.WIKI_PKG_DIR` Books" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_books' ) && $gBitUser->hasPermission( 'p_wiki_create_book' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit_book.php">{booticon iname="fa-files" iexplain="Create Book" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_orphans' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}orphan_pages.php">{booticon iname="fa-magnifying-glass" iexplain="Orphan Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_multiprint' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}print_pages.php">{booticon iname="fa-print"   iexplain="Print" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}rankings.php">{booticon iname="fa-list-ul" iexplain="`$smarty.const.WIKI_PKG_DIR` Rankings" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'rss' )}
	<li><a title="{tr}Wiki Update RSS Feed{/tr}" href="{$smarty.const.RSS_PKG_URL}index.php?pkg=wiki">{booticon iname="fa-rss" ipackage=rss iexplain="Changes Feed" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
