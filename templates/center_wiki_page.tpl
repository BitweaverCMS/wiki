{strip}
{if $wikiPage}
<div class="display wiki">
	<nav class="floaticon"> <!-- Actions -->
		{if $wikiPage->hasUpdatePermission()}
			<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$wikiPage->mInfo.page_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit"}</a>
		{/if}
		{if $gBitSystem->isPackageActive( 'pdf' ) && $wikiPage->hasUserPermission( 'p_pdf_generation' )}
			<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?page_id={$wikiPage->mInfo.page_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
		{/if}
	</nav> <!-- End Actions -->

	{if $showTitle}
		<header>
			<h1>{$wikiPage->getTitle()|escape}</h1>
			{if $wikiPage->getField('summary')}<small>{$wikiPage->getField('summary')}</small>{/if}
		</header>
	{/if}

	<section class="body">
		<div class="content">
			{if $gBitSystem->isFeatureActive( 'liberty_auto_display_attachment_thumbs' )}
				{include file="bitpackage:liberty/storage_thumbs.tpl"}
			{/if}
			{$wikiPage->mInfo.parsed_data}
		</div>
	</section>
</div>
{/if}
{/strip}
