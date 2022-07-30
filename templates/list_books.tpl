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
					<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?structure_id={$channels[ix].structure_id}">{booticon iname="fa-gears" iexplain="edit book"}</a>
				{/if}
				{if $gBitUser->isAdmin()}
					{if $gBitSystem->isPackageActive( 'nexus' )}
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?structure_id={$channels[ix].structure_id}&amp;action=convert_structure">{booticon iname="fa-sitemap" iexplain="create menu from structure"}</a>
					{/if}
				{/if}
				{if ($channels[ix].creator_user_id == $gBitUser->mUserId) || $gBitUser->hasPermission( 'p_wiki_admin_book' )}
					<a href="{$smarty.const.WIKI_PKG_URL}edit_book.php?action=remove&structure_id={$channels[ix].structure_id}&tk={$gBitUser->mTicket}">{booticon iname="fa-trash" iexplain="remove"}</a>
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
