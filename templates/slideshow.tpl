<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<style type="text/css">
<!--
	@import url({$smarty.const.THEMES_PKG_URL}base.css);
	@import url({$smarty.const.THEMES_PKG_URL}styles/{$site_slide_style}/{$site_slide_style}.css);
	@import url({$smarty.const.THEMES_PKG_URL}styles/{$site_slide_style}/slideshow.css);
-->
</style>
	<title>{$page_info.pageName}</title>
</head>
<body>
<div style="text-align: center;">
<div id="slideshow">
<div class="header">
	<h1>{$page_info.pageName}</h1>
</div> <!-- end .header -->
<div class="body">
	<div class="content">
		{$slide_data}
	</div><!-- end .content -->
</div><!-- end .body -->

<div class="structure">
<table>
	<tr class="navigation">
	{if $structure eq 'y'}
		<td width="33%" align="left">
			<a href="{$smarty.const.WIKI_PKG_URL}slideshow2.php?structure_id={$prev_info.structure_id}">{$prev_info.pageName}</a>
		</td>
		<td width="34%" align="center">
			<a href="{$smarty.const.WIKI_PKG_URL}slideshow2.php?structure_id={$home_info.structure_id}">{$home_info.pageName}</a>
		</td>
		<td width="33%" align="right">
			<a href="{$smarty.const.WIKI_PKG_URL}slideshow2.php?structure_id={$next_info.structure_id}">{$next_info.pageName}</a>
		</td>
	{else}
		<td width="33%" align="left">
		{if $slide_prev_title}
			<a href="{$smarty.const.WIKI_PKG_URL}slideshow.php?page={$page}&amp;slide={$prev_slide}">{$slide_prev_title}</a>
		{else}
			&nbsp;
		{/if}
		</td>
		<td width="34%" align="center">
			{$current_slide} of {$total_slides}
		</td>
		<td width="33%" align="right">
		{if $slide_next_title}
			<a href="{$smarty.const.WIKI_PKG_URL}slideshow.php?page={$page}&amp;slide={$next_slide}">{$slide_next_title}</a>
		{else}
			<a href="{$smarty.const.WIKI_PKG_URL}index.php?page={$page_info.pageName}" title="{tr}back to{/tr} {$page_info.pageName}">{$page_info.pageName}</a>
		{/if}
		</td>
	{/if}
	</tr>
</table>
</div><!-- end .structure -->

</div><!-- end #slideshow -->
</div>

</body>
</html>
