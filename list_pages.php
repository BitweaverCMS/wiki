<?php
/**
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once '../kernel/includes/setup_inc.php';
use Bitweaver\Wiki\BitPage;
use Bitweaver\KernelTools;

// verify stuff
$gBitSystem->verifyPackage( 'wiki' );
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
	$gContent->verifyUserPermission( 'p_wiki_remove_page' );

	if( !empty( $_REQUEST['cancel'] )) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] )) {
		$formHash['delete'] = true;
		$formHash['batch_submit'] = 'remove_pages';
		foreach( $_REQUEST["checked"] as $del ) {
			$tmpPage = new BitPage( $del);
			$info = $tmpPage->load() && !empty( $tmpPage->mInfo['title'] ) ? $tmpPage->mInfo['title'] : $del;
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>'.$info;
		}
		$gBitSystem->confirmDialog( $formHash, 
			[ 
				'warning' => KernelTools::tra('Are you sure you want to delete these pages?') . ' (' . KernelTools::tra('Count: ') . count( $_REQUEST["checked"] ) . ')',				
				'error' => KernelTools::tra('This cannot be undone!'),
			]
		);
	} else {
		foreach( $_REQUEST["checked"] as $deletepage ) {
			$tmpPage = new BitPage( $deletepage );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ));
			}
		}
		if( !empty( $errors )) {
			$gBitSmarty->assign( 'errors', $errors );
		}
	}
}

$gBitSmarty->assign( "gContent", $gContent );

if( !empty( $_REQUEST['sort_mode'] )) {
	$listHash['sort_mode'] = preg_replace( '/^user_/', 'creator_user_', $_REQUEST['sort_mode'] );
}
$listHash = $_REQUEST;
$listHash['extras'] = true;
$listpages = $gContent->getList( $listHash );

// we will probably need a better way to do this
$listHash['listInfo']['parameters']['find_title']       = !empty( $listHash['find_title'] ) ? $listHash['find_title'] : '';
$listHash['listInfo']['parameters']['find_author']      = !empty( $listHash['find_author'] ) ? $listHash['find_author'] : '';
$listHash['listInfo']['parameters']['find_last_editor'] = !empty( $listHash['find_last_editor'] ) ? $listHash['find_last_editor'] : '';
$listHash['listInfo']['ihash']['content_type_guid'] = BITPAGE_CONTENT_TYPE_GUID;

$gBitSmarty->assign( 'listpages', $listpages );
$gBitSmarty->assign( 'listInfo', $listHash['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:wiki/list_pages.tpl', KernelTools::tra( 'Wiki Pages' ), [ 'display_mode' => 'list' ] );