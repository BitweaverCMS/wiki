{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}index.php">{biticon ipackage=liberty iname=home iexplain="Wiki Home" iforce="icon"} {tr}Wiki Home{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_pages' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}list_pages.php">{biticon ipackage=liberty iname=list iexplain="List pages" iforce="icon"} {tr}List pages{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_edit_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php">{biticon ipackage=liberty iname=new iexplain="Create page" iforce="icon"} {tr}Create page{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_view_page' ) and $gBitSystem->isFeatureActive( 'wiki_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}books.php">{biticon ipackage=wiki iname=book iexplain="Wiki Books" iforce="icon"} {tr}Wiki Books{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_books' ) && $gBitUser->hasPermission( 'p_wiki_edit_book' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit_book.php">{biticon ipackage=liberty iname=new iexplain="Create a new book" iforce="icon"} {tr}Create book{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_pages' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}orphan_pages.php">{biticon ipackage=liberty iname=spacer iexplain="Orphan pages" iforce="icon"} {tr}Orphan pages{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_multiprint' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}print_pages.php">{biticon ipackage=liberty iname=print iexplain="Print" iforce="icon"} {tr}Print{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_rankings' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}rankings.php">{biticon ipackage=liberty iname=list iexplain="Wiki Rankings" iforce="icon"} {tr}Wiki Rankings{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_xmlrpc_send_content' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.XMLRPC_PKG_URL}send_objects.php">{biticon ipackage=liberty iname=spacer iexplain="Send pages" iforce="icon"} {tr}Send pages{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_xmlrpc_admin_content' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}received_pages.php">{biticon ipackage=liberty iname=spacer iexplain="Recieved pages" iforce="icon"} {tr}Received pages{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_dump' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.STORAGE_PKG_URL}dump/{$bitdomain}new.tar">{biticon ipackage=liberty iname=save iexplain="Backup" iforce="icon"} {tr}Backup{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_sandbox' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php?page=SandBox">{biticon ipackage=liberty iname=spacer iexplain="Sandbox" iforce="icon"} {tr}Sandbox{/tr}</a></li>
	{/if}
</ul>
{/strip}
