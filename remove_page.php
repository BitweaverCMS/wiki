<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/remove_page.php,v 1.9 2007/10/25 17:14:15 nickpalmer Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: remove_page.php,v 1.9 2007/10/25 17:14:15 nickpalmer Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( WIKI_PKG_PATH.'BitPage.php');
include_once( WIKI_PKG_PATH.'lookup_page_inc.php' );

$gBitSystem->verifyPackage( 'wiki' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( tra( "No page indicated" ));
}

$gContent->verifyPermission( 'p_wiki_remove_page' );

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header( "location: ".WIKI_PKG_URL );
		die;
	} else {
		$gBitSystem->fatalError( "Error Deleting Page", "There was an error deleting the page: " . vc($gContent->mErrors)./wiki/edit_book.php
 );
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
