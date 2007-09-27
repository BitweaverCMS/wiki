{capture assign="wiki_page_title"}{strip}
	{if $gBitSystem->isFeatureActive( 'wiki_page_title' )}
		<h1>{$pageInfo.title|escape}</h1>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_description' ) and $pageInfo.summary}
		<p>{$pageInfo.summary|escape}</p>
	{/if}
	{include file="bitpackage:wiki/page_date_bar.tpl"}
{/strip}{/capture}
{if !empty($wiki_page_title)}
	<div class="header">
		{$wiki_page_title}
	</div><!-- end .header -->
{/if}
