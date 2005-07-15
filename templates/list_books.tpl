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
				<a href="{$gBitLoc.WIKI_PKG_URL}index.php?structure_id={$channels[ix].structure_id}">
					{$channels[ix].title}
				</a>
			</td>
			<td class="actionicon">
				{if ($channels[ix].creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'bit_p_admin_books' )}
					<a href="{$gBitLoc.WIKI_PKG_URL}edit_book.php?structure_id={$channels[ix].structure_id}">{biticon ipackage=liberty iname="settings" iexplain="edit book"}</a>
				{/if}
{* remove for now because it's broken, and I can't find reason to fix - spiderr
				<a href="{$gBitLoc.WIKI_PKG_URL}edit_book.php?action=export&structure_id={$channels[ix].structure_id}">{biticon ipackage=liberty iname="export" iexplain="export pages"}</a>
				<a href="{$gBitLoc.WIKI_PKG_URL}edit_book.php?action=export_tree&structure_id={$channels[ix].structure_id}">{biticon ipackage="wiki" iname="tree" iexplain="dump tree"}</a>
*}
				{if $gBitUser->isAdmin()}
					{if $gBitSystem->isPackageActive( 'nexus' )}
						<a href="{$gBitLoc.NEXUS_PKG_URL}menus.php?structure_id={$channels[ix].structure_id}&amp;action=convert_structure">{biticon ipackage="liberty" iname="tree" iexplain="create menu from structure"}</a>
					{/if}
					<a href="{$gBitLoc.WIKI_PKG_URL}create_webhelp.php?structure_id={$channels[ix].structure_id}">{biticon ipackage="wiki" iname="webhelp" iexplain="create webhelp"}</a>
				{/if}
				{if $channels[ix].webhelp eq 'y'}
					<a href="{$gBitLoc.BITHELP_PKG_URL}/{$channels[ix].title}/index.html">{biticon ipackage="wiki" iname="webhelp_toc" iexplain="view webhelp"}</a>
				{/if}
				{if ($channels[ix].creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'bit_p_admin_books' )}
					<a href="{$gBitLoc.WIKI_PKG_URL}edit_book.php?action=remove&structure_id={$channels[ix].structure_id}">{biticon ipackage=liberty iname="delete" iexplain="remove"}</a>
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
