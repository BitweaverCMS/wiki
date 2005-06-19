<?php
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
	$smarty->assign( 's5', $s5 );
	$smarty->display( 'bitpackage:wiki/s5.tpl' );
	die;
?>
