{if $comments_at_top_of_page eq 'y' and $print_page ne 'y' and $gBitSystem->isFeatureActive( 'wiki_comments' )}
	{include file="bitpackage:wiki/page_header.tpl"}
	{include file="bitpackage:liberty/comments.tpl"}
{/if}

{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}

<div class="display wiki {$pageInfo.title|escape|lower|regex_replace:"/[^a-z_]/i":""}">
	{if !$liberty_preview}
		{include file="bitpackage:wiki/page_icons.tpl"}
		{include file="bitpackage:wiki/page_header.tpl"}
	{/if}

	{if $gBitSystem->isPackageActive( 'stickies' )}
		{include file="bitpackage:stickies/display_bitsticky.tpl"}
	{/if}

	{include file="bitpackage:wiki/page_display.tpl"}

	{if $pages > 1}
		<div class="pagination">
			{*<a title="{tr}First page{/tr}" href="index.php?page_id={$pageInfo.page_id}&amp;pagenum={$first_page}">&laquo; &laquo;</a>*}
			<a title="{tr}Previous page{/tr}" href="index.php?page_id={$pageInfo.page_id}&amp;pagenum={$prev_page}">&laquo;</a>
			{tr}Page {$pagenum} of {$pages}{/tr}
			<a title="{tr}Next page{/tr}" href="index.php?page_id={$pageInfo.page_id}&amp;pagenum={$next_page}">&raquo;</a>
			{*<a title="{tr}Last page{/tr}" href="index.php?page_id={$pageInfo.page_id}&amp;pagenum={$last_page}">&raquo; &raquo;</a>*}
		</div>
	{/if} {* end .pagination *}

	{$footnote}

	{if $gBitSystem->isFeatureActive( 'wiki_copyrights' )}
		<p class="copyright">
			{if $pageCopyrights}
				{section name=i loop=$pageCopyrights}
					&copy; {$pageCopyrights[i].year} {$pageCopyrights[i].authors} {if $pageCopyrights[i].title} under {$pageCopyrights[i].title|escape}{/if}
				{/section}
			{elseif $wiki_license_page != '' }
				{tr}The content on this page is licensed under the terms of the{/tr} <a href="{$wiki_license_page}"><b>{tr}{$wiki_submit_notice}{/tr}</b></a>.
			{/if}
			{if $gBitUser->hasPermission( 'p_wiki_edit_copyright' )}
				<br />{tr}To edit the copyright notices{/tr} <a href="{$smarty.const.WIKI_PKG_URL}copyrights.php?page_id={$pageInfo.page_id}">{tr}click here{/tr}</a>.
			{/if}
		</p>
	{/if}
</div><!-- end .wiki -->

{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}

{if $comments_at_top_of_page ne 'y' and $print_page ne 'y' and $gBitSystem->isFeatureActive( 'wiki_comments' )}
	{include file="bitpackage:liberty/comments.tpl"}
{/if}
