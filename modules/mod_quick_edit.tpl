{* $Header: /cvsroot/bitweaver/_bit_wiki/modules/mod_quick_edit.tpl,v 1.1 2005/06/19 06:12:45 bitweaver Exp $ *}
{strip}
{if $gBitUser->hasPermission( 'bit_p_edit' )}
	{bitmodule title="$moduleTitle" name="quick_edit"}
		{form method="get" ipackage=wiki ifile="edit.php"}
			<div class="row">
				<input type="text" size="15" name="page" />
			</div>
			<div class="row submit">
				<input type="submit" name="quickedit" value="{tr}edit{/tr}" />
			</div>
		{/form}
	{/bitmodule}
{/if}
{/strip}
