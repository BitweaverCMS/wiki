<div class="floaticon">{bithelp}</div>

<div class="admin ewiki">
<div class="header">
<h1>Admin external wikis</h1>
</div>

<div class="body">

<h2>Create/Edit External Wiki</h2>
<form action="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php" method="post">
<input type="hidden" name="extwiki_id" value="{$extwiki_id|escape}" />
<table class="panel">
<tr><td>name:</td><td><input type="text" maxlength="255" size="10" name="name" value="{$info.name|escape}" /></td></tr>
<tr><td>URL (use $page to be replaced by the page name in the URL example: http://www.example.com/{$smarty.const.WIKI_PKG_URL}index.php?page=$page):</td><td><input type="text" maxlength="255" size="40" name="extwiki" value="{$info.extwiki|escape}" /></td></tr>
<tr class="panelsubmitrow"><td colspan="2"><input type="submit" class="btn btn-default" name="save" value="Save" /></td></tr>
</table>
</form>

<h2>External Wiki</h2>
<table class="table data">
<tr>
<th><a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">name</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'extwiki_desc'}extwiki_asc{else}extwiki_desc{/if}">extwiki</a></th>
<th>action</th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td>{$channels[user].name}</td>
<td>{$channels[user].extwiki}</td>
<td>
   &nbsp;&nbsp;<a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].extwiki_id}" onclick="return confirm('Are you sure you want to delete this external wiki?')" title="Click here to delete this external wiki">{booticon iname="icon-trash" ipackage="icons" iexplain="remove"}</a>&nbsp;&nbsp;
   <a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;extwiki_id={$channels[user].extwiki_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit"}</a>
</td>
</tr>
{sectionelse}
<tr class="norecords"><td colspan="3">No records found</td></tr>
{/section}
</table>

</div><!-- end .body -->

{pagination}

</div> {* end .admin *}
