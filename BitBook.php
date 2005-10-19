<?php
/**
 * BitBook class
 *
 * @author   spider <spider@steelsun.com>
 * @version  $Revision: 1.1.1.1.2.2 $
 * @package  wiki
 */
// +----------------------------------------------------------------------+
// | Copyright (c) 2004, bitweaver.org
// +----------------------------------------------------------------------+
// | All Rights Reserved. See copyright.txt for details and a complete list of authors.
// | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
// |
// | For comments, please use phpdocu.sourceforge.net documentation standards!!!
// | -> see http://phpdocu.sourceforge.net/
// +----------------------------------------------------------------------+
// | Authors: spider <spider@steelsun.com>
// +----------------------------------------------------------------------+
//
// $Id: BitBook.php,v 1.1.1.1.2.2 2005/10/19 21:13:46 squareing Exp $


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
 * @version  $Revision: 1.1.1.1.2.2 $
 * @package  wiki
 * @subpackage BitBook
 */
class BitBook extends BitPage {


    function BitBook( $pPageId=NULL, $pContentId=NULL ) {
        $this->registerContentType( BITBOOK_CONTENT_TYPE_GUID, array(
                'content_type_guid' => BITBOOK_CONTENT_TYPE_GUID,
                'content_description' => 'Wiki Book',
                'handler_class' => 'BitBook',
                'handler_package' => 'wiki',
                'handler_file' => 'BitBook.php',
                'maintainer_url' => 'http://www.bitweaver.org'
            ) );
        BitPage::BitPage( $pPageId, $pContentId );
		$this->mContentTypeGuid = BITBOOK_CONTENT_TYPE_GUID;
    }

	function getList( &$pListHash ) {
		$struct = new LibertyStructure();
		return $struct->getList( $pListHash );
	}
}

?>
