{strip}
<ul>
	<li><a class="item" href="{$gBitLoc.KERNEL_PKG_URL}admin/index.php?page=wiki">{tr}Wiki Settings{/tr}</a></li>
	{if $gBitSystem->isFeatureActive( 'feature_dump' ) and $gBitUser->hasPermission( 'bit_p_view' )}
		<li><a class="item" href="{$gBitLoc.STORAGE_PKG_URL}dump/{$bitdomain}new.tar">{tr}Backup{/tr}</a></li>
	{/if}
</ul>
{/strip}
