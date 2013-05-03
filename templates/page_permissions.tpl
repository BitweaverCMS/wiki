{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin wiki">
	<div class="header">
		<h1>{tr}Permissions for{/tr} <a href="{$gContent->mInfo.display_url}">{$gContent->mInfo.title|escape}</a></h1>
	</div>

	<div class="body">
		{form legend="Notify via email when updated"}
			<input type="hidden" name="page_id" value="{$gContent->mInfo.page_id}" />
			<input type="hidden" name="tab" value="email" />

			<div class="control-group">
				{formlabel label="Email"}
				{forminput}
					<input type="text" name="email" size="35" />
					{formhelp note="Enter the email address where you want to have notifications sent, as soon as there are changes made to this page."}
				{/forminput}
			</div>

			<div class="control-group submit">
				<input type="submit" class="btn" name="addemail" value="{tr}Add email address{/tr}" />
			</div>
		{/form}

		{if count( $emails ) gt 0 }
			<h2>{tr}Existing requests for email notification{/tr}</h2>
			<ul>
				{section name=ix loop=$emails}
					<li>{$emails[ix]} <a href="{$smarty.const.WIKI_PKG_URL}page_permissions.php?page_id={$gContent->mInfo.page_id}&amp;removeemail={$emails[ix]}&amp;tab=email">{booticon iname="icon-trash" ipackage="icons" iexplain="delete"}</a></li>
				{/section}
			</ul>
		{/if}

		{include file="bitpackage:liberty/content_permissions_inc.tpl"}
	</div><!-- end .body -->
</div><!-- end .wiki -->

{include file="bitpackage:wiki/page_action_bar.tpl"}
{/strip}
