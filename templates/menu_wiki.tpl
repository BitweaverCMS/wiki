{strip}
<ul>
	{if $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}index.php">{biticon ipackage=liberty iname=home iexplain="Wiki Home" iforce="icon"} {tr}Wiki Home{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_listPages' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}list_pages.php">{biticon ipackage=liberty iname=list iexplain="List pages" iforce="icon"} {tr}List pages{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'bit_p_edit' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php">{biticon ipackage=liberty iname=new iexplain="Create page" iforce="icon"} {tr}Create page{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'bit_p_view' ) and $gBitSystem->isFeatureActive( 'feature_wiki_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}books.php">{biticon ipackage=wiki iname=book iexplain="Wiki Books" iforce="icon"} {tr}Wiki Books{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_wiki_books' ) && $gBitUser->hasPermission( 'bit_p_edit_books' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit_book.php">{biticon ipackage=liberty iname=new iexplain="Create a new book" iforce="icon"} {tr}Create book{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_listPages' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}orphan_pages.php">{biticon ipackage=liberty iname=spacer iexplain="Orphan pages" iforce="icon"} {tr}Orphan pages{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_wiki_multiprint' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}print_pages.php">{biticon ipackage=liberty iname=print iexplain="Print" iforce="icon"} {tr}Print{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_wiki_rankings' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}rankings.php">{biticon ipackage=liberty iname=list iexplain="Wiki Rankings" iforce="icon"} {tr}Wiki Rankings{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'bit_p_send_pages' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.XMLRPC_PKG_URL}send_objects.php">{biticon ipackage=liberty iname=spacer iexplain="Send pages" iforce="icon"} {tr}Send pages{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'bit_p_admin_received_pages' ) and $gBitSystem->isFeatureActive( 'feature_comm' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}received_pages.php">{biticon ipackage=liberty iname=spacer iexplain="Recieved pages" iforce="icon"} {tr}Received pages{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_dump' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.STORAGE_PKG_URL}dump/{$bitdomain}new.tar">{biticon ipackage=liberty iname=save iexplain="Backup" iforce="icon"} {tr}Backup{/tr}</a></li>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_sandbox' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$smarty.const.WIKI_PKG_URL}edit.php?page=SandBox">{biticon ipackage=liberty iname=spacer iexplain="Sandbox" iforce="icon"} {tr}Sandbox{/tr}</a></li>
	{/if}
</ul>
{/strip}
