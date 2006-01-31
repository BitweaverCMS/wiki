<div class="header">
	{if $gBitSystem->isFeatureActive( 'feature_page_title' )}
		<h1>{$pageInfo.title}</h1>
	{/if}

	{if $gBitSystem->isFeatureActive( 'feature_wiki_description' ) and $description}
		<h2>{$description}</h2>
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}
</div><!-- end .header -->
