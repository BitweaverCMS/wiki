<div class="header">
	{if $gBitSystem->isFeatureActive( 'page_title' )}
		<h1>{$pageInfo.title}</h1>
		{if $pageInfo.page_is_cached}<span class="cached">(cached)</span>{/if}
	{/if}

	{if $gBitSystem->isFeatureActive( 'wiki_description' ) and $description}
		<h2>{$description}</h2>
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}
</div><!-- end .header -->
