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
use Bitweaver\Liberty\LibertyContent;

$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_rankings' );
$gBitSystem->verifyPermission( 'p_wiki_list_pages' );

$rankingOptions = [
	[
		'output' => KernelTools::tra( 'Most Often Viewed' ),
		'value' => 'hits_desc'
	],
	[
		'output' => KernelTools::tra( 'Most Recently Modified' ),
		'value' => 'last_modified_desc'
	],
	[
		'output' => KernelTools::tra( 'Most Active Authors' ),
		'value' => 'top_authors'
	],
];
$gBitSmarty->assign( 'rankingOptions', $rankingOptions );

if( !empty( $_REQUEST['sort_mode'] ) ) {
	switch( $_REQUEST['sort_mode'] ) {
		case 'last_modified_desc':
			$gBitSmarty->assign( 'attribute', 'last_modified' );
			$_REQUEST['attribute'] = KernelTools::tra( 'Date of last modification' );
			break;
		case 'top_authors':
			$gBitSmarty->assign( 'attribute', 'ag_hits' );
			$_REQUEST['attribute'] = KernelTools::tra( 'Hits to items by this Author' );
			break;
		default:
			$gBitSmarty->assign( 'attribute', 'hits' );
			$_REQUEST['attribute'] = KernelTools::tra( 'Hits' );
			break;
	}
} else {
	$gBitSmarty->assign( 'attribute', 'hits' );
	$_REQUEST['attribute'] = KernelTools::tra( 'Hits' );
}

$_REQUEST['title']             = KernelTools::tra( 'Wiki Rankings' );
$_REQUEST['content_type_guid'] = BITPAGE_CONTENT_TYPE_GUID;
$_REQUEST['max_records']       = !empty( $_REQUEST['max_records'] ) ? $_REQUEST['max_records'] : 10;

if( empty( $gContent ) ) {
	$gContent = new LibertyContent();
}
$rankList = $gContent->getContentRanking( $_REQUEST );
$gBitSmarty->assign( 'rankList', $rankList );

$gBitSystem->display( 'bitpackage:liberty/rankings.tpl', KernelTools::tra( "Wiki Rankings" ) , array( 'display_mode' => 'display' ));
