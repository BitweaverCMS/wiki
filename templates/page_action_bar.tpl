{if $print_page ne 'y' && $gBitUser->hasPermission( 'p_users_view_icons_and_tools' )}
	{capture name=navbarlist}{strip}
			{if !$gContent->isLocked()}
				{assign var=format_guid value=$pageInfo.format_guid}
				{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y'}
				{if $gContent->hasUpdatePermission() or $page eq 'SandBox'}
						<li><a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}">{tr}Edit{/tr}</a></li>
					{/if}
				{/if}
				{if $page ne 'SandBox' && $gBitUser->hasPermission( 'p_wiki_remove_page' )}
					<li><a title="{tr}Remove this page{/tr}" href="{$smarty.const.WIKI_PKG_URL}remove_page.php?page_id={$pageInfo.page_id}&amp;version=last">{tr}Remove{/tr}</a></li>
				{/if}
			{/if}
			{if $page ne 'SandBox'}
				{if $gBitUser->hasPermission( 'p_wiki_admin' ) or ($gBitUser->mUserId and ($gBitUser->mUserId eq $pageInfo.modifier_user_id) and ($gBitUser->hasPermission( 'p_wiki_lock_page' )) and ($gBitSystem->isFeatureActive( 'wiki_usrlock' )))}
					{if $gContent->isLocked()}
						<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=unlock">{tr}Unlock{/tr}</a></li>
					{else}
						<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=lock">{tr}Lock{/tr}</a></li>
					{/if}
				{/if}
				{if $gBitUser->hasPermission( 'p_wiki_admin' )}
					<li><a href="{$smarty.const.WIKI_PKG_URL}page_permissions.php?page_id={$pageInfo.page_id}">{tr}Permissions{/tr}</a></li>
				{/if}
				{if $gBitSystem->isFeatureActive( 'wiki_history' ) and $gContent->hasUserPermission('p_wiki_view_history')}
					<li><a href="{$smarty.const.WIKI_PKG_URL}page_history.php?page_id={$pageInfo.page_id}" rel="nofollow">{tr}History{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitSystem->isFeatureActive( 'wiki_like_pages' )}
				<li><a href="{$smarty.const.WIKI_PKG_URL}like_pages.php?page_id={$pageInfo.page_id}">{tr}Similar{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'wiki_undo' ) and !$gContent->isLocked() and $gContent->hasUserPermission('p_wiki_rollback')}
				<li><a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;undo=1">{tr}Undo{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'wiki_uses_slides' )}
				{if $show_slideshow eq 'y'}
					<li><a href="{$smarty.const.WIKI_PKG_URL}slideshow.php?page_id={$pageInfo.page_id}">{tr}Slides{/tr}</a></li>
				{elseif $structure eq 'y'}
					<li><a href="slideshow2.php?structure_id={$page_info.structure_id}">{tr}Slides{/tr}</a></li>
				{/if}
			{/if}
			{if $gBitUser->hasPermission( 'p_wiki_admin' )}
				<li><a href="{$smarty.const.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}">{tr}Export{/tr}</a></li>
			{/if}
			{if $gBitSystem->isFeatureActive( 'users_watches' ) and $gContent->hasUserPermission('p_users_admin')}
				<li><a href="{$smarty.const.WIKI_PKG_URL}page_watches.php?page_id={$pageInfo.page_id}">{tr}Watches{/tr}</a></li>
			{/if}
	{/strip}{/capture}
	{if $smarty.capture.navbarlist ne ''}
		<ul class="list-inline navbar">
			{$smarty.capture.navbarlist}
		</ul>
	{/if}
{/if}
