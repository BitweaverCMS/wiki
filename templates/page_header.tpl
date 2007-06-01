<div class="header">
	{if $gBitSystem->isFeatureActive( 'wiki_page_title' )}
		<h1>{$pageInfo.title|escape}</h1>
	{/if}

	{if $gBitSystem->isFeatureActive( 'wiki_description' ) and $description}
		<p>{$pageInfo.description|escape}</p>
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}
</div><!-- end .header -->
