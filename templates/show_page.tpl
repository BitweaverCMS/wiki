{* $Header$ *}
{if empty($comments_at_top_of_page)}{assign var=comments_at_top_of_page value=false}{/if}
{if empty($print_page)}{assign var=print_page value=false}{/if}
{if $comments_at_top_of_page and !$print_page and $gBitSystem->isFeatureActive( 'wiki_comments' )}
	{include file="bitpackage:wiki/page_header.tpl"}
	{include file="bitpackage:liberty/comments.tpl"}
{/if}

{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}

<div class="display wiki {$gContent->mInfo.title|escape|lower|regex_replace:"/[^a-z_]/i":""}">
	{if empty($liberty_preview)}
		{include file="bitpackage:wiki/page_icons.tpl"}
		{include file="bitpackage:wiki/page_header.tpl"}
	{/if}

	{if $gBitSystem->isPackageActive( 'stickies' )}
		{include file="bitpackage:stickies/display_bitsticky.tpl"}
	{/if}

	{include file="bitpackage:wiki/page_display.tpl"}

	{if !empty($pages) and $pages > 1}
		<div class="pagination">
			<a title="First page" href="index.php?page_id={$gContent->mInfo.page_id}&amp;pagenum={$first_page}">&laquo; &laquo;</a>
			<a title="Previous page" href="index.php?page_id={$gContent->mInfo.page_id}&amp;pagenum={$prev_page}">&laquo;</a>
			Page {$pagenum} of {$pages}
			<a title="Next page" href="index.php?page_id={$gContent->mInfo.page_id}&amp;pagenum={$next_page}">&raquo;</a>
			<a title="Last page" href="index.php?page_id={$gContent->mInfo.page_id}&amp;pagenum={$last_page}">&raquo; &raquo;</a>
		</div>
	{/if} {* end .pagination *}

	{if !empty($footnote)}{$footnote}{/if}

	{if $gBitSystem->isFeatureActive( 'wiki_copyrights' )}
		<p class="copyright">
			{if $pageCopyrights}
				{section name=i loop=$pageCopyrights}
					&copy; {$pageCopyrights[i].year} {$pageCopyrights[i].authors} {if $pageCopyrights[i].title} under {$pageCopyrights[i].title|escape}{/if}
				{/section}
			{elseif $wiki_license_page != '' }
				The content on this page is licensed under the terms of the <a href="{$wiki_license_page}"><b>{$wiki_submit_notice}</b></a>.
			{/if}
			{if $gBitUser->hasPermission( 'p_wiki_edit_copyright' )}
				<br />To edit the copyright notices <a href="{$smarty.const.WIKI_PKG_URL}copyrights.php?page_id={$gContent->mInfo.page_id}">click here</a>.
			{/if}
		</p>
	{/if}
</div><!-- end .wiki -->

{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}

{if !$comments_at_top_of_page and !$print_page and $gBitSystem->isFeatureActive( 'wiki_comments' )}
	{include file="bitpackage:liberty/comments.tpl"}
{/if}
