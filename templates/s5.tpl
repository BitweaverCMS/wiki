<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$gContent->mInfo.title}</title>
	<meta name="generator" content="S5" />
	<meta name="version" content="S5 1.0" />
	<meta name="presdate" content="20041007" />
	<meta name="author" content="{displayname user_id=`$gContent->mInfo.user_id` nolink='yes'}" />
	<meta name="company" content="" />
	<link rel="stylesheet" href="{$gBitLoc.THEMES_PKG_URL}s5/i18n/slides.css" type="text/css" media="projection" id="slideProj" />
	<link rel="stylesheet" href="{$gBitLoc.THEMES_PKG_URL}s5/i18n/print.css" type="text/css" media="print" id="slidePrint" />
	<link rel="stylesheet" href="{$gBitLoc.THEMES_PKG_URL}s5/opera.css" type="text/css" media="projection" id="operaFix" />
	<script src="{$gBitLoc.THEMES_PKG_URL}s5/slides.js" type="text/javascript"></script>
</head>
<body>
	<div class="layout">
		<div id="currentSlide"></div>
		<div id="header"></div>
		<div id="footer">
			<h1>{$gContent->mInfo.title}</h1>
			<h2>{$gContent->mInfo.description}</h2>
			<div id="controls"></div>
		</div>

		<div class="presentation">
			{$s5}
		</div>
	</div>
</body>
</html>
