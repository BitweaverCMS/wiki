<?php
/**
 * BitBook class
 *
 * @author   spider <spider@steelsun.com>
 * @version  $Revision: 1.14 $
 * @package  wiki
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2004, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See below for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Authors: spider <spider@steelsun.com>
// +----------------------------------------------------------------------+
//
// $Id: BitBook.php,v 1.14 2010/04/17 22:46:11 wjames5 Exp $


/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );
require_once( LIBERTY_PKG_PATH.'LibertyStructure.php' );

define('BITBOOK_CONTENT_TYPE_GUID', 'bitbook' );

/**
 * BitBook class
 *
 * @abstract
 * @author   spider <spider@steelsun.com>
 * @version  $Revision: 1.14 $
 * @package  wiki
 */
class BitBook extends BitPage {
	var $mPageId;
	var $mPageName;
	function BitBook( $pPageId=NULL, $pContentId=NULL ) {
		BitPage::BitPage( $pPageId, $pContentId );
		$this->registerContentType( BITBOOK_CONTENT_TYPE_GUID, array(
			'content_type_guid' => BITBOOK_CONTENT_TYPE_GUID,
			'content_name' => 'Wiki Book',
			'handler_class' => 'BitBook',
			'handler_package' => 'wiki',
			'handler_file' => 'BitBook.php',
			'maintainer_url' => 'http://www.bitweaver.org'
		) );
		$this->mContentTypeGuid = BITBOOK_CONTENT_TYPE_GUID;

		// Permission setup
		$this->mCreateContentPerm  = 'p_wiki_create_book';
		$this->mUpdateContentPerm  = 'p_wiki_update_book';
		$this->mAdminContentPerm = 'p_wiki_admin_book';
	}

	function getList( &$pListHash ) {
		$struct = new LibertyStructure();
		$pListHash['content_type_guid'] = $this->mContentTypeGuid;
		return $struct->getList( $pListHash );
	}

	function getStats() {
		return FALSE;
	}
}
?>
