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
require_once( WIKI_PKG_CLASS_PATH.'BitBook.php');

global $gContent;
include_once( LIBERTY_PKG_INCLUDE_PATH.'lookup_content_inc.php' );

// this is needed when the center module is applied to avoid abusing $_REQUEST
if( empty( $lookupHash )) {
	$lookupHash = &$_REQUEST;
}

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || strtolower( get_class( $gContent ) ) != 'bitpage' ) {
	if( !empty( $lookupHash['page_id'] ) )  {
		$loadContentId = BitPage::findContentIdByPageId( $lookupHash['page_id'] );
	} elseif( !empty( $lookupHash['content_id'] ) ) {
		$loadContentId = $lookupHash['content_id'];
	} elseif( !empty( $lookupHash['page'] ) ) {
		//handle legacy forms that use plain 'page' form variable name

		//if page had some special enities they were changed to HTML for for security reasons.
		//now we deal only with string so convert it back - so we can support this case:
		//You&Me --(detoxify in kernel)--> You&amp;Me --(now)--> You&Me
		//we could do htmlspecialchars_decode but it allows <> marks here, so we just transform &amp; to & - it's not so scary.
		$loadPage = str_replace("&amp;", "&", $lookupHash['page'] );
		// Fix nignx mapping of '+' sign when doing rewrite
		$loadPage = str_replace("+", " ", $loadPage );

		if( $loadPage && $existsInfo = BitPage::pageExists( $loadPage ) ) {
			if (count($existsInfo)) {
				if (count($existsInfo) > 1) {
					// Display page so user can select which wiki page they want (there are multiple that share this name)
					$gBitSmarty->assign( 'choose', $lookupHash['page'] );
					$gBitSmarty->assign('dupePages', $existsInfo);
					$gBitSystem->display('bitpackage:wiki/page_select.tpl', NULL, array( 'display_mode' => 'display' ));
					die;
				} else {
					$loadPageId = $existsInfo[0]['page_id'];
					$loadContentId = $existsInfo[0]['content_id'];
				}
			}
		} elseif( $loadPage ) {
			$gBitSmarty->assign('page', $loadPage);//to have the create page link in the error
		}
	}

	if( !empty( $loadContentId ) ) {
		$gContent = BitPage::getLibertyObject( $loadContentId );
	}

	if( empty( $gContent ) || !is_object( $gContent ) ) {
		$gContent = new BitPage();
	}
}

// we weren't passed a structure, but maybe this page belongs to one. let's check...
if( $gContent->isValid() && empty( $gStructure ) ) {
	//Get the structures this page is a member of
	if( !empty($lookupHash['structure']) ) {
		$structure=$lookupHash['structure'];
	} else {
		$structure='';
	}
	$structs = $gContent->getStructures();
	if (count($structs)==1) {
		$gStructure = new LibertyStructure( $structs[0]['structure_id'] );
		if( $gStructure->load() ) {
			$gStructure->loadNavigation();
			$gStructure->loadPath();
			$gBitSmarty->assign( 'structureInfo', $gStructure->mInfo );
		}
	} else {
		$gBitSmarty->assign('showstructs', $structs);
	}
}

$gBitSmarty->clearAssign( 'gContent' );
$gBitSmarty->assignByRef( 'gContent', $gContent );
?>
