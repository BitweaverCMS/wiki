<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/list_pages.php,v 1.22 2007/10/12 16:14:14 spiderr Exp $
 *
 * $Id: list_pages.php,v 1.22 2007/10/12 16:14:14 spiderr Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );

// verify stuff
$gBitSystem->verifyPackage( 'wiki' );
$gBitSystem->verifyFeature( 'wiki_list_pages' );
$gBitSystem->verifyPermission( 'p_wiki_list_pages' );

$gContent = new BitPage();
$gContent->invokeServices( 'content_list_function', $_REQUEST );

/* mass-remove:
   the checkboxes are sent as the array $_REQUEST["checked[]"], values are the wiki-PageNames,
   e.g. $_REQUEST["checked"][3]="HomePage"
   $_REQUEST["batch_submit"] holds the value of the "with selected do..."-option list
   we look if any page's checkbox is on and if remove_pages is selected.
   then we check permission to delete pages.
   if so, we call BitPage::expunge for all the checked pages.  */
if( isset( $_REQUEST["batch_submit"] ) && isset( $_REQUEST["checked"] ) && $_REQUEST["batch_submit"] == "remove_pages" ) {

	// Now check permissions to remove the selected pages
	$gContent->verifyPermission( 'p_wiki_remove_page' );

	if( !empty( $_REQUEST['cancel'] )) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] )) {
		$formHash['delete'] = TRUE;
		$formHash['batch_submit'] = 'remove_pages';
		foreach( $_REQUEST["checked"] as $del ) {
			$tmpPage = new BitPage( $del);
			if( $tmpPage->load() && !empty( $tmpPage->mInfo['title'] )) {
				$info = $tmpPage->mInfo['title'];
			} else {
				$info = $del;
			}
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>'.$info;
		}
		$gBitSystem->confirmDialog( $formHash, array( 'warning' => 'Are you sure you want to delete '.count($_REQUEST["checked"]).' pages?', 'error' => 'This cannot be undone!' ) );
	} else {
		foreach( $_REQUEST["checked"] as $deletepage ) {
			$tmpPage = new BitPage( $deletepage );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ));
			}
		}
		if( !empty( $errors )) {
			$gBitSmarty->assign_by_ref( 'errors', $errors );
		}
	}
}

$gBitSmarty->assign_by_ref( "gContent", $gContent );

if( !empty( $_REQUEST['sort_mode'] )) {
	$listHash['sort_mode'] = preg_replace( '/^user_/', 'creator_user_', $_REQUEST['sort_mode'] );
}
$listHash = $_REQUEST;
$listHash['extras'] = TRUE;
$listpages = $gContent->getList( $listHash );

// we will probably need a better way to do this
$listHash['listInfo']['parameters']['find_title']       = !empty( $listHash['find_title'] ) ? $listHash['find_title'] : '';
$listHash['listInfo']['parameters']['find_author']      = !empty( $listHash['find_author'] ) ? $listHash['find_author'] : '';
$listHash['listInfo']['parameters']['find_last_editor'] = !empty( $listHash['find_last_editor'] ) ? $listHash['find_last_editor'] : '';

$gBitSmarty->assign_by_ref( 'listpages', $listpages );
$gBitSmarty->assign_by_ref( 'listInfo', $listHash['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:wiki/list_pages.tpl', tra( 'Wiki Pages' ));
?>
