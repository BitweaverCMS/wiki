{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/print_multi_pages.tpl,v 1.3 2006/03/25 20:55:09 squareing Exp $ *}

{include file="bitpackage:kernel/header.tpl"}
<div id="printpage">
	<h1>Wiki Pages</h1>
	{section name=ix loop=$pages}
		<h2>{$pages[ix].title|escape}</h2>
		<div class="content">{$pages[ix].parsed_data}</div>
		<hr />
	{/section}
</div>
{include file="bitpackage:kernel/footer.tpl"}
