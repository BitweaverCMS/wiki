{if $print_page ne 'y'}
<div class="navbar">
	<ul>
		{if $gBitUser->hasPermission( 'bit_p_view_tabs_and_tools' )}
			{if !$lock}
				{assign var=format_guid value=$pageInfo.format_guid}
				{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y'}
					{if $gBitUser->hasPermission( 'bit_p_edit' ) or $page eq 'SandBox'}
						{if $beingEdited eq 'y'}
							{*popup_init src="`$gBitLoc.THEMES_PKG_URL`js/overlib.js"*}
							<li><strong><a href="{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}" {popup text="$semUser"}>{tr}edit{/tr}</a></strong></li>
						{else}
							<li><a href="{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}">{tr}edit{/tr}</a></li>
						{/if}
					{/if}
				{/if}
			{/if}
			{if $page ne 'SandBox'}
				{if $gBitUser->hasPermission( 'bit_p_admin_wiki' ) or ($gBitUser->mUserId and ($gBitUser->mUserId eq $pageInfo.modifier_user_id) and ($gBitUser->hasPermission( 'bit_p_lock' )) and ($gBitSystemPrefs.feature_wiki_usrlock eq 'y'))}
					{if $lock}
						<li><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=unlock">{tr}unlock{/tr}</a></li>
					{else}
						<li><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=lock">{tr}lock{/tr}</a></li>
					{/if}
				{/if}
				{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
					<li><a href="{$gBitLoc.WIKI_PKG_URL}page_permissions.php?page_id={$pageInfo.page_id}">{tr}perms{/tr}</a></li>
				{/if}
				{if $gBitSystemPrefs.feature_history eq 'y'}
					<li><a href="{$gBitLoc.WIKI_PKG_URL}page_history.php?page_id={$pageInfo.page_id}">{tr}history{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitSystemPrefs.feature_likePages eq 'y'}
				<li><a href="{$gBitLoc.WIKI_PKG_URL}like_pages.php?page_id={$pageInfo.page_id}">{tr}similar{/tr}</a></li>
			{/if}
			{if $gBitSystemPrefs.feature_wiki_undo eq 'y' and $canundo eq 'y'}
				<li><a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;undo=1">{tr}undo{/tr}</a></li>
			{/if}
			{if $gBitSystemPrefs.wiki_uses_slides eq 'y'}
				{if $show_slideshow eq 'y'}
					<li><a href="{$gBitLoc.WIKI_PKG_URL}slideshow.php?page_id={$pageInfo.page_id}">{tr}slides{/tr}</a></li>
				{elseif $structure eq 'y'}
					<li><a href="slideshow2.php?structure_id={$page_info.structure_id}">{tr}slides{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
				<li><a href="{$gBitLoc.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}">{tr}export{/tr}</a></li>
			{/if}
			{if $gBitSystemPrefs.feature_wiki_discuss eq 'y'}
				<li><a href="{$gBitLoc.BITFORUMS_PKG_URL}view_forum.php?forum_id={$wiki_forum_id}&amp;comments_postComment=post&amp;comments_title={$page|escape:"url"}&amp;comments_data={ "Use this thread to discuss the [index.php\?page=$page|$page page."|escape:"url"}&amp;comment_topictype=n">{tr}discuss{/tr}</a></li>
			{/if}
		{/if}
	</ul>
</div>
{/if}
