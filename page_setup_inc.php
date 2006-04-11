<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/Attic/page_setup_inc.php,v 1.5 2006/04/11 17:52:11 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: page_setup_inc.php,v 1.5 2006/04/11 17:52:11 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
if( !empty( $gContent ) && $gContent->isValid() ) {
	if (!$gBitUser->isAdmin()) {
		$ppps = array(
			'p_wiki_view_page',
			'p_wiki_edit_page',
			'p_wiki_rollback',
			'p_wiki_remove_page',
			'p_wiki_rename_page',
			'p_wiki_lock_page',
			'p_wiki_admin',
			'p_wiki_view_attachments'
		);
		// If we are in a page then get individual permissions
		foreach( array_keys( $gBitUser->mPerms ) as $perm ) {
			if (in_array($perm, $ppps)) {
				// Check for individual permissions if this is a page
				if ($gBitUser->object_has_one_permission($gContent->mContentId, BITPAGE_CONTENT_TYPE_GUID )) {
					if ($gBitUser->object_has_permission($gBitUser->mUserId, $gContent->mContentId, BITPAGE_CONTENT_TYPE_GUID, $perm)) {
						$$perm = 'y';
						$gBitSmarty->assign("$perm", 'y');
					} else {
						$$perm = 'n';
						$gBitSmarty->assign("$perm", 'n');
					}
				}
			} else {
				$$perm = 'y';
				$gBitSmarty->assign("$perm", 'y');
			}
		}
	}
}
?>
