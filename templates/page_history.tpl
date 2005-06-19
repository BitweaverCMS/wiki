{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/page_history.tpl,v 1.2 2005/06/19 10:01:33 jht001 Exp $ *}
{strip}
<div class="admin wiki">
	<div class="header">
		<h1>{tr}History{/tr} {tr}of{/tr} <a href="{$pageInfo.display_url}">{$pageInfo.title}</a></h1>
	</div>

	<div class="body">
		{if $version}
			<h2>{tr}Version{/tr} {$version}</h2>
		{/if}

		{if $parsed}
			{include file="bitpackage:wiki/show_page.tpl"}
		{/if}

		{if $source}
			<div class="content">{$sourcev}</div>
		{/if}

		{if $diff}
			<table class="data">
				<caption>{tr}Comparing versions{/tr}</caption>
				<tr>
					<th width="50%">{tr}Current version{/tr}</td>
					<th width="50%">{tr}Version {$version}{/tr}</td>
				</tr>
				<tr valign="top">
					<td><div class="content">{$parsed}</div></td>
					<td><div class="content">{$diff}</div></td>
				</tr>
			</table>
		{/if}

		{if $diff2 eq 'y'}
			<h2>{tr}Differences from version{/tr} {$version}</h2>
			{$diffdata}
		{/if}

		{form}
			<input type="hidden" name="page_id" value="{$pageInfo.page_id}" />
			<input type="hidden" name="page" value="{$page}" />

			<table class="data">
				<caption>{tr}Page History{/tr}</caption>
				<tr>
					<th style="width:70%;">{tr}Date / Comment{/tr}</th>
					<th style="width:10%;">{tr}User{/tr}</th>
					<th style="width:10%;">{tr}IP{/tr}</th>
					<th style="width:10%;">{tr}Version{/tr}</th>
				</tr>

				<tr class="odd">
					<td>{$pageInfo.last_modified|bit_short_datetime}<br />{$pageInfo.comment}</td>

					<td>{displayname user=$pageInfo.modifier_user user_id=$pageInfo.modifier_user_id real_name=$pageInfo.modifier_real_name} </td>
					<td style="text-align:right;">{$pageInfo.ip}</td>
					<td style="text-align:right;">{$pageInfo.version}</td>
				</tr>

				<tr class="odd">
					<td colspan="4">
						<a href="{$pageInfo.display_url}">{tr}Current{/tr}</a>
						&nbsp;&bull;&nbsp;{smartlink ititle="Source" page_id=`$gContent->mPageId` source="current"}
					</td>
				</tr>

				{foreach from=$history item=item}
					<tr class="{cycle values='even,odd' advance=false}">
						<td><label for="hist_{$item.version}">{$item.last_modified|bit_short_datetime}<br />{$item.comment}</label></td>
						<td>{displayname hash=$item}</td>
						<td style="text-align:right;">{$item.ip}</td>
						<td style="text-align:right;">{$item.version}</td>
					</tr>
					<tr class="{cycle values='even,odd'}">
						<td colspan="3">
							{smartlink ititle="View" page_id=`$gContent->mPageId` preview=`$item.version`}
							&nbsp;&bull;&nbsp;{smartlink ititle="Compare" page_id=`$gContent->mPageId` diff=`$item.version`}
							&nbsp;&bull;&nbsp;{smartlink ititle="Difference" page_id=`$gContent->mPageId` diff2=`$item.version`}
							&nbsp;&bull;&nbsp;{smartlink ititle="Source" page_id=`$gContent->mPageId` source=`$item.version`}
							{if $gBitUser->hasPermission( 'bit_p_rollback' )}
								&nbsp;&bull;&nbsp;{smartlink iurl="rollback.php" ititle="Rollback" page_id=`$gContent->mPageId` version=`$item.version`}
							{/if}
						</td>
						<td style="text-align:right;">
							{if $gBitUser->hasPermission( 'bit_p_remove' )}
								<input type="checkbox" name="hist[{$item.version}]" id="hist_{$item.version}" />
							{/if}
						</td>
					</tr>
				{foreachelse}
					<tr class="norecords">
						<td colspan="4">
							{tr}No records found{/tr}
						</td>
					</tr>
				{/foreach}
			</table>

			{if $gBitUser->hasPermission( 'bit_p_remove' )}
				<div style="text-align:right;">
					<input type="submit" name="delete" value="{tr}Delete selected versions{/tr}" />
				</div>
			{/if}
		{/form}

		{libertypagination numPages=$numPages page=$page page_id=$pageInfo.page_id}
	</div>
</div> <!-- end .wiki -->
{/strip}
