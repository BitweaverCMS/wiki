{strip}
<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="dropdown-menu">
	{if $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}index.php">{booticon iname="icon-home" iexplain=$pkgNameWS|cat:"Home" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}list_pages.php">{booticon iname="icon-list" iexplain="List Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_create_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php">{booticon iname="icon-file" iexplain="Create Page" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_view_page' ) and $gBitSystem->isFeatureActive( 'wiki_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}books.php">{booticon iname="icon-book" iexplain=$pkgNameWS|cat:"Books" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_books' ) && $gBitUser->hasPermission( 'p_wiki_create_book' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit_book.php">{booticon iname="icon-copy" iexplain="Create Book" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_orphans' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}orphan_pages.php">{booticon iname="icon-search" iexplain="Orphan Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_multiprint' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}print_pages.php">{booticon iname="icon-print"   iexplain="Print" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}rankings.php">{booticon iname="icon-list" iexplain=$pkgNameWS|cat:"Rankings" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive( 'rss' )}
	<li><a title="{tr}Wiki Update RSS Feed{/tr}" href="{$smarty.const.RSS_PKG_URL}index.php?pkg=wiki">{booticon iname="icon-rss" ipackage=rss iexplain="Changes Feed" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_sandbox' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php?page=SandBox">{booticon iname="icon-lightbulb" iexplain="Sandbox" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
