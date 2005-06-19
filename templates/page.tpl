{if $type eq 'd'}
	<iframe width="0" height="0" border="0" src="{$gBitLoc.WIKI_PKG_URL}page_loader.php?refresh={$refresh}&amp;title={$title|escape:"url"}">Browser not supported</iframe>
{/if}
{$parsed}

{if $gBitUser->hasPermission( 'bit_p_edit_html_pages' )}
	<a href="{$gBitLoc.HTML_PKG_URL}admin/admin_html_pages.php?title={$title|escape:"url"}">{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
	{if $type eq 'd'}
		<a href="{$gBitLoc.HTML_PKG_URL}admin/admin_html_page_content.php?title={$title|escape:"url"}">content</a>
	{/if}
{/if}
