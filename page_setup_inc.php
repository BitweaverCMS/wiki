<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page_setup_inc.php,v 1.1.1.1.2.1 2005/06/27 17:47:41 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_setup_inc.php,v 1.1.1.1.2.1 2005/06/27 17:47:41 lsces Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
if( !empty( $gContent ) && $gContent->isValid() ) {
	if (!$gBitUser->isAdmin()) {
		$ppps = array(
			'bit_p_view',
			'bit_p_edit',
			'bit_p_rollback',
			'bit_p_remove',
			'bit_p_rename',
			'bit_p_lock',
			'bit_p_admin_wiki',
			'bit_p_view_attachments'
		);
		// If we are in a page then get individual permissions
		foreach( array_keys( $gBitUser->mPerms ) as $perm ) {
			if (in_array($perm, $ppps)) {
				// Check for individual permissions if this is a page
				if ($gBitUser->object_has_one_permission($gContent->mContentId, BITPAGE_CONTENT_TYPE_GUID )) {
					if ($gBitUser->object_has_permission($gBitUser->mUserId, $gContent->mContentId, BITPAGE_CONTENT_TYPE_GUID, $perm)) {
						$$perm = 'y';
						$smarty->assign("$perm", 'y');
					} else {
						$$perm = 'n';
						$smarty->assign("$perm", 'n');
					}
				}
			} else {
				$$perm = 'y';
				$smarty->assign("$perm", 'y');
			}
		}
	}
}
?>
