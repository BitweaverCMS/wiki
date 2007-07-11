<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_wiki/admin/pump_wiki_inc.php,v 1.2 2007/07/11 10:22:03 squareing Exp $
 * @package install
 * @subpackage pumps
 */

/**
 * required setup
 */
require_once( WIKI_PKG_PATH.'BitPage.php' );

$tutorial_intro = '^This tutorial does not contain any direct links to any particular pages. This is to help you understand how to navigate bitweaver. If you don\'t feel confident about finding your way back to this page, it might be useful to open a second browser window to carry out these instructions or print this page before continuing.

To continue with this tutorial, it would be best if you had administrator rights.^';

$admin_link = 'You first have to go to the Administration panel (Administration in the top menu bar). Once there you can see a number of links organised by package:
__Administration__ --> ';

$pageHash = array(
	array(
		'title' => 'Welcome',
		'description' => 'The Wiki Package of bitweaver',
		'edit' => 'Welcome to bitweaver.
We appreciate that you are taking the time to test this unique product. bitweaver allows you to create a website exactly the way you want it to be. You can install packages as you need them, enhancing or reducing the functionality of your site. If you should have space limitations you can delete packages from your server without any consequences (as long as it\'s none of the required packages).

bitweaver was built with extensibility in mind and thus we have made it \'\'easy\'\' to create new packages for developers and just plug them into the bitweaver framework. We envisage that more packages will become in the future. If you should require additional functionality, it is worth keeping your eyes on [http://www.bitweaver.org/wiki/index.php?page=bitweaverFeatures|bitweaver Features], where we will announce all new packages as they are developed and reach a stable status.

^bitweaver allows you to manage your content the way you want to.^

!!!Other useful pages that have been added to your site
* ((Getting Started))
** ((Getting Help))
** ((How to create a Wiki Book))
** ((How to set your Wiki Homepage))
** ((bitweaver Glossary))
'),
	array(
		'title' => 'Getting Started',
		'description' => 'Some basic pointer on where to go after installation',
		'edit' => "{maketoc}
Well, it seems you have successfully installed bitweaver and since you're viewing this page, it's probably safe to assume that this is your first bitweaver installation.

$tutorial_intro

''Please see ((bitweaver Glossary)) if are having difficulties with the terms used on this page.''

! Activating Packages
bitweaver is very flexible and therefore has many settings. The complexity of your bitweaver installation also depends on the number of packages you have active. You can modify the active packages in the {$admin_link}__Kernel__ --> __Packages__ page at any time.

! Package Descriptions
!! Liberty
Liberty is the part of bitweaver that deals with all the content on your site. From a developers point of view, this is very handy since they can make use of a great number of common base classes and methods to store, retrieve and manipulate the content stored within. From a users point of view, this allows for easy control of certain elements in a central location. This means that in the liberty administration pages you will find certain settings such as image proceccing features and content caching settings that apply to all content.

! Controlling Plugins
!! Liberty Plugins
In keeping with the flexibility of bitweaver, there are various plugins that can be enabled to add functionality and features to your content. For instance, you can enable or disable a __code__ plugin to allow users to easily insert code fragments into their wiki pages:
{code title='Some sample code'}
class phpClass() {
	function phpClass() {
		\$this->pourCode->brain();
	}
}
{/code}
To control the availability of this __code__ plugin, you can do this on the {$admin_link}__Liberty__ --> __Liberty Plugins__ page. Once you have de-activated the plugin, the above code section will not be parsed any more and you will simply see the text as it is.

!! Other Plugins
Some other packages also have plugins. These settings can usually be found in the respective package plugins admin page e.g.: {$admin_link}__Treasury__ --> __Treasury Plugins__.
"),
	array(
		'title' => 'Getting Help',
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
		'title' => 'How to create a Wiki Book',
		'description' => 'A wikibook is a handy way to organise pages.',
		'edit' => '{maketoc}
This page will give you an idea of what Wiki Books are capable of and how these can help you organise your pages.

'.$tutorial_intro.'

!Turning on Wiki Books
'.$admin_link.'__Wiki__ --> __Wiki Settings__


Clicking this link will take you to a page with a massive number of options. The ones we are interested in, are all in the __Wiki Books__ tab. There are 3 options there, which all have a brief description of what they do - please activate all three and click on __Change Preferences__. now the Wiki Books have been activated.

!Creating a Wiki Book
In the main menu to your left - the __Application Menu__, there are 2 new links in the Wiki section, both refering to Wiki Books: __Wiki Books__ and __Create Book__.

Follow the __Create Book__ link and you come to a form. In __Book Title__ please enter ===bitweaver Help=== and click the __Create new book__ button.

This page displays the current organisation of your __Wiki Book__, which should currently only display the name of your Wiki Book. To add content to your book, please click on the __Wiki Book Content__ tab. The left part of the page displays the current book and to the right there is a small form with a dropdown box. From the dropdown box select __Wiki Page__, which will give a list of existing pages. you can select multiple pages at once by holding down the __CTRL__ key and clicking on the desired pages with the mouse.

Select the following pages:
* Welcome
* ((Getting Help))
* ((bitweaver Glossary))
and click on __add page__.

!Organising the Wiki Book
You can see that the selected pages have been added to your Wiki Book. If all has gone well, you should see something like:
* bitweaver Help
** 1 ((Getting Help))
** 2 ((bitweaver Glossary))
** 3 Welcome

Please make sure you are viewing the __Edit Wiki Book__ tab.

Again you see the content on the left and to the right of the page, you can see a number of buttons. For every page you can see a some __arrow buttons__ and a button to __view the page__, __select the page__ and __remove the page__ from the Wiki Book.

To position the pages in a more desirable order, please click the up and down arrows for the various pages until your Wiki Book represents the following setup:
* bitweaver Help
** 1 Welcome
** 2 ((Getting Help))
** 3 ((bitweaver Glossary))

You can make it more apparent that the ((bitweaver Glossary)) is part of the ((Getting Help)) pages by clicking the __right arrow__ for the ((bitweaver Glossary)) page. this should yield something like:
* bitweaver Help
** 1 Welcome
** 2 ((Getting Help))
*** 2.1 ((bitweaver Glossary))

!Viewing the Wiki Book
Now that we have created a Wiki Book, we want to view it and see what all this work was for. If you click on the __Wiki Books__ in your left menu, you will see the ===bitweaver Help=== book. Please click on the book, which will take you to a page, where you can see the Wiki Book you just created. The special aspect of these books, is the easy navigation that comes with the hierarchial structuring of the pages. Above the wiki page, you can now see links that allow you to move forwards, backwards and up one level using those simple navigational links.
'),
	array(
		'title' => 'How to set your Wiki Homepage',
		'description' => 'How to set a specific wikipage as home',
		'edit' => 'This page will guide you through the process of defining a particular wikipage as you Wiki HomePage.

'.$tutorial_intro.'

!Where is the Wiki HomePage setting?
'.$admin_link.'__Wiki__ --> __Wiki Settings__

Clicking this link will take you to a page with a massive number of options. The one we are interested in, are all in the __Wiki Settings__ tab. The topmost setting is the one we want. The name of the page you enter here, will be the one tha is first shown when clicking on the __Wiki Home__ link in the ===Application Menu=== or the __Wiki__ link in the ===top bar menu===.
'),
	array(
		'title' => 'bitweaver Glossary',
		'description' => 'Definition of Frequently used Terms',
		'edit' => ';Package:A Package is a part of bitweaver that can manipulate, store and/or display information. Packages are always self-contained entities that can be installed, or uninstalled should require additional functionality. A list of currently available packages can be found on [http://www.bitweaver.org|bitweaver]. bitweaver differentiates between internal and foreign packages. Internal packages are packages that have been created inhouse and are meant to work with bitweaver from the ground up and will probably not work with any other application. a Foreign Package is usually a standalone application that has been modified in a way to fit into bitweaver. We try and keep these modifications to a minimum, allowing for easy upgrades to new versions.
;Plugin:Enable or disable pluggable features. These plugins are more powerful than regular features and usually allow manipulation of content.
;Feature:Packages usually contain various features that can be activated or deactivated. All these features can be set from the ['.KERNEL_PKG_URL.'admin/index.php|Administration Screen]. The number of features can be daunting to begin with, but we are sure you will soon work out what they all do. most features have some short description of what you expect from the setting once turned on or off.
;Module:A module is one of the small boxes in one of the outer columns. You can select what modules and where you want to display these from the ['.KERNEL_PKG_URL.'admin/index.php?page=layout|Layout Screen].
;Group:In bitweaver groups are given permissions. You can then assign users to various groups. this makes it easy to allow a set of users to edit wiki pages but not edit blogs or vice versa.
;User:When a user registers with your site, that user is added to your database. every user has a defined set of permissions that allow them to access particular parts of your site. to manage these permissions, you have to first define groups and allocate permissions to them.
;Content:Content is any text that is intered into bitweaver that is stored in the central liberty tables. storing the content in one place makes it accesible from various different places allowing users to mix and match such content as they see fit. This makes it possible to easily display images in a wiki text or even display a blog post in an article since they are all considered to be the same.
'),
);

foreach( $pageHash as $page ) {
	// common settings for all of these pages
	$page['fSavePage']   = TRUE;
	$page['user_id']     = ROOT_USER_ID;
	$page['format_guid'] = 'tikiwiki';

	$newPage = new BitPage();
	if( $newPage->store( $page ) ) {
		$pumpedData['Wiki'][] = $page['title'];
	} else {
		$error = $newPage->mErrors;
		$gBitSmarty->assign( 'error',$error );
	}
}
?>
