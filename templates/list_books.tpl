{strip}

<div class="display structure">
<div class="header">
	<h1>{tr}Wiki Books{/tr}</h1>
</div>

<div class="body">

	<table class="panel">
		<tr>
			<th>{tr}WikiBook{/tr}</th>
			<th>{tr}Action{/tr}</th>
		</tr>

		{cycle values="even,odd" print=false}
		{section loop=$channels name=ix}

		<tr class="{cycle}">
			<td>
				<a href="{$smarty.const.WIKI_PKG_URL}index.php?structure_id={$channels[ix].structure_id}">
					{$channels[ix].title|escape}
				</a>
			</td>
			<td class="actionicon">
				{if ($channels[ix].creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'p_wiki_admin_book' )}
					<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?structure_id={$channels[ix].structure_id}">{booticon iname="icon-cogs"  ipackage="icons"  iexplain="edit book"}</a>
				{/if}
{* remove for now because it's broken, and I can't find reason to fix - spiderr
				<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?action=export&structure_id={$channels[ix].structure_id}">{biticon ipackage="icons" iname="document-save-as" iexplain="export pages"}</a>
				<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?action=export_tree&structure_id={$channels[ix].structure_id}">{biticon ipackage="icons" iname="already dealt with" iexplain="dump tree"}</a>
*}
				{if $gBitUser->isAdmin()}
					{if $gBitSystem->isPackageActive( 'nexus' )}
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?structure_id={$channels[ix].structure_id}&amp;action=convert_structure">{biticon ipackage="icons" iname="folder-remote" iexplain="create menu from structure"}</a>
					{/if}
					{* remove this feature for now - needs fixing or 'real' removal
					<a href="{$smarty.const.WIKI_PKG_URL}create_webhelp.php?structure_id={$channels[ix].structure_id}">{biticon ipackage="icons" iname="help-browser" iexplain="create webhelp"}</a>
					*}
				{/if}
				{*if $channels[ix].webhelp eq 'y'}
					<a href="{$smarty.const.BITHELP_PKG_URL}/{$channels[ix].title|escape}/index.html">{biticon ipackage="icons" iname="help-contents" iexplain="view webhelp"}</a>
				{/if*}
				{if ($channels[ix].creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'p_wiki_admin_book' )}
					<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?action=remove&structure_id={$channels[ix].structure_id}">{booticon iname="icon-trash" ipackage="icons" iexplain="remove"}</a>
				{/if}
			</td>
		</tr>

		{sectionelse}

		<tr class="norecords">
			<td colspan="2">{tr}No records found{/tr}</td>
		</tr>

		{/section}
	</table>

</div><!-- end .body -->

{pagination}

</div><!-- end .structure -->

{/strip}
