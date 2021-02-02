<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage modules
 */

/**
 * required setup
 */
require_once( WIKI_PKG_CLASS_PATH.'BitPage.php' );
$wikilib = new WikiLib();
$ranking = $wikilib->list_received_pages(0, -1, $sort_mode = 'title_asc', '');
$_template->tpl_vars['modReceivedPages'] = new Smarty_variable( $ranking["cant"]);
?>
