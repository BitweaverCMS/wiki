<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/remove_page.php,v 1.4 2006/04/11 13:10:33 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: remove_page.php,v 1.4 2006/04/11 13:10:33 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
include_once( WIKI_PKG_PATH.'lookup_page_inc.php' );
include_once( WIKI_PKG_PATH.'page_setup_inc.php' );

$gBitSystem->verifyPackage( 'wiki' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( "No page indicated" );
}

$gBitSystem->verifyPermission( 'p_wiki_remove_page' );

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header ("location: ".BIT_ROOT_URL );
		die;
	} else {
		vd( $gContent->mErrors );
	}
}

$gBitSystem->setBrowserTitle( tra( 'Confirm delete of: ' ).$gContent->getTitle() );
$formHash['remove'] = TRUE;
$formHash['page_id'] = $_REQUEST['page_id'];
$msgHash = array(
	'label' => tra( 'Delete WikiPage' ),
	'confirm_item' => $gContent->getTitle(),
	'warning' => tra( 'All previous versions of this page will be completely deleted.<br />This cannot be undone!' ),
);
$gBitSystem->confirmDialog( $formHash,$msgHash );

?>
