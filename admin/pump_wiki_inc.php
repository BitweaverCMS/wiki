<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/admin/pump_wiki_inc.php,v 1.1 2007/07/09 13:16:16 squareing Exp $
 * @package install
 * @subpackage pumps
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );

$tutorial_intro = 'This tutorial does not contain any direct links to any particular pages. This is to help you understand how to navigate bitweaver. If you don\'t feel confident about finding your way back to this page, it might be useful to open a second browser window to carry out these instructions or print this page before continuing.

To continue with this tutorial, it would be best if you had administrator rights.';

$admin_link = 'You first have to go to the Administration panel (Administration in the top menu bar). Once there you can see a number of links organised by package. In the Wiki section there is a link called: ';

$pageHash = array(
	array(
		'fSavePage' => TRUE,
		'user_id' => ROOT_USER_ID,
		'format_guid' => 'tikiwiki',
		'title' => 'Welcome',
		'description' => 'The Wiki Package of bitweaver',
		'edit' => 'Welcome to bitweaver.
We appreciate that you are taking the time to test this unique product. bitweaver allows you to create a website exactly the way you want it to be. You can install packages as you need them, enhancing or reducing the functionality of your site. If you should have space limitations you can delete packages from your server without any consequences (as long as it\'s none of the required folders).

bitweaver was built with extensibility in mind and thus we have made it \'\'easy\'\' to create new packages for developers and just plug them into the bitweaver framework. We envisage that several packages will be created in the near future. If you should require additional functionality, it is worth keeping your eyes on [http://www.bitweaver.org/wiki/index.php?page=bitweaverFeatures|bitweaver Features], where we will announce all new packages as they are developed and reach a stable status.

^bitweaver allows you to manage your content the way you want to.^

!!!Other useful pages that have been added to your site
* GettingHelp
** Tutorial_WikiBook
** Tutorial_SetYourWikiHome
** Tutorial_Bitcommerce
** bitweaverGlossary
'),
	array(
		'fSavePage' => TRUE,
		'user_id' => ROOT_USER_ID,
		'format_guid' => 'tikiwiki',
		'title' => 'GettingHelp',
		'description' => 'Help and Contact Details',
		'edit' => 'bitweaver features an internal help system that provides help in virtually all forms and areas where you have to enter information. However, should you require more help, you can always contact us by any of the means mentioned below.
* Since we are still wroking hard on improving usability and stability of bitweaver, we haven\'t had time to translate bitweaver into various languages. If you are interested in helping us translate parts of bitweaver, please feel free to contact us.
* If you want to find out more about existing packages and how to install them, you can find information at [http://www.bitweaver.org|bitweaver].
* Best methods to get in contact with us
	** IRC ([http://www.bitweaver.org/wiki/index.php?page=ConnectingToIrc|instructions] on connecting to irc)
	** [http://sourceforge.net/mail/?group_id=141358|Sourceforge mailing list]
	** If you wish to report any bugs, we urge you to do this at [http://sourceforge.net/tracker/?atid=630083&group_id=101599&func=browse|Sourceforge]
	** the [http://www.bitweaver.org/forums/viewforum.php?f=5|bitweaver Forums] might contain useful information.
	** [http://www.bitweaver.org/|bitweaver] currently contains all the documentation we have.
* If you think you could contribute to bitweaver in any way, please feel free to contact us. we appreciate all the help we can get.
'),
	array(
		'fSavePage' => TRUE,
		'format_guid' => 'tikiwiki',
		'title' => 'Tutorial_WikiBook',
		'description' => 'A wikibook is a handy way to organise pages.',
		'edit' => 'This page will give you an idea of what Wiki Books are capable of and how these can help you organise your pages.

'.$tutorial_intro.'

!Turning on Wiki Books
'.$admin_link.'__Wiki Settings__


Clicking this link will take you to a page with a massive number of options. The ones we are interested in, are all in the __Wiki Books__ tab. There are 3 options there, which all have a brief description of what they do - please activate all three and click on __Change Preferences__. now the Wiki Books have been activated.

!Creating a Wiki Book
In the main menu to your left - the __Application Menu__, there are 2 new links in the Wiki section, both refering to Wiki Books: __Wiki Books__ and __Create Book__.

Follow the __Create Book__ link and you come to a form. In __Book Title__ please enter ===bitweaver Help=== and click the __Create new book__ button.

This page displays the current organisation of your __Wiki Book__, which should currently only display the name of your Wiki Book. To add content to your book, please click on the __Wiki Book Content__ tab. The left part of the page displays the current book and to the right there is a small form with a dropdown box. From the dropdown box select __Wiki Page__, which will give a list of existing pages. you can select multiple pages at once by holding down the __CTRL__ key and clicking on the desired pages with the mouse.

Select the following pages:
* Welcome
* GettingHelp
* bitweaverGlossary
and click on __add page__.

!Organising the Wiki Book
You can see that the selected pages have been added to your Wiki Book. If all has gone well, you should see something like:
* bitweaver Help
** 1 GettingHelp
** 2 bitweaverGlossary
** 3 Welcome

Please make sure you are viewing the __Edit Wiki Book__ tab.

Again you see the content on the left and to the right of the page, you can see a number of buttons. For every page you can see a some __arrow buttons__ and a button to __view the page__, __select the page__ and __remove the page__ from the Wiki Book.

To position the pages in a more desirable order, please click the up and down arrows for the various pages until your Wiki Book represents the following setup:
* bitweaver Help
** 1 Welcome
** 2 GettingHelp
** 3 bitweaverGlossary

You can make it more apparent that the bitweaverGlossary is part of the GettingHelp pages by clicking the __right arrow__ for the bitweaverGlossary page. this should yield something like:
* bitweaver Help
** 1 Welcome
** 2 GettingHelp
*** 2.1 bitweaverGlossary

!Viewing the Wiki Book
Now that we have created a Wiki Book, we want to view it and see what all this work was for. If you click on the __Wiki Books__ in your left menu, you will see the ===bitweaver Help=== book. Please click on the book, which will take you to a page, where you can see the Wiki Book you just created. The special aspect of these books, is the easy navigation that comes with the hierarchial structuring of the pages. Above the wiki page, you can now see links that allow you to move forwards, backwards and up one level using those simple navigational links.
'),
	array(
		'fSavePage' => TRUE,
		'format_guid' => 'tikiwiki',
		'title' => 'Tutorial_SetYourWikiHome',
		'description' => 'How to set a specific wikipage as home',
		'edit' => 'This page will guide you through the process of defining a particular wikipage as you Wiki HomePage.

'.$tutorial_intro.'

!Where is the Wiki HomePage setting?
'.$admin_link.'__Wiki Settings__

Clicking this link will take you to a page with a massive number of options. The one we are interested in, are all in the __Wiki Settings__ tab. The topmost setting is the one we want. The name of the page you enter here, will be the one tha is first shown when clicking on the __Wiki Home__ link in the ===Application Menu=== or the __Wiki__ link in the ===top bar menu===.
'),
	array(
		'fSavePage' => TRUE,
		'format_guid' => 'tikiwiki',
		'title' => 'bitweaverGlossary',
		'description' => 'Definition of Frequently used Terms',
		'edit' => ';Package:A Package is a part of bitweaver that can manipulate, store and/or display information. Packages are always self-contained entities that can be installed, or uninstalled should require additional functionality. A list of currently available packages can be found on [http://www.bitweaver.org|bitweaver]. bitweaver differentiates between internal and foreign packages. Internal packages are packages that have been created inhouse and are meant to work with bitweaver from the ground up and will probably not work with any other application. a Foreign Package is usually a standalone application that has been modified in a way to fit into bitweaver. We try and keep these modifications to a minimum, allowing for easy upgrades to new versions.
;Feature:Packages usually contain various features that can be activated or deactivated. All these features can be set from the ['.KERNEL_PKG_URL.'admin/index.php|Administration Screen]. The number of features can be daunting to begin with, but we are sure you will soon work out what they all do. most features have some short description of what you expect from the setting once turned on or off.
;Module:A module is one of the small boxes in one of the outer columns. You can select what modules and where you want to display these from the ['.KERNEL_PKG_URL.'admin/index.php?page=layout|Layout Screen].
;Group:In bitweaver groups are given permissions. You can then assign users to various groups. this makes it easy to allow a set of users to edit wiki pages but not edit blogs or vice versa.
;User:When a user registers with your site, that user is added to your database. every user has a defined set of permissions that allow them to access particular parts of your site. to manage these permissions, you have to first define groups and allocate permissions to them.
'),
	array(
		'fSavePage' => TRUE,
		'format_guid' => 'tikiwiki',
		'title' => 'Tutorial_Bitcommerce',
		'description' => 'Setting up Bitcommerce to work with bitweaver',
		'edit' => '[http://www.bitcommerce.org|Bitcommerce] is an open source ecommerce solution derived from Zen Cart and OSCommnercethat is widely used all over the world.

From their homepage:
^Bitcommerce truly is the art of e-commerce; a free, user-friendly, open source shopping cart system. The software is being developed by group of like-minded shop owners, programmers, designers, and consultants that think e-commerce could be and should be done differently. Some "solutions" seem to be complicated programming exercises instead of responding to users\' needs, Bitcommerce puts the merchant\'s and shopper\'s requirements first. Similarly, other programs are nearly impossible to install and use without an IT degree, Bitcommerce can be installed and set-up by anyone with the most basic computer skills. Others are so expensive ... not Bitcommerce, it\'s FREE!^

'.$tutorial_intro.'

We have made an effort to leave as much of the original code as it is to allow for easy upgrades to future version providing you with the best. For this reason there are some simple setup steps that have to be carried out for a seamless integration into bitweaver.
# if you haven\'t done so already, you should install Bitcommerce. You can do this by accessing the __Administration__ --> __Kernel__ --> __Packages__ area. There you should activate Bitcommerce and you will also find a link to the Bitcommerce installer page.
# After you have set up Bitcommerce, you should be able to customise Bitcommerce in any way you want to from the Bitcommerce administration area.
'),
);

foreach( $pageHash as $page ) {
	$newPage = new BitPage();
	if( $newPage->store( $page ) ) {
		$pumpedData['Wiki'][] = $page['title'];
	} else {
		$error = $newPage->mErrors;
		$gBitSmarty->assign( 'error',$error );
	}
}
?>
