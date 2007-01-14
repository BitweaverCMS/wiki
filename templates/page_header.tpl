<div class="header">
	{if $gBitSystem->isFeatureActive( 'wiki_page_title' )}
		<h1>{$pageInfo.title|escape}</h1>
		{if $pageInfo.page_is_cached}<span class="cached">(cached)</span>{/if}
	{/if}

	{if $gBitSystem->isFeatureActive( 'wiki_description' ) and $description}
		<p>{$pageInfo.description|escape}</p>
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}
</div><!-- end .header -->
