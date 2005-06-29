{strip}
{if $gBitUser->hasPermission( 'bit_p_view_tabs_and_tools' )}
	<div class="floaticon">
		{if $print_page ne 'y' && count($showstructs) ne 0}
			<select name="page" onchange="go(this)">
				<option value="">{tr}Wiki Books{/tr}...</option>
				{section name=struct loop=$showstructs}
					<option value="{$gBitLoc.WIKI_PKG_URL}index.php?structure_id={$showstructs[struct].structure_id}">
						{$showstructs[struct].root_title}
					</option>
				{/section}
			</select>
		{/if}
		{if $print_page ne 'y'}
			{if $lock}
				{biticon ipackage="wiki" iname="locked" iexplain="locked"}{$info.editor|userlink}
			{else}
				{assign var=format_guid value=$pageInfo.format_guid}
				{if $gLibertySystem->mPlugins.$format_guid.is_active eq 'y'}
					{if $gBitUser->hasPermission( 'bit_p_edit' ) or $page eq 'SandBox'}
						<a href="{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}" {if $beingEdited eq 'y'}{*popup_init src="`$gBitLoc.THEMES_PKG_URL`js/overlib.js"*}{popup text="$semUser" width="-1"}{/if}>{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
					{/if}
				{/if}
			{/if}

			{if $gBitSystem->isPackageActive( 'stickies' ) && $gBitUser->hasPermission('bit_p_stickies_edit') }
				{if ($structureInfo.structure_id)}{assign var='stickyRequest' value="structure_id=`$structureInfo.structure_id`"}
				{else}{assign var='stickyRequest' value="notated_content_id=`$pageInfo.content_id`"}{/if}
				<a href="{$gBitLoc.STICKIES_PKG_URL}edit.php?{$stickyRequest}">{biticon ipackage=stickies iname="sticky_note" iexplain="add sticky note"}</a>
			{/if}

			{if ($structureInfo.structure_id) && (($gStructure->mInfo.creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'bit_p_admin_books' )) }
				<a href="{$gBitLoc.WIKI_PKG_URL}edit_book.php?structure_id={$structureInfo.structure_id}">{biticon ipackage=liberty iname="settings" iexplain="edit book"}</a>
			{/if}

			{if $gBitSystemPrefs.wiki_uses_s5 eq 'y'}
				<a href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;s5=1" onclick="return confirm('this works best in gecko based browsers (mozilla, firefox) or opera (press F11)')">{biticon ipackage=wiki iname="s5" iexplain="s5 slideshow"}</a>
			{/if}

			{if $cached_page eq 'y'}
				<a title="{tr}refresh{/tr}" href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;refresh=1">{biticon ipackage=liberty iname="refresh" iexplain="refresh"}</a>
			{/if}
			{if $gBitUser->hasPermission( 'bit_p_print' )}
				<a title="{tr}print{/tr}" style="display:none;" href="{$gBitLoc.WIKI_PKG_URL}print.php?{if $structureInfo.root_structure_id}structure_id={$structureInfo.root_structure_id}{else}page_id={$pageInfo.page_id}{/if}">{biticon ipackage=liberty iname="print" iexplain="print"}</a>
			{/if}
			{if $gBitSystem->isPackageActive( 'pdf' ) && $gContent->hasUserPermission( 'bit_p_pdf_generation' )}
				{if $structureInfo.root_structure_id}
					<a title="{tr}create PDF{/tr}" href="{$gBitLoc.PDF_PKG_URL}?structure_id={$structureInfo.root_structure_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
				{else}
					<a title="{tr}create PDF{/tr}" href="{$gBitLoc.PDF_PKG_URL}?content_id={$pageInfo.content_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
				{/if}
			{/if}
			{if $user and $gBitSystem->isPackageActive( 'notepad' ) and $gBitUser->hasPermission( 'bit_p_notepad' )}
				<a title="{tr}Save{/tr}" href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;savenotepad=1">{biticon ipackage="wiki" iname="save" iexplain="save"}</a>
			{/if}
			{if $gBitUser->mUserId && $gBitSystem->isFeatureActive( 'feature_user_watches' ) }
				{if $user_watching_page eq 'y'}
					<a title="{tr}stop monitoring this page{/tr}" href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;watch_event=wiki_page_changed&amp;watch_object={$pageInfo.page_id}&amp;watch_action=remove">{biticon ipackage="users" iname="unwatch" iexplain="stop monitoring"}</a>
				{else}
					<a title="{tr}monitor this page{/tr}" href="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}&amp;watch_event=wiki_page_changed&amp;watch_object={$pageInfo.page_id}&amp;watch_action=add">{biticon ipackage="users" iname="watch" iexplain="monitor"}</a>
				{/if}
			{/if}
			{if $pageInfo.title ne 'SandBox'}
				{if $gBitUser->hasPermission( 'bit_p_remove' )}
					<a title="{tr}remove this page{/tr}" href="{$gBitLoc.WIKI_PKG_URL}remove_page.php?page_id={$pageInfo.page_id}&amp;version=last">{biticon ipackage=liberty iname="delete" iexplain="delete"}</a>
				{/if}
			{/if}
			{if $gBitSystemPrefs.feature_backlinks eq 'y' and $backlinks}
				<select name="page" onchange="go(this)">
					<option value="{$gBitLoc.WIKI_PKG_URL}index.php?page_id={$pageInfo.page_id}">{tr}backlinks{/tr}...</option>
					{foreach key=contentId item=backPage from=$backlinks}
						<option value="{$gBitLoc.BIT_ROOT_URL}index.php?content_id={$contentId}">{$backPage}</option>
					{/foreach}
				</select>
			{/if}
		{/if}<!-- end print_page -->
	</div>
{/if}
{/strip}
