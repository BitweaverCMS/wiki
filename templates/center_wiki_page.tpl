{strip}
{if $gContent}
<div class="display wiki">
	<nav class="floaticon"> <!-- Actions -->
		{if $gContent->hasUpdatePermission()}
			<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$gContent->mInfo.page_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit"}</a>
		{/if}
		{if $gBitSystem->isPackageActive( 'pdf' ) && $gContent->hasUserPermission( 'p_pdf_generation' )}
			<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?page_id={$gContent->mInfo.page_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
		{/if}
	</nav> <!-- End Actions -->

	{if $showTitle}
		<header>
			<h1>{$gContent->getTitle()|escape}</h1>
			{if $gContent->getField('summary')}<small>{$gContent->getField('summary')}</small>{/if}
		</header>
	{/if}

	<section class="body">
		<div class="content">
			{if $gBitSystem->isFeatureActive( 'liberty_auto_display_attachment_thumbs' )}
				{include file="bitpackage:liberty/storage_thumbs.tpl"}
			{/if}
			{$gContent->mInfo.parsed_data}
		</div>
	</section>
</div>
{/if}
{/strip}
