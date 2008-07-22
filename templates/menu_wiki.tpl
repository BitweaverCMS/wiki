{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}index.php">{biticon iname="go-home" iexplain="Wiki Home" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_pages' ) and $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}list_pages.php">{biticon iname="format-justify-fill" iexplain="List Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_edit_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php">{biticon iname="document-new" iexplain="Create Page" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_wiki_view_page' ) and $gBitSystem->isFeatureActive( 'wiki_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}books.php">{biticon iname="x-office-address-book" iexplain="Wiki Books" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_books' ) && $gBitUser->hasPermission( 'p_wiki_edit_book' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit_book.php">{biticon iname="folder-new" iexplain="Create Book" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_list_orphans' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}orphan_pages.php">{biticon iname=go-bottom iexplain="Orphan Pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_multiprint' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}print_pages.php">{biticon iname="document-print" iexplain="Print" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_rankings' ) and $gBitUser->hasPermission( 'p_wiki_list_pages' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}rankings.php">{biticon iname="format-justify-fill" iexplain="Wiki Rankings" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_sandbox' ) and $gBitUser->hasPermission( 'p_wiki_view_page' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php?page=SandBox">{biticon iname=user-trash-full iexplain="Sandbox" ilocation=menu}</a></li>
	{/if}

	{*if $gBitUser->hasPermission( 'p_xmlrpc_send_content' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.XMLRPC_PKG_URL}send_objects.php">{biticon ipackage=liberty iname=spacer iexplain="Send pages" ilocation=menu}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_xmlrpc_admin_content' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}received_pages.php">{biticon ipackage=liberty iname=spacer iexplain="Recieved pages" ilocation=menu}</a></li>
	{/if*}
</ul>
{/strip}
