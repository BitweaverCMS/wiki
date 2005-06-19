<div class="admin wiki">
<div class="header">
<h1>{tr}Rename page{/tr}: {$page}</h1>
</div>

<div class="body">
<form action="{$gBitLoc.WIKI_PKG_URL}rename_page.php" method="post">
	<input type="hidden" name="oldpage" value="{$page|escape}" />
	<input type="hidden" name="page" value="{$page|escape}" />
	<table class="panel">
		<tr><td>
			{tr}New name{/tr}:</td><td>
			<input type="text" name="newpage" value="{$page|escape}" size="35" />
			<input type="submit" name="rename" value="{tr}Rename{/tr}" />
		</td></tr>
	</table>
</form>
</div> {* end .body *}
</div> {* end .admin *}
