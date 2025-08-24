{capture wiki_page_title assign="wiki_page_title"}
	{if $gBitSystem->isFeatureActive( 'wiki_page_title' )}
		<h1>{$gContent->mInfo.title|escape}</h1>
	{/if}
	{if $gBitSystem->isFeatureActive( 'wiki_description' ) and $gContent->mInfo.summary}
		<p>{$gContent->mInfo.summary|escape}</p>
	{/if}
{/capture}
{if !empty($wiki_page_title)}
	<div class="main_header">
		{$wiki_page_title|highlight}
		{include file="bitpackage:wiki/page_date_bar.tpl"}
		{if ( !empty( $highlightWordList) ) }{$highlightWordList}{/if}
	</div><!-- end .header -->
{/if}
