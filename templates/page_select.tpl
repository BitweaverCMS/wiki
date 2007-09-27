{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/page_select.tpl,v 1.5 2007/09/27 13:49:58 spiderr Exp $ *}

<div class="header">
	<h1>Multiple pages match "{$choose}"</h1>
</div>

<ul class="data">
	{foreach name=loc key=key item=item from=$dupePages}
		<li class="item {cycle values='even,odd'}">
			<h3><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$item.page_id}">{$item.title|escape}</a></h3>
			{if $item.summary}{$item.summary|escape}<br/>{/if}
			{tr}Last Modified{/tr}: {$item.last_modified|bit_long_datetime}
		</li>
	{/foreach}
</ul>
