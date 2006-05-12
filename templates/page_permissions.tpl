{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/page_permissions.tpl,v 1.4 2006/05/12 20:28:50 sylvieg Exp $ *}
{strip}{debug}
<div class="floaticon">{bithelp}</div>

<div class="admin wiki">
	<div class="header">
		<h1>{tr}Permissions for{/tr} <a href="{$gContent->mInfo.display_url}">{$gContent->mInfo.title|escape}</a></h1>
	</div>

	<div class="body">
		{jstabs}
			{jstab title="Permissions"}
				{form legend="Assign permissions"}
					<input type="hidden" name="page_id" value="{$gContent->mInfo.page_id}" />

					<div class="row">
						{formlabel label="Group"}
						{forminput}
							<select name="group_id">
								{foreach from=$groups key=groupId item=group}
									<option value="{$groupId}">{$group.group_name}</option>
								{/foreach}
							</select>
							{formhelp note="Select the group that should be gived specific permissions."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Permission"}
						{forminput}
							<select name="perm">
								{foreach from=$perms key=permName item=perm}
									<option value="{$permName}">{$perm.perm_desc}</option>
								{/foreach}
							</select>
							{formhelp note="Select what permission you want to give the group."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="assign" value="{tr}Assign permission{/tr}" />
					</div>
				{/form}

				<h2>{tr}Current permissions for{/tr} {$gContent->mInfo.title|escape}</h2>
				<table class="data" summary="{tr}Table describing permissions for the page {$gContent->mInfo.title|escape}{/tr}">
					<tr>
						<th scope="col">{tr}Group{/tr}</th>
						<th scope="col">{tr}Permissions{/tr}</th>
						<th scope="col">{tr}Actions{/tr}</th>
					</tr>
					{section  name=pg loop=$page_perms}
						<tr class="{cycle values="even,odd"}">
							<td>{$page_perms[pg].group_name}</td>
							<td>{$page_perms[pg].perm_name}</td>
							<td class="actionicon"><a href="{$smarty.const.WIKI_PKG_URL}page_permissions.php?action=remove&amp;content_id={$gContent->mContentId}&amp;object_type={$gContent->mInfo.content_type_guid}&amp;perm={$page_perms[pg].perm_name}&amp;group_id={$page_perms[pg].group_id}">{biticon ipackage=liberty iname="delete" iexplain="remove from this page"}</a></td>
						</tr>
					{sectionelse}
						<tr class="norecords">
							<td colspan="3">
								{tr}No individual permissions set<br />global permissions apply{/tr}
							</td>
						</tr>
					{/section}
				</table>
			{/jstab}

			{jstab title="Email Notifications"}

				{form legend="Notify via email when updated"}
					<input type="hidden" name="page_id" value="{$gContent->mInfo.page_id}" />
					<input type="hidden" name="tab" value="email" />

					<div class="row">
						{formlabel label="Email"}
						{forminput}
							<input type="text" name="email" size="35" />
							{formhelp note="Enter the email address where you want to have notifications sent, as soon as there are changes made to this page."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="addemail" value="{tr}Add email address{/tr}" />
					</div>
				{/form}

				{if count( $emails ) gt 0 }
					<h2>{tr}Existing requests for email notification{/tr}</h2>
					<ul>
						{section name=ix loop=$emails}
							<li>{$emails[ix]} <a href="{$smarty.const.WIKI_PKG_URL}page_permissions.php?page_id={$gContent->mInfo.page_id}&amp;removeemail={$emails[ix]}&amp;tab=email">{biticon ipackage=liberty iname="delete_small" iexplain="delete"}</a></li>
						{/section}
					</ul>
				{/if}
			{/jstab}
		{/jstabs}
	</div><!-- end .body -->
</div><!-- end .wiki -->

{include file="bitpackage:wiki/page_action_bar.tpl"}
{/strip}
