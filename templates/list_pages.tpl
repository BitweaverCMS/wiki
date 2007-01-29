{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/list_pages.tpl,v 1.21 2007/01/29 10:03:14 squareing Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin wiki">
	<div class="header">
		<h1>{tr}WikiPages{/tr}</h1>
	</div>

	{formfeedback error=$errors}

	<div class="body">
		{form class="minifind" legend="find in entries"}
			<input type="hidden" name="sort_mode" value="{$sort_mode}" />
			{biticon ipackage="icons" iname="edit-find" iexplain="Search"} &nbsp;
			<label>{tr}Title{/tr}:&nbsp;<input size="16" type="text" name="find_title" value="{$find_title|default:$smarty.request.find_title|escape}" /></label> &nbsp;
			<label>{tr}Author{/tr}:&nbsp;<input size="10" type="text" name="find_author" value="{$find_author|default:$smarty.request.find_author|escape}" /></label> &nbsp;
			<label>{tr}Last Editor{/tr}:&nbsp;<input size="10" type="text" name="find_last_editor" value="{$find_last_editor|default:$smarty.request.find_last_editor|escape}" /></label> &nbsp;
			<input type="submit" name="search" value="{tr}Find{/tr}" />&nbsp;
			<input type="button" onclick="location.href='{$smarty.server.PHP_SELF}{if $hidden}?{/if}{foreach from=$hidden item=value key=name}{$name}={$value}&amp;{/foreach}'" value="{tr}Reset{/tr}" />
		{/form}

		{form id="checkform"}
			<div class="navbar">
				<ul>
					<li>{biticon ipackage="icons" iname="emblem-symbolic-link" iexplain="sort by"}</li>
					{if $gBitSystem->isFeatureActive( 'wiki_list_name' )}
						<li>{smartlink ititle="Page Name" isort="title" offset="$offset" find_title="$find_title" find_author="$find_author" find_last_editor="$find_last_editor"}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_lastmodif' )}
						<li>{smartlink ititle="Last Modified" iorder="desc" idefault=1 isort="last_modified" offset=$offset find_title="$find_title" find_author="$find_author" find_last_editor="$find_last_editor"}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_creator' )}
						<li>{smartlink ititle="Author" isort="creator_user" offset=$offset find_title="$find_title" find_author="$find_author" find_last_editor="$find_last_editor"}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_user' )}
						<li>{smartlink ititle="Last Editor" isort="modifier_user" offset=$offset find_title="$find_title" find_author="$find_author" find_last_editor="$find_last_editor"}</li>
					{/if}
					{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='list_sort' serviceHash=$gContent->mInfo}
				</ul>
			</div>

			<input type="hidden" name="offset" value="{$offset}" />
			<input type="hidden" name="sort_mode" value="{$sort_mode}" />

			<table class="clear data">
				<caption>{tr}WikiPages Listing{/tr} <span class="total">[ {$pagecount} ]</span></caption>
				<tr>
					{*  at the moment, the only working option to use the checkboxes for is deleting pages. so for now the checkboxes are visible iff $p_wiki_remove_page is set. Other applications make sense as well (categorize, convert to pdf, etc). Add necessary corresponding permission here: *}

					{if $gBitUser->hasPermission( 'p_wiki_remove_page' )}              {* ... "or $gBitUser->hasPermission( 'bit_p_other_sufficient_condition_for_checkboxes' )"  *}
						{assign var='checkboxes_on' value='y'}
					{else}
						{assign var='checkboxes_on' value='n'}
					{/if}
					{counter name=cols start=-1 print=false}
					{if $gBitSystem->isFeatureActive( 'wiki_list_hits' )}
						<th>{smartlink ititle="Hits" isort="hits" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_lastver' )}
						<th>{smartlink ititle="Last Version" isort="version" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_comment' )}
						<th>{smartlink ititle="Comment" isort="edit_comment" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_status' )}
						<th>{smartlink ititle="Status" isort="flag" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_versions' )}
						<th>{smartlink ititle="Version" isort="versions" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_links' )}
						<th>{smartlink ititle="Links" isort="links" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_backlinks' )}
						<th>{smartlink ititle="Backlinks" isort="backlinks" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_format_guid' )}
						<th>{smartlink ititle="GUID" isort="format_guid" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_size' )}
						<th>{smartlink ititle="Size" isort="size" offset=$offset}</th>
						{counter name=cols assign=cols print=false}
					{/if}
					{if $gBitUser->hasPermission( 'p_wiki_edit_page' )}
						<th style="width:1px;">{tr}Actions{/tr}</th>
						{counter name=cols assign=cols print=false}
					{/if}
				</tr>

				{cycle values="even,odd" print=false}
				{section name=changes loop=$listpages}
					<tr class="{cycle advance=false}">
						<td colspan="{$cols}">
							{if $gBitSystem->isFeatureActive( 'wiki_list_name' )}
								<h3><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$listpages[changes].page_id}" title="{$listpages[changes].description}">{$listpages[changes].title|escape}</a></h3>
							{else}
								<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$listpages[changes].page_id}" title="{$listpages[changes].page_id}">Page #{$listpages[changes].page_id}</a>
							{/if}

							{if $gBitSystem->isFeatureActive( 'wiki_list_creator' ) && $gBitSystem->isFeatureActive( 'wiki_list_lastmodif' ) }
								<span style="display:block; width:50%; float:left;">
									{tr}Created:{/tr} {displayname real_name=$listpages[changes].creator_real_name user=$listpages[changes].creator_user}
									, {$listpages[changes].created|bit_short_datetime}
								</span>
								<span style="display:block; width:50%; float:right;">
									{if ($listpages[changes].version <= 1)}
										{tr}No edits since creation{/tr}
									{else}
										{tr}Last Edited:{/tr} {displayname real_name=$listpages[changes].modifier_real_name user=$listpages[changes].modifier_user}
										, {$listpages[changes].last_modified|bit_short_datetime}
									{/if}
								</span>
							{else}
								{if $gBitSystem->isFeatureActive( 'wiki_list_creator' )}
									{tr}Created by{/tr} {displayname real_name=$listpages[changes].creator_real_name user=$listpages[changes].creator_user}
								{/if}
								, {$listpages[changes].created|bit_short_datetime}
								{if $gBitSystem->isFeatureActive( 'wiki_list_lastmodif' ) && ($listpages[changes].version > 1)}
									<br />
									{tr}Last modified{/tr}
									{if $listpages[changes].editor != $listpages[changes].creator}
										&nbsp;{tr}by{/tr} {displayname real_name=$listpages[changes].modifier_real_name user=$listpages[changes].modifier_user}
									{/if}
									, {$listpages[changes].last_modified|bit_short_datetime}
								{/if}
							{/if}
						</td>
						<td style="text-align:right; vertical-align:top;">
							{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='list' serviceHash=$listpages[changes]}
						</td>
					</tr>
					<tr class="{cycle}">
						{if $gBitSystem->isFeatureActive( 'wiki_list_hits' )}
							<td style="text-align:center;">{$listpages[changes].hits}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_lastver' )}
							<td style="text-align:center;">{$listpages[changes].version}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_comment' )}
							<td>{$listpages[changes].comment}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_status' )}
							<td style="text-align:center;">
								{if $listpages[changes].flag eq 'locked'}
									{biticon ipackage="icons" iname="emblem-readonly" iexplain="locked"}
								{else}
									{biticon ipackage="icons" iname="emblem-default" iexplain="unlocked"}
								{/if}
							</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_versions' )}
							{if $gBitSystem->isFeatureActive( 'wiki_history' )}
								<td style="text-align:center;">{smartlink ititle=$listpages[changes].version ifile='page_history.php' page_id=$listpages[changes].page_id}</td>
							{else}
								<td style="text-align:center;">{$listpages[changes].version}</td>
							{/if}
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_links' )}
							<td style="text-align:center;">{$listpages[changes].links|default:"0"}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_backlinks' )}
							{if $gBitSystem->isFeatureActive( 'wiki_backlinks' ) && $listpages[changes].backlinks > 0}
								<td style="text-align:center;"><a href="{$smarty.const.WIKI_PKG_URL}backlinks.php?page={$listpages[changes].title|escape:"url"}">{$listpages[changes].backlinks|default:"0"}</a></td>
							{else}
								<td style="text-align:center;">{$listpages[changes].backlinks|default:"0"}</td>
							{/if}
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_format_guid' )}
							<td>{$listpages[changes].format_guid}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_size' )}
							<td style="text-align:right;">{$listpages[changes].len|display_bytes}</td>
						{/if}
						{if $gBitUser->hasPermission( 'p_wiki_edit_page' )}
							<td class="actionicon">
								<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$listpages[changes].page_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
								{if $checkboxes_on eq 'y'}
									<input type="checkbox" name="checked[]" value="{$listpages[changes].page_id}" />
								{/if}
							</td>
						{/if}
					</tr>
				{sectionelse}
					<tr class="norecords"><td colspan="{$cols}">
						{tr}No records found{/tr}
					</td></tr>
				{/section}
			</table>

			{if $checkboxes_on eq 'y'}
				<div style="text-align:right;">
					<script type="text/javascript">/* <![CDATA[ check / uncheck all */
						document.write("<label for=\"switcher\">{tr}Select All{/tr}</label> ");
						document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form.id,'checked[]','switcher')\" />");
					/* ]]> */</script>
					<br />
					<select name="submit_mult" onchange="this.form.submit();">
						<option value="" selected="selected">{tr}with checked{/tr}:</option>
						{if $gBitUser->hasPermission( 'p_wiki_remove_page' )}
							<option value="remove_pages">{tr}remove{/tr}</option>
						{/if}
					</select>

					<noscript>
						<div><input type="submit" value="{tr}Submit{/tr}" /></div>
					</noscript>
				</div>
			{/if}
		{/form}

		{pagination}
	</div><!-- end .body -->
</div><!-- end .wiki -->
{/strip}
