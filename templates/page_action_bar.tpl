
{if $print_page ne 'y'}

{if $gBitUser->hasPermission( 'bit_p_view_tabs_and_tools' )}
	{if !$lock}
		{assign var=format_guid value=$pageInfo.format_guid}
		{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y'}
			{if $gBitUser->hasPermission( 'bit_p_edit' ) or $page eq 'SandBox'}
				{if $beingEdited eq 'y'}
					{*popup_init src="`$gBitLoc.THEMES_PKG_URL`js/overlib.js"*}
					<strong><a href="{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}" {popup text="$semUser"}>{tr}edit{/tr}</a></strong>
				{else}
					<a href="{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}">{tr}edit{/tr}</a>
				{/if}
			{/if}
		{/if}
	{/if}
	{if $page ne 'SandBox'}
		{if $gBitUser->hasPermission( 'bit_p_admin_wiki' ) or ($gBitUser->mUserId and ($gBitUser->mUserId eq $pageInfo.modifier_user_id) and ($gBitUser->hasPermission( 'bit_p_lock' )) and ($gBitSystemPrefs.feature_wiki_usrlock eq 'y'))}
			{if $lock}
				<a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=unlock">{tr}unlock{/tr}</a>
			{else}
				<a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;action=lock">{tr}lock{/tr}</a>
			{/if}
		{/if}
		{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
			<a href="{$gBitLoc.WIKI_PKG_URL}page_permissions.php?page_id={$pageInfo.page_id}">{tr}perms{/tr}</a>
		{/if}
		{if $gBitSystemPrefs.feature_history eq 'y'}
			<a href="{$gBitLoc.WIKI_PKG_URL}page_history.php?page_id={$pageInfo.page_id}">{tr}history{/tr}</a>
		{/if}
	{/if}
	{if $gBitSystemPrefs.feature_likePages eq 'y'}
		<a href="{$gBitLoc.WIKI_PKG_URL}like_pages.php?page_id={$pageInfo.page_id}">{tr}similar{/tr}</a>
	{/if}
	{if $gBitSystemPrefs.feature_wiki_undo eq 'y' and $canundo eq 'y'}
		<a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;undo=1">{tr}undo{/tr}</a>
	{/if}
	{if $gBitSystemPrefs.wiki_uses_slides eq 'y'}
		{if $show_slideshow eq 'y'}
			<a href="{$gBitLoc.WIKI_PKG_URL}slideshow.php?page_id={$pageInfo.page_id}">{tr}slides{/tr}</a>
		{elseif $structure eq 'y'}
			<a href="slideshow2.php?structure_id={$page_info.structure_id}">{tr}slides{/tr}</a>
		{/if}
	{/if}
	{if $gBitUser->hasPermission( 'bit_p_admin_wiki' )}
		<a href="{$gBitLoc.WIKI_PKG_URL}export_wiki_pages.php?page_id={$pageInfo.page_id}">{tr}export{/tr}</a>
	{/if}
	{if $gBitSystemPrefs.feature_wiki_discuss eq 'y'}
		<a href="{$gBitLoc.BITFORUMS_PKG_URL}view_forum.php?forum_id={$wiki_forum_id}&amp;comments_postComment=post&amp;comments_title={$page|escape:"url"}&amp;comments_data={ "Use this thread to discuss the [index.php\?page=$page|$page page."|escape:"url"}&amp;comment_topictype=n">{tr}discuss{/tr}</a>
	{/if}
{/if}

{*if $show_page eq 'y'}
	<!-- this comment code is no longer valid. The current comment system should probably be changed to use this "comzone" style
	{if $gBitSystemPrefs.feature_wiki_comments eq 'y'}
		<div class="navbar comment">
			{if $comments_cant > 0}
				<a href="javascript:document.location='#comments';flip('comzone{if $comments_show eq 'y'}open{/if}');">{if $comments_cant eq 1}{tr}1 comment{/tr}{else}{$comments_cant} {tr}comments{/tr}{/if}</a>
			{else}
				<a href="javascript:document.location='#comments';flip('comzone{if $comments_show eq 'y'}open{/if}');">{tr}comment{/tr}</a>
			{/if}
		</div>
	{/if}-->
{/if*}
{/if}
