<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/copyrights.php,v 1.1.1.1.2.3 2005/07/26 15:50:32 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: copyrights.php,v 1.1.1.1.2.3 2005/07/26 15:50:32 drewslater Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
$gBitSystem->isFeatureActive( 'wiki_feature_copyrights', tra("The copyright management feature is not enabled.") );

$gBitUser->hasPermission( 'bit_p_edit_copyrights' );
require_once( WIKI_PKG_PATH.'copyrights_lib.php' );
require_once( WIKI_PKG_PATH.'lookup_page_inc.php' );

if (isset($_REQUEST['addcopyright'])) {
	if ($wiki_feature_copyrights == 'y' && isset($_REQUEST['copyrightTitle']) && isset($_REQUEST['copyrightYear'])
		&& isset($_REQUEST['copyrightAuthors']) && !empty($_REQUEST['copyrightYear']) && !empty($_REQUEST['copyrightTitle'])) {
		$copyrightYear = $_REQUEST['copyrightYear'];
		$copyrightTitle = $_REQUEST['copyrightTitle'];
		$copyrightAuthors = $_REQUEST['copyrightAuthors'];
		$copyrightslib->add_copyright($gContent->mPageId, $copyrightTitle, $copyrightYear, $copyrightAuthors, $gBitUser->mUserId);
	} else {
		$gBitSmarty->assign('msg', tra("You must supply all the information, including title and year."));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
}
if (isset($_REQUEST['editcopyright'])) {
	if ($wiki_feature_copyrights == 'y' && isset($_REQUEST['copyrightTitle']) && isset($_REQUEST['copyrightYear'])
		&& isset($_REQUEST['copyrightAuthors']) && !empty($_REQUEST['copyrightYear']) && !empty($_REQUEST['copyrightTitle'])) {
		$copyright_id = $_REQUEST['copyright_id'];
		$copyrightYear = $_REQUEST['copyrightYear'];
		$copyrightTitle = $_REQUEST['copyrightTitle'];
		$copyrightAuthors = $_REQUEST['copyrightAuthors'];
		$copyrightslib->edit_copyright($copyright_id, $copyrightTitle, $copyrightYear, $copyrightAuthors, $gBitUser->mUserId);
	} else {
		$gBitSmarty->assign('msg', tra("You must supply all the information, including title and year."));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
}
if (isset($_REQUEST['action']) && isset($_REQUEST['copyright_id'])) {
	if ($_REQUEST['action'] == 'up') {
		$copyrightslib->up_copyright($_REQUEST['copyright_id']);
	} elseif ($_REQUEST['action'] == 'down') {
		$copyrightslib->down_copyright($_REQUEST['copyright_id']);
	} elseif ($_REQUEST['action'] == 'delete') {
		$copyrightslib->remove_copyright($_REQUEST['copyright_id']);
	}
}
$copyrights = $copyrightslib->list_copyrights( $gContent->mPageId );
$gBitSmarty->assign('copyrights', $copyrights["data"]);
// Display the template
$gBitSystem->display( 'bitpackage:wiki/copyrights.tpl');
?>
