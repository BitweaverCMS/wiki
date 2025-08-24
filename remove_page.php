<?php
/**
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\KernelTools;

include_once WIKI_PKG_INCLUDE_PATH.'lookup_page_inc.php';

$gBitSystem->verifyPackage( 'wiki' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( KernelTools::tra( "No page indicated" ) );
}

$gContent->verifyUserPermission( 'p_wiki_remove_page' );

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header( "location: ".WIKI_PKG_URL );
		die;
	} else {
		$gBitSystem->fatalError( KernelTools::tra("There was an error deleting the page:") . ' ' . \Bitweaver\vc( $gContent->mErrors ));
	}
}

$gBitSystem->setBrowserTitle( KernelTools::tra( 'Confirm delete of: ' ).$gContent->getTitle() );
$formHash['remove'] = true;
$formHash['page_id'] = $_REQUEST['page_id'];
$msgHash = [
	'label' => KernelTools::tra( 'Delete WikiPage' ),
	'confirm_item' => $gContent->getTitle(),
	'warning' => KernelTools::tra( 'All previous versions of this page will be completely deleted.' ),
	'error' => KernelTools::tra( 'This cannot be undone!' ),
];
$gBitSystem->confirmDialog( $formHash,$msgHash );
