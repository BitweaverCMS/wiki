{* $Header: /cvsroot/bitweaver/_bit_wiki/templates/list_pages.tpl,v 1.13 2006/03/25 20:55:09 squareing Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin wiki">
	<div class="header">
		<h1>{if $pagetitle ne ''}{$pagetitle}{else}{tr}WikiPages{/tr}{/if}</h1>
	</div>

	{formfeedback error=$errors}

	<div class="body">
		{minifind sort_mode=$sort_mode}

		{form id="checkform"}
			<div class="navbar">
				<ul>
					<li>{biticon ipackage=liberty iname=sort iexplain="sort by"}</li>
					{if $gBitSystem->isFeatureActive( 'wiki_list_name' )}
						<li>{smartlink ititle="Page Name" isort="title" offset=$offset}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_lastmodif' )}
						<li>{smartlink ititle="Last Modified" iorder="desc" idefault=1 isort="last_modified" offset=$offset}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_creator' )}
						<li>{smartlink ititle="Author" isort="creator_user" offset=$offset}</li>
					{/if}
					{if $gBitSystem->isFeatureActive( 'wiki_list_user' )}
						<li>{smartlink ititle="Last Editor" isort="modifier_user" offset=$offset}</li>
					{/if}
					{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='list_sort' serviceHash=$gContent->mInfo}
				</ul>
			</div>

			<input type="hidden" name="offset" value="{$offset}" />
			<input type="hidden" name="sort_mode" value="{$sort_mode}" />

			<table class="clear data">
				<caption>{tr}WikiPages Listing{/tr} <span class="total">[ {$pagecount} ]</span></caption>
				<tr>
					{*  at the moment, the only working option to use the checkboxes for is deleting pages. so for now the checkboxes are visible iff $bit_p_remove is set. Other applications make sense as well (categorize, convert to pdf, etc). Add necessary corresponding permission here: *}

					{if $gBitUser->hasPermission( 'bit_p_remove' )}              {* ... "or $gBitUser->hasPermission( 'bit_p_other_sufficient_condition_for_checkboxes' )"  *}
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
					{if $gBitUser->hasPermission( 'bit_p_edit' )}
						<th style="width:1px;">{tr}Actions{/tr}</th>
						{counter name=cols assign=cols print=false}
					{/if}
				</tr>

				{cycle values="even,odd" print=false}
				{section name=changes loop=$listpages}
					<tr class="{cycle advance=false}">
						<td colspan="{$cols}">
							{if $gBitSystem->isFeatureActive( 'wiki_list_name' )}
								<h3><a href="{$listpages[changes].display_url}" title="{$listpages[changes].description}">{$listpages[changes].title|escape}</a></h3>
							{else}
								<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$listpages[changes].page_id}" title="{$listpages[changes].page_id}">Page #{$listpages[changes].page_id}</a>
							{/if}
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
						</td>
						<td style="text-align:right; vertical-algin:top;">
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
									{biticon ipackage="wiki" iname="locked" iexplain="locked"}
								{else}
									{biticon ipackage="wiki" iname="unlocked" iexplain="unlocked"}
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
							{if $gBitSystem->isFeatureActive( 'backlinks' ) && $listpages[changes].backlinks > 0}
								<td style="text-align:center;"><a href="{$smarty.const.WIKI_PKG_URL}backlinks.php?page={$listpages[changes].title|escape:"url"}">{$listpages[changes].backlinks|default:"0"}</a></td>
							{else}
								<td style="text-align:center;">{$listpages[changes].backlinks|default:"0"}</td>
							{/if}
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_format_guid' )}
							<td>{$listpages[changes].format_guid}</td>
						{/if}
						{if $gBitSystem->isFeatureActive( 'wiki_list_size' )}
							<td style="text-align:right;">{$listpages[changes].len|kbsize}</td>
						{/if}
						{if $gBitUser->hasPermission( 'bit_p_edit' )}
							<td class="actionicon">
								<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$listpages[changes].page_id}">{biticon ipackage="liberty" iname="edit" iexplain="edit"}</a>
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
						{if $gBitUser->hasPermission( 'bit_p_remove' )}
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
