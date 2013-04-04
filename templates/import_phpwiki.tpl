<div class="floaticon">{bithelp}</div>

<div class="admin phpwiki">
<div class="header">
<h1>{tr}Import pages from a PHPWiki Dump{/tr}</h1>
</div>

<div class="body">

<form method="post" action="{$smarty.const.WIKI_PKG_URL}admin/import_phpwiki.php">
<table class="panel">
<tr>
  <td>{tr}Path to where the dumped files are (relative to tiki basedir with trailing slash ex: dump/):{/tr}</td>
  <td><input type="text" name="path" /></td>
</tr>
<tr>
  <td>{tr}Overwrite existing pages if the name is the same{/tr}:</td>
  <td><input type="radio" name="crunch" value="y" /> {tr}yes{/tr}<br />
	<input checked="checked" type="radio" name="crunch" value="n" /> {tr}no{/tr}</td>
</tr>
<tr>
  <td>{tr}Previously remove existing page versions{/tr}:</td>
  <td><input type="radio" name="remo" value="y" /> {tr}yes{/tr}<br />
	<input checked="checked" type="radio" name="remo" value="n" /> {tr}no{/tr}</td>
</tr>
<tr class="panelsubmitrow">
  <td colspan="2"><input type="submit" class="btn" name="import" value="{tr}Import{/tr}" /></td>
</tr>
</table>
</form>

<br />

{if $result eq 'y'}
<table class="data">
<tr>
  <th>{tr}Page{/tr}</th>
  <th>{tr}version{/tr}</th>
  <th>{tr}excerpt{/tr}</th>
  <th>{tr}result{/tr}</th>
</tr>
{cycle values="even,odd" print=false}
{section name=ix loop=$lines}
<tr class="{cycle}">
  <td>{$lines[ix].page}</td>
  <td>{$lines[ix].version}</td>
  <td>{$lines[ix].part}</td>
  <td>{$lines[ix].msg}</td>
</tr>
{sectionelse}
<tr class="norecords"><td colspan="4">{tr}No records found{/tr}</td></tr>
{/section}
</table>
{/if}

</div> {* end .body *}
</div> {* end .admin *}
