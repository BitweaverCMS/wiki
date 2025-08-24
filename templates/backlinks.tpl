{strip}
<div class="listing wiki">
	<div class="header">
		<h1>Backlinks to <a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mPageId}" class="wiki">{$page}</a></h1>
	</div>

	<div class="body">
		<ul>
			{foreach from=$backlinks key=page_id item=title}
				<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$page_id}" class="wiki">{$title}</a></li>
			{foreachelse}
				<div class="norecords">No backlinks to this page</div>
			{/foreach}
		</ul>
	</div><!-- end .body -->
</div><!-- end .wiki -->
{/strip}
