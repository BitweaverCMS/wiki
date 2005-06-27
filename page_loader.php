<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/page_loader.php,v 1.1.1.1.2.1 2005/06/27 17:47:43 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_loader.php,v 1.1.1.1.2.1 2005/06/27 17:47:43 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
include_once( '../../bit_setup_inc.php' );
include_once (HTML_PKG_PATH.'htmlpages_lib.php');
$refresh = 1000 * $_REQUEST["refresh"];
?>
<html>
	<head>
		<script language = 'Javascript' type = 'text/javascript'>
		<?php
		$zones = $htmlpageslib->list_html_page_content($_REQUEST["title"], 0, -1, 'zone_asc', '');
		$cmds = array();
		for ($i = 0; $i < count($zones["data"]); $i++) {
			$cmd = 'top.document.getElementById("' . $zones["data"][$i]["zone"] . '").innerHTML="' . $zones["data"][$i]["content"] . '";';
			echo $cmd;
		}
		?>
		</script>
	</head>
	<body onLoad = "window.setInterval('location.reload()','<?php echo $refresh ?>');">
		<?php
		//print_r($cmds);
		?>
	</body>
</html>