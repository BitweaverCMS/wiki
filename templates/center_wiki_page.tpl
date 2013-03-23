{strip}
{if $gContent}
<div class="display wiki">
	<div class="floaticon"> <!-- Actions -->
		{if $gContent->hasUpdatePermission()}
			<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$gContent->mInfo.page_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
		{/if}
		{if $gBitSystem->isPackageActive( 'pdf' ) && $gContent->hasUserPermission( 'p_pdf_generation' )}
			<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?page_id={$gContent->mInfo.page_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
		{/if}
	</div> <!-- End Actions -->

	{if $gContent->mInfo.title}
		<div class="header"><h1>{$gContent->mInfo.title|escape}</h1></div>
	{/if}

	<div class="body">
		<div class="content">
			{if $gBitSystem->isFeatureActive( 'liberty_auto_display_attachment_thumbs' )}
				{include file="bitpackage:liberty/storage_thumbs.tpl"}
			{/if}
			{$gContent->mInfo.parsed_data}
		</div>
	</div>
</div>
{/if}
{/strip}
