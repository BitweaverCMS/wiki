<h2>{tr}Rollback page{/tr}: {$pageInfo.title|escape} {tr}to_version{/tr}: {$version}</h2>
<div class="wikibody">{$preview.data}</div>
<div align="center">
<form action="{$smarty.const.WIKI_PKG_URL}rollback.php" method="post">
<input type="hidden"  name="page_id" value="{$pageInfo.page_id}" />
<input type="hidden" name="version" value="{$version|escape}" />
<input type="submit" name="rollback" value="{tr}rollback{/tr}" />
</form>
</div>
