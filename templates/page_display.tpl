{strip}
<div class="body"{if $user_dbl eq 'y' and $dblclickedit eq 'y' and $gBitUser->hasPermission( 'bit_p_edit' )} ondblclick="location.href='{$gBitLoc.WIKI_PKG_URL}edit.php?page_id={$pageInfo.page_id}';"{/if}>
	<div class="content">
		{if $gBitSystemPrefs.liberty_auto_display_attachment_thumbs eq 'y'}
			{include file="bitpackage:liberty/storage_thumbs.tpl"}
		{/if}
		{$parsed}
	</div> <!-- end .content -->
</div> <!-- end .body -->
{/strip}
