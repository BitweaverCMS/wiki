{strip}
{if $gBitUser->hasPermission('p_wiki_view_page')}
{bitmodule title="$moduleTitle" name="search_box"}
	{form ipackage=wiki ifile="list_pages.php"}
		<div class="form-group">
			<input name="find_title" size="14" type="text" accesskey="s" value="{$find}" />
		</div>
		<div class="form-group submit">
			<input type="submit" class="wikiaction" name="search" value="{tr}go{/tr}" />
		</div>
	{/form}
{/bitmodule}
{/if}
{/strip}
