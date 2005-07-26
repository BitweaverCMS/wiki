<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/s5.php,v 1.1.1.1.2.2 2005/07/26 15:50:47 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: s5.php,v 1.1.1.1.2.2 2005/07/26 15:50:47 drewslater Exp $
 * @package wiki
 * @subpackage functions
 */

	// we need to split the page into seperate slides
	$slides = explode( '<h1>', $pdata );
	//vd($slides);
	// manually set the first slide to page title and description
	$s5 = '<div class="slide"><h1>'.$gContent->mInfo['title'].'</h1>';
	$s5 .= '<h3>'.$gContent->mInfo['description'].'</h3></div>';
	foreach( $slides as $slide ) {
		if( !empty( $slide ) ) {
			$s5 .= '<div class="slide">';
			if( preg_match( '/<\/h1>/',$slide ) ) {
				$s5 .= '<h1>';
			}
			$s5 .= $slide.'</div>';
		}
	}
	// manually set the last slide with a link back to the wiki page
	$s5 .= '<div class="slide"><h1 style="margin:30% 0 10% 0;">'.tra( 'The End' ).'</h1>';
	$s5 .= '<p><a href="'.WIKI_PKG_URL.'index.php?page_id='.$gContent->mInfo['page_id'].'">'.tra( 'back to the wiki page' ).'</a></p></div>';
	$gBitSmarty->assign( 's5', $s5 );
	$gBitSmarty->display( 'bitpackage:wiki/s5.tpl' );
	die;
?>
