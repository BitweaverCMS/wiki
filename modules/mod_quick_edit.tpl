{strip}
{if $gBitUser->hasPermission( 'p_wiki_edit_page' )}
	{bitmodule title="$moduleTitle" name="quick_edit"}
		{form method="get" ipackage=wiki ifile="edit.php"}
			<div class="form-group">
				<input type="text" size="15" name="page" />
			</div>
			<div class="form-group submit">
				<input type="submit" name="quickedit" value="{tr}edit{/tr}" />
			</div>
		{/form}
	{/bitmodule}
{/if}
{/strip}
