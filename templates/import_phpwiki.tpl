<div class="floaticon">{bithelp}</div>

<div class="admin phpwiki">
<div class="header">
<h1>Import pages from a PHPWiki Dump</h1>
</div>

<div class="body">

<form method="post" action="{$smarty.const.WIKI_PKG_URL}admin/import_phpwiki.php">
<table class="panel">
<tr>
  <td>Path to where the dumped files are (relative to tiki basedir with trailing slash ex: dump/):</td>
  <td><input type="text" name="path" /></td>
</tr>
<tr>
  <td>Overwrite existing pages if the name is the same:</td>
  <td><input type="radio" name="crunch" value="y" /> yes<br />
	<input checked="checked" type="radio" name="crunch" value="n" /> no</td>
</tr>
<tr>
  <td>Previously remove existing page versions:</td>
  <td><input type="radio" name="remo" value="y" /> yes<br />
	<input checked="checked" type="radio" name="remo" value="n" /> no</td>
</tr>
<tr class="panelsubmitrow">
  <td colspan="2"><input type="submit" class="btn btn-default" name="import" value="Import" /></td>
</tr>
</table>
</form>

<br />

{if $result eq 'y'}
<table class="table data">
<tr>
  <th>Page</th>
  <th>version</th>
  <th>excerpt</th>
  <th>result</th>
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
<tr class="norecords"><td colspan="4">No records found</td></tr>
{/section}
</table>
{/if}

</div> {* end .body *}
</div> {* end .admin *}
