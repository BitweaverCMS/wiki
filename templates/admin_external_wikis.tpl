<div class="floaticon">{bithelp}</div>

<div class="admin ewiki">
<div class="header">
<h1>{tr}Admin external wikis{/tr}</h1>
</div>

<div class="body">

<h2>{tr}Create/Edit External Wiki{/tr}</h2>
<form action="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php" method="post">
<input type="hidden" name="extwiki_id" value="{$extwiki_id|escape}" />
<table class="panel">
<tr><td>{tr}name{/tr}:</td><td><input type="text" maxlength="255" size="10" name="name" value="{$info.name|escape}" /></td></tr>
<tr><td>{tr}URL (use $page to be replaced by the page name in the URL example: http://www.example.com/{$smarty.const.WIKI_PKG_URL}index.php?page=$page){/tr}:</td><td><input type="text" maxlength="255" size="40" name="extwiki" value="{$info.extwiki|escape}" /></td></tr>
<tr class="panelsubmitrow"><td colspan="2"><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>

<h2>{tr}External Wiki{/tr}</h2>
<table class="data">
<tr>
<th><a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'extwiki_desc'}extwiki_asc{else}extwiki_desc{/if}">{tr}extwiki{/tr}</a></th>
<th>{tr}action{/tr}</th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td>{$channels[user].name}</td>
<td>{$channels[user].extwiki}</td>
<td>
   &nbsp;&nbsp;<a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].extwiki_id}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this external wiki?{/tr}')" title="Click here to delete this external wiki">{biticon ipackage=liberty iname="delete" iexplain="remove"}</a>&nbsp;&nbsp;
   <a href="{$smarty.const.WIKI_PKG_URL}admin/admin_external_wikis.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;extwiki_id={$channels[user].extwiki_id}">{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
</td>
</tr>
{sectionelse}
<tr class="norecords"><td colspan="3">{tr}No records found{/tr}</td></tr>
{/section}
</table>

</div> {* end .body *}

{pagination}

</div> {* end .admin *}
