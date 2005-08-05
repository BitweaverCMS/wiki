{if $print_page ne 'y'}
<div class="navbar">
	<ul>
		{if $gBitUser->hasPermission( 'bit_p_view_tabs_and_tools' )}
			{if !$lock}
				{assign var=format_guid value=$pageInfo.format_guid}
				{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y'}
					{if $gBitUser->hasPermission( 'bit_p_edit' ) or $page eq 'SandBox'}
						{if $beingEdited eq 'y'}
							{*popup_init src="`$smarty.const.THEMES_PKG_URL`js/overlib.js"*}
							<li><strong><a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}" {popup text="$semUser"}>{tr}Edit{/tr}</a></strong></li>
						{else}
							<li><a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}">{tr}Edit{/tr}</a></li>
						{/if}
					{/if}
				{/if}
			{/if}
			{if $page ne 'SandBox'}
				{if $gBitUser->hasPermission( 'bit_p_admin_wiki' ) or ($gBitUser->mUserId and ($gBitUser->mUserId eq $pageInfo.modifier_user_id) and ($gBitUser->hasPermission( 'bit_p_lock' )) and ($gBitSystem->isFeatureActive( 'feature_wiki_usrlock' )))}
					{if $lock}
						<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=unlock">{tr}Unlock{/tr}</a></li>
					{else}
						<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=lock">{tr}Lock{/tr}</a></li>
					{/if}
				{/if}
				{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
					<li><a href="{$smarty.const.WIKI_PKG_URL}page_permissions.php?page_id={$pageInfo.page_id}">{tr}Permissions{/tr}</a></li>
				{/if}
				{if $gBitSystem->isFeatureActive( 'feature_history' )}
					<li><a href="{$smarty.const.WIKI_PKG_URL}page_history.php?page_id={$pageInfo.page_id}">{tr}History{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitSystem->isFeatureActive( 'feature_likePages' )}
				<li><a href="{$smarty.const.WIKI_PKG_URL}like_pages.php?page_id={$pageInfo.page_id}">{tr}Similar{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'feature_wiki_undo' ) and $canundo eq 'y'}
				<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;undo=1">{tr}Undo{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'wiki_uses_slides' )}
				{if $show_slideshow eq 'y'}
					<li><a href="{$smarty.const.WIKI_PKG_URL}slideshow.php?page_id={$pageInfo.page_id}">{tr}Slides{/tr}</a></li>
				{elseif $structure eq 'y'}
					<li><a href="slideshow2.php?structure_id={$page_info.structure_id}">{tr}Slides{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
				<li><a href="{$smarty.const.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}">{tr}Export{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'feature_wiki_discuss' )}
				<li><a href="{$smarty.const.BITFORUMS_PKG_URL}view_forum.php?forum_id={$wiki_forum_id}&amp;comments_postComment=post&amp;comments_title={$page|escape:"url"}&amp;comments_data={ "Use this thread to discuss the [index.php\?page=$page|$page page."|escape:"url"}&amp;comment_topictype=n">{tr}Discuss{/tr}</a></li>
			{/if}
		{/if}
	</ul>
</div>
{/if}
