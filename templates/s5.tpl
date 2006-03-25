<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$gContent->mInfo.title|escape}</title>
	<meta name="generator" content="S5" />
	<meta name="version" content="S5 1.0" />
	<meta name="author" content="{displayname user_id=`$gContent->mInfo.user_id` nolink='yes'}" />
	<meta name="company" content="" />
	<meta name="defaultView" content="slideshow" />
	<meta name="controlVis" content="hidden" />
	{assign var=s5theme value=default}
	<link rel="stylesheet" href="{$smarty.const.UTIL_PKG_URL}s5/ui/{$s5theme}/slides.css" type="text/css" media="projection" id="slideProj" />
	<link rel="stylesheet" href="{$smarty.const.UTIL_PKG_URL}s5/ui/{$s5theme}/outline.css" type="text/css" media="screen" id="outlineStyle" />
	<link rel="stylesheet" href="{$smarty.const.UTIL_PKG_URL}s5/ui/{$s5theme}/print.css" type="text/css" media="print" id="slidePrint" />
	<link rel="stylesheet" href="{$smarty.const.UTIL_PKG_URL}s5/ui/{$s5theme}/opera.css" type="text/css" media="projection" id="operaFix" />
	<script src="{$smarty.const.UTIL_PKG_URL}ui/{$s5theme}/slides.js" type="text/javascript"></script>
	{literal}
	<style type="text/css" media="all">
		.imgcon		{width: 525px; margin: 0 auto; padding: 0; text-align: center;}
		#anim		{width: 270px; height: 320px; position: relative; margin-top: 0.5em;}
		#anim img	{position: absolute; top: 42px; left: 24px;}
		img#me01	{top: 0; left: 0;}
		img#me02	{left: 23px;}
		img#me04	{top: 44px;}
		img#me05	{top: 43px;left: 36px;}
	</style>
	{/literal}
</head>
<body>
	<div class="layout">
		<div id="controls"><!-- DO NOT EDIT --></div>
		<div id="currentSlide"><!-- DO NOT EDIT --></div>
		<div id="header"></div>
		<div id="footer">
			<h1>{$gContent->mInfo.title|escape}</h1>
			<h2>{$gContent->mInfo.description|escape}</h2>
		</div>
	</div>

	<ol class="presentation">{$s5}</ol>
</body>
</html>
