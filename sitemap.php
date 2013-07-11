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
require_once( '../kernel/setup_inc.php' );

require_once( WIKI_PKG_PATH.'BitBook.php' );

$book = new BitBook();
$gSiteMapHash = array();

$listHash = array();

if( $bookList = $book->getList( $listHash ) ) {
	foreach( $bookList['data'] as $bookHash ) {
		$bookStructure = new LibertyStructure( $bookHash['structure_id'] );
		$listBook = $bookStructure->buildTreeToc( $bookHash['structure_id'] );
		process_book_list( $listBook );
	}
}


function process_book_list( $pList, $pDepth = 1 ) {
	global $gSiteMapHash;
	foreach( array_keys( $pList ) as $key ) {
		if( !empty( $pList[$key]['display_url'] ) ) {
			$hash = array();
			$hash['loc'] =  BIT_BASE_URI.$pList[$key]['display_url'];
			$hash['lastmod'] = date( 'Y-m-d', $pList[$key]['last_modified'] );
			if( (time() - $pList[$key]['last_modified']) < 86400 ) {
				$freq = 'daily';
			} elseif( (time() - $pList[$key]['last_modified']) < (86400 * 7) ) {
				$freq = 'weekly';
			} else {
				$freq = 'monthly';
			}
			
			$hash['changefreq'] = $freq;
			$hash['priority'] = 1 - (round( $pDepth * .5 ) * .1);
			$gSiteMapHash[$pList[$key]['content_id']] = $hash;
		}
		if( !empty( $pList[$key]['sub'] ) ) {
			process_book_list( $pList[$key]['sub'], ($pDepth + 1) );
		}
	}
}

$gBitSmarty->assign_by_ref( 'gSiteMapHash', $gSiteMapHash );
$gBitThemes->setFormatHeader( 'xml' );
print $gBitSmarty->display( 'bitpackage:kernel/sitemap.tpl' );
