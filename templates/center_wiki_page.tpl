{strip}
{*
<div class="row">
	{formfeedback success=`$success`}
</div>
<div class="row">
	{formfeedback error=`$error`}
</div>
*}

{if $fEditCenterWikiPageSettings}
	{form legend="Center Wiki Page Settings"}
		<input type="hidden" name="fHomepage" value="{$fHomepage}"/>
		<div class="row">
			{formlabel label="Wiki Page" for="page_name"}
			{forminput}
				<input type="text" size="40" name="page_name" id="page_name" value="{$gContent->mInfo.title}"/>
				{formhelp note=""}
			{/forminput}
		</div>

		<div class="row">
			{formlabel label="Display Title" for="display_title"}
			{forminput}
				<input type="checkbox" name="display_title" id="display_title" {if $modParams.display_title}checked="checked"{/if}/>
			{/forminput}
		</div>

		<div class="row submit">
			<input type="submit" name="fSubmitCenterWikiPageSettings" value="Update Settings"/>
			&nbsp;<input type="submit" name="fCancelCenterWikiPageSettings" value="Cancel"/>
		</div>
	{/form}
{/if}

<div class="display wikipage">
	{if $gContent->mInfo.title}
		<div class="header"><h1>{$gContent->mInfo.title}</h1></div>
	{/if}
	<div class="content">
		{if $gBitSystem->isFeatureActive( 'liberty_auto_display_attachment_thumbs' )}
			{include file="bitpackage:liberty/storage_thumbs.tpl"}
		{/if}
		{$gContent->mInfo.parsed_data}
	</div>

	<div class="actionicon"> <!-- Actions -->
		{if $gBitUser->hasPermission( 'bit_p_edit' )}
			<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$gContent->mInfo.page_id}">{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
		{/if}
		{if $userOwnsPage}
			<a href="{$smarty.server.PHP_SELF}?fHomepage={$fHomepage}&fEditCenterWikiPageSettings=1">{biticon ipackage=liberty iname="config" iexplain="configure"}</a>
		{/if}
		{if $gBitSystem->isPackageActive( 'pdf' ) && $gContent->hasUserPermission( 'bit_p_pdf_generation' )}
			<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?page_id={$gContent->mInfo.page_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
		{/if}
	</div> <!-- End Actions -->
</div>
{/strip}
