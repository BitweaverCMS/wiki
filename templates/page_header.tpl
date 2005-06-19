<div class="header">
	{if $gBitSystem->isPackageActive( 'categories' )}
		{include file="bitpackage:categories/categories_nav.tpl"}
	{/if}

	{if $gBitSystemPrefs.feature_page_title eq 'y'}
		<h1>{$pageInfo.title}</h1>
		{if $cached_page eq 'y'}<span class="cached">(cached)</span>{/if}
	{/if}

	{include file="bitpackage:wiki/page_date_bar.tpl"}

	{if $gBitSystemPrefs.feature_wiki_description eq 'y' and $description}
		<h2>{$description}</h2>
	{/if}
</div><!-- end .header -->
