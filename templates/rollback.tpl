<h2>Rollback page: {$gContent->mInfo.title|escape} to_version: {$version}</h2>
<div class="wikibody">{$preview.data}</div>
<div align="center">
<form action="{$smarty.const.WIKI_PKG_URL}rollback.php" method="post">
<input type="hidden"  name="content_id" value="{$gContent->mContentId}" />
<input type="hidden" name="version" value="{$version|escape}" />
<input type="submit" class="btn btn-default" name="rollback" value="rollback" />
</form>
</div>
