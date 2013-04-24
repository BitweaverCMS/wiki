{strip}
{if $gBitUser->hasPermission( 'p_users_view_icons_and_tools')}
	<div class="floaticon">
		{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='icon' serviceHash=$gContent->mInfo}

		{if $gBitSystem->isFeatureActive( 'wiki_like_pages' )}
			<a href="{$smarty.const.WIKI_PKG_URL}like_pages.php?page_id={$gContent->mInfo.page_id}">{booticon iname="icon-copy" ipackage="icons" iexplain="Similar Pages"}</a>
		{/if}

		{if $gContent->isLocked()}
			{if $gContent->hasAdminPermission() or ($gContent->isOwner() and $gContent->hasUserPermission( 'p_wiki_lock_page' ) and $gBitSystem->isFeatureActive( 'wiki_usrlock' ))}
				<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;action=unlock">{booticon iname="icon-lock" ipackage="icons" iexplain="Unlock this page"}</a>
			{else}
				{booticon iname="icon-lock" ipackage="icons" iexplain="Locked"}
			{/if}
		{else}
			{assign var=format_guid value=$gContent->mInfo.format_guid}
			{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y' or $gContent->hasAdminPermission()}
				{if $gContent->hasUpdatePermission()}
					{if $gContent->hasAdminPermission() or ($gContent->isOwner() and $gContent->hasUserPermission( 'p_wiki_lock_page' ) and $gBitSystem->isFeatureActive( 'wiki_usrlock' ))}
						<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;action=lock">{booticon iname="icon-unlock" ipackage="icons" iexplain="Lock this page"}</a>
					{/if}
					<a href="{$smarty.const.WIKI_PKG_URL}edit.php?page_id={$gContent->mInfo.page_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="Edit"}</a>
				{/if}
			{/if}

			{if $gBitSystem->isFeatureActive( 'wiki_undo' ) and $gContent->hasUserPermission('p_wiki_rollback')}
				<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;undo=1">{booticon ipackage=icons iname=icon-undo iexplain="Undo last edit"}</a>
			{/if}

			{if $gBitSystem->isFeatureActive( 'wiki_history' ) and $gContent->hasUserPermission('p_wiki_view_history')}
				<a href="{$smarty.const.WIKI_PKG_URL}page_history.php?page_id={$gContent->mInfo.page_id}" rel="nofollow">{booticon iname="icon-time" ipackage="icons" iexplain=History}</a>
			{/if}
		{/if}

		{if ($structureInfo.structure_id) && ($gStructure->mInfo.creator_user_id == $gBitUser->mUserId || $gContent->hasUserPermission( 'p_wiki_admin_book' ))}
			<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?structure_id={$structureInfo.structure_id}">{booticon iname="icon-cogs"  ipackage="icons"  iexplain="Edit book"}</a>
		{elseif is_a($gContent,'BitBook') && $gContent->hasUpdatePermission()}
			<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?content_id={$gContent->mContentId}">{booticon iname="icon-cogs"  ipackage="icons"  iexplain="Edit book"}</a>
		{/if}

		{if $gBitSystem->isFeatureActive( 'wiki_uses_s5' )}
			<a href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;s5=1">{booticon ipackage="icons" iname="icon-facetime-video" iexplain="s5 slideshow"}</a>
		{/if}

		{* we hide this link from browsers that can interpret css since we use css to determine what is printed and what not *}
		{if $gContent->hasUserPermission( 'p_liberty_print' )}
			<a title="{tr}print{/tr}" style="display:none;" href="{$smarty.const.WIKI_PKG_URL}print.php?{if $structureInfo.root_structure_id}structure_id={$structureInfo.root_structure_id}{else}page_id={$gContent->mInfo.page_id}{/if}">{booticon iname="icon-print"  ipackage="icons"  iexplain="Print"}</a>
		{/if}

		{if $gBitUser->isRegistered() and $gBitUser->mUserId && $gBitSystem->isFeatureActive( 'users_watches' ) }
			{if $user_watching_page eq 'y'}
				<a title="{tr}stop monitoring this page{/tr}" href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;watch_event=wiki_page_changed&amp;watch_object={$gContent->mInfo.page_id}&amp;watch_action=remove">{booticon ipackage="icons" iname="icon-eye-close" iexplain="Stop monitoring"}</a>
			{else}
				<a title="{tr}monitor this page{/tr}" href="{$smarty.const.WIKI_PKG_URL}index.php?page_id={$gContent->mInfo.page_id}&amp;watch_event=wiki_page_changed&amp;watch_object={$gContent->mInfo.page_id}&amp;watch_action=add">{booticon iname="icon-eye"  ipackage="icons"  iexplain="Monitor"}</a>
			{/if}
		{/if}

		{if $gBitSystem->isFeatureActive( 'users_watches' ) and $gContent->hasUserPermission('p_users_admin')}
			<a href="{$smarty.const.WIKI_PKG_URL}page_watches.php?page_id={$gContent->mInfo.page_id}">{booticon ipackage="icons" iname="icon-user" iexplain="Watches"}</a>
		{/if}

{* seem to be broken
		{if $gBitUser->hasPermission( 'p_wiki_admin' )}
			<li><a href="{$smarty.const.WIKI_PKG_URL}export_wiki_pages.php?page_id={$gContent->mInfo.page_id}">{booticon iname="icon-cloud-upload" ipackage="icons" iexplain="Export"}</a></li>
		{/if}
*}

		{if $gBitSystem->isFeatureActive( 'wiki_uses_slides' )}
			{if $show_slideshow eq 'y'}
				<a href="{$smarty.const.WIKI_PKG_URL}slideshow.php?page_id={$gContent->mInfo.page_id}">{booticon ipackage="icons" iname="icon-facetime-video" iexplain="Slideshow"}</a>
			{elseif $structure eq 'y'}
				<a href="{$smarty.const.WIKI_PKG_URL}slideshow2.php?structure_id={$page_info.structure_id}">{booticon ipackage="icons" iname="icon-facetime-video" iexplain="Slideshow"}</a>
			{/if}
		{/if}

		{if $gContent->mInfo.title ne 'SandBox' && !$gContent->isLocked()}
			{if $gContent->isOwner() || $gContent->hasUserPermission( 'p_wiki_remove_page' )}
				<a title="{tr}remove this page{/tr}" href="{$smarty.const.WIKI_PKG_URL}remove_page.php?page_id={$gContent->mInfo.page_id}&amp;version=last">{booticon iname="icon-trash" ipackage="icons" iexplain="Delete"}</a>
			{/if}
		{/if}

		{if $gBitSystem->isFeatureActive( 'wiki_backlinks' ) and $backlinks}
			<div class="btn-group">
				<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
				  <i class="icon-link"></i>
				  <span class="caret"></span>
				</button>
				<ul class="dropdown-menu pull-right">
					{foreach key=contentId item=backPage from=$backlinks}
						<li><a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$contentId}">{$backPage|escape|truncate:30:"&hellip":true}</a></li>
					{/foreach}
				</ul>
			  </div>
		{/if}

		{if count($showstructs) gt 0}
			<select id="sel-structures" name="page" onchange="javascript:BitBase.go(this)">
				<option value="">{tr}Wiki Books{/tr} &hellip;</option>
				{section name=struct loop=$showstructs}
					<option value="{$smarty.const.WIKI_PKG_URL}index.php?structure_id={$showstructs[struct].structure_id}">
						{$showstructs[struct].root_title}
					</option>
				{/section}
			</select>
		{/if}
	</div>
{/if}
{/strip}
