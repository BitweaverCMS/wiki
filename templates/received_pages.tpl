<div class="floaticon">{bithelp}</div>

<div class="display wiki">
<div class="header">
<h1>{tr}Received pages{/tr}</h1>
</div>

<div class="body">

{if $received_page_id > 0 or $view eq 'y'}
<h2>{tr}Preview{/tr}</h2>
<div class="wikibody">{$parsed}</div>
{/if}
{if $received_page_id > 0}
<h2>{tr}Edit received page{/tr}</h2>
<form action="{$smarty.const.WIKI_PKG_URL}received_pages.php" method="post">
<input type="hidden" name="received_page_id" value="{$received_page_id|escape}" />
<table class="panel">
<tr><td>
{tr}Name{/tr}:</td><td>
<input type="text" name="title" value="{$title|escape}" />
</td></tr>
<tr><td>
{tr}Data{/tr}:</td><td>
<textarea name="data" rows="10" cols="50">{$data|escape}</textarea>
</td></tr>
<tr><td>
{tr}Comment{/tr}:</td><td>
<input type="text" name="comment" value="{$comment|escape}" />
</td></tr>
<tr><td colspan="2"><input type="submit" name="preview" value="{tr}Preview{/tr}" /> <input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
{/if}

<table class="find">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="{$smarty.const.WIKI_PKG_URL}received_pages.php">
     <input type="text" name="find" />
     <input type="submit" name="search" value="{tr}find{/tr}" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>

<table class="data">
<tr>
<th><a href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'received_page_id_desc'}received_page_id_asc{else}received_page_id_desc{/if}">{tr}ID{/tr}</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}name{/tr}</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'received_date_desc'}received_date_asc{else}received_date_desc{/if}">{tr}Date{/tr}</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'received_from_site_desc'}received_from_site_asc{else}received_from_site_desc{/if}">{tr}Site{/tr}</a></th>
<th><a href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'received_from_user_desc'}received_from_user_asc{else}received_from_user_desc{/if}">{tr}User{/tr}</a></th>
<th>{tr}action{/tr}</th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td>{$channels[user].received_page_id}</td>
{if $channels[user].exists eq 'y'}
<td><span class="highlight">{$channels[user].title}</span></td>
{else}
<td>{$channels[user].title}</td>
{/if}
<td>{$channels[user].received_date|bit_short_date}</td>
<td>{$channels[user].received_from_site}</td>
<td>{$channels[user].received_from_user}</td>
<td>
   <a title="{tr}edit{/tr}" href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;received_page_id={$channels[user].received_page_id}">{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
   <a title="{tr}view{/tr}" href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;view={$channels[user].received_page_id}">{biticon ipackage=liberty iname="view" iexplain="view"}</a>
   <a title="{tr}accept{/tr}" href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;accept={$channels[user].received_page_id}">{biticon ipackage=liberty iname="accept" iexplain="accept"}</a>
   <a title="{tr}remove{/tr}" href="{$smarty.const.WIKI_PKG_URL}received_pages.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].received_page_id}">{biticon ipackage=liberty iname="delete" iexplain="delete"}</a>
</td>
</tr>
{sectionelse}
	<tr class="norecords"><td colspan="6">{tr}No records found{/tr}</td></tr>
{/section}
</table>

</div> {* end .body *}

{pagination}

</div> {* end .wiki *}
