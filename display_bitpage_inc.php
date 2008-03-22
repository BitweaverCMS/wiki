<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/display_bitpage_inc.php,v 1.39 2008/03/22 21:37:47 jht001 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: display_bitpage_inc.php,v 1.39 2008/03/22 21:37:47 jht001 Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */

include( WIKI_PKG_PATH.'get_bitpage_info.php' );


/*
$gBitSmarty->assign('structure','n');
//Has a structure page been requested
if (isset($_REQUEST["structure_id"])) {
	$structure_id = $_REQUEST["structure_id"];
} else {
	//if not then check if page is the head of a structure
	$structure_id = $structlib->get_struct_ref_if_head( $gContent->mPageName );
}
$gBitSmarty->assign_by_ref('page',$gContent->mInfo['title']);
*/

// Let creator set permissions
if($gBitSystem->isFeatureActive( 'wiki_creator_admin' )) {
	if( $gContent->isOwner() ) {
		$gBitUser->setPreference( 'p_wiki_admin', TRUE );
	}
}
if(isset($_REQUEST["copyrightpage"])) {
	$gBitSmarty->assign_by_ref('copyrightpage',$_REQUEST["copyrightpage"]);
}


// Now increment page hits since we are visiting this page
if( $gBitSystem->isFeatureActive( 'users_count_admin_pageviews' ) || !$gBitUser->isAdmin() ) {
  $gContent->addHit();
}

// Check if we have to perform an action for this page
// for example lock/unlock
if( isset( $_REQUEST["action"] ) && (($_REQUEST["action"] == 'lock' || $_REQUEST["action"]=='unlock' ) &&
	($gBitUser->hasPermission( 'p_wiki_admin' )) || ($user and ($gBitUser->hasPermission( 'p_wiki_lock_page' )) and ($gBitSystem->isFeatureActive( 'wiki_usrlock' )))) ) {
	$gContent->setLock( ($_REQUEST["action"] == 'lock' ? 'L' : NULL ) );
}

// Process an undo here
if(isset($_REQUEST["undo"])) {
	if( !$gContent->isLocked() && ( ($gBitUser->hasPermission( 'p_wiki_edit_page' ) && $gContent->isOwner())||($gContent->hasUserPermission( 'p_wiki_rollback' ))) ) {
		// Remove the last version
		$gContent->removeLastVersion();
	}
}

if ($gBitSystem->isFeatureActive( 'wiki_uses_slides' )) {
	$slides = split("-=[^=]+=-",$gContent->mInfo["data"]);
	if(count($slides)>1) {
		$gBitSmarty->assign('show_slideshow','y');
	} else {
		$slides = explode(defined('PAGE_SEP') ? PAGE_SEP : "...page...",$gContent->mInfo["data"]);
		if(count($slides)>1) {
			$gBitSmarty->assign('show_slideshow','y');
		} else {
			$gBitSmarty->assign('show_slideshow','n');
		}
	}
} else {
	$gBitSmarty->assign('show_slideshow','n');
}

// ...page... stuff - TODO: this is cumbersome and should be cleaned up
$pages = $gContent->countSubPages($gContent->mInfo['parsed_data']);
if( $pages > 1 ) {
	if(!isset($_REQUEST['pagenum'])) {
		$_REQUEST['pagenum']=1;
	}
	$gContent->mInfo['parsed_data']=$gContent->getSubPage($gContent->mInfo['parsed_data'],$_REQUEST['pagenum']);
	$gBitSmarty->assign('pages',$pages);
	if($pages>$_REQUEST['pagenum']) {
		$gBitSmarty->assign('next_page',$_REQUEST['pagenum']+1);
	} else {
		$gBitSmarty->assign('next_page',$_REQUEST['pagenum']);
	}
	if($_REQUEST['pagenum']>1) {
		$gBitSmarty->assign('prev_page',$_REQUEST['pagenum']-1);
	} else {
		$gBitSmarty->assign('prev_page',1);
	}
	$gBitSmarty->assign('first_page',1);
	$gBitSmarty->assign('last_page',$pages);
	$gBitSmarty->assign('pagenum',$_REQUEST['pagenum']);
}

// Watches
if( $gBitSystem->isFeatureActive( 'users_watches' ) ) {
	$gBitSmarty->assign('user_watching_page','n');
	if( $watch = $gBitUser->getEventWatches( 'wiki_page_changed', $gContent->mPageId ) ) {
		$gBitSmarty->assign('user_watching_page','y');
	}
}

if( isset( $_REQUEST['s5'] ) ) {
	include_once( WIKI_PKG_PATH.'s5.php');
}

$gBitSystem->display('bitpackage:wiki/show_page.tpl');
// xdebug_dump_function_profile(XDEBUG_PROFILER_CPU);
?>
