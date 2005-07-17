<div class="header">
	{if $gBitSystem->isPackageActive( 'categories' )}
		{include file="bitpackage:categories/categories_nav.tpl"}
	{/if}

	{if $gBitSystem->isFeatureActive( 'feature_page_title' )}
		<h1>{$pageInfo.title}</h1>
		{if $cached_page eq 'y'}<span class="cached">(cached)</span>{/if}
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}

	{if $gBitSystem->isFeatureActive( 'feature_wiki_description' ) and $description}
		<h2>{$description}</h2>
	{/if}
</div><!-- end .header -->
