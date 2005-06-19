{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/page_select.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}

<div class="header">
	<h1>Multiple pages match "{$choose}"</h1>
</div>

<ul class="data">
	{foreach name=loc key=key item=item from=$dupePages}
		<li class="item {cycle values='even,odd'}">
			<h3><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$item.page_id}">{$item.title}</a></h3>
			{if $item.description}{$item.description}<br/>{/if}
			{tr}Last Modified{/tr}: {$item.last_modified|bit_long_datetime}
		</li>
	{/foreach}
</ul>
