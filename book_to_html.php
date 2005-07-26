<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/book_to_html.php,v 1.1.1.1.2.2 2005/07/26 15:50:32 drewslater Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: book_to_html.php,v 1.1.1.1.2.2 2005/07/26 15:50:32 drewslater Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( WIKI_PKG_PATH.'BitBook.php');

function copys($source,$dest)
{
    if (!is_dir($source))
    return 0;
    if (!is_dir($dest))
    {
        mkdir($dest);
    }
    $h=@dir($source);
    while (@($entry=$h->read()) !== false)
    {
        if (($entry!=".")&&($entry!=".."))
        {
            if (is_dir("$source/$entry")&&$dest!=="$source/$entry")
            {
                copys("$source/$entry","$dest/$entry");
            }
            else
            {
                @copy("$source/$entry","$dest/$entry");
            }
        }
    }
    $h->close();
    return 1;
}

function deldirfiles($dir){
  $current_dir = opendir($dir);
  while($entryname = readdir($current_dir)){
     if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
        deldirfiles("${dir}/${entryname}");
     }elseif($entryname != "." and $entryname!=".."){
        unlink("${dir}/${entryname}");
     }
  }
  closedir($current_dir);
}

if( !$gBitUser->isAdmin() ) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' );
	die;
}

$struct_info = $structlib->s_get_structure_info($_REQUEST['struct']);
$gBitSmarty->assign_by_ref('struct_info',$struct_info);

$gBitSmarty->assign('generated','y');
if(isset($_REQUEST['create'])) {
  $name=$_REQUEST['name'];
  $dir=$_REQUEST['dir'];
  $gBitSmarty->assign('dir',$_REQUEST['dir']);
  $struct=$_REQUEST['struct'];
  $top=$_REQUEST['top'];
  $top='foo1';
  $output='';
  $output.="TikiHelp WebHelp generation engine<br/>";
  $output.="Generating WebHelp using <b>$name</b> as index. Directory: $name<br/>";
  $base = BITHELP_PKG_PATH."$dir";
  if(!is_dir(BITHELP_PKG_PATH."$dir")) {
    $output.="Creating directory structure in $base<br/>";
    mkdir(BITHELP_PKG_PATH."$dir");
    mkdir("$base/js");
    mkdir("$base/css");
    mkdir("$base/icons");
    mkdir("$base/menu");
    mkdir("$base/pages");
    mkdir("$base/pages/img");
    mkdir("$base/pages/img/wiki_up");
  }
  $output.="Eliminating previous files<br/>";
  deldirfiles("$base/js");
  deldirfiles("$base/css");
  deldirfiles("$base/icons");
  deldirfiles("$base/menu");
  deldirfiles("$base/pages");
  deldirfiles("$base/pages/img/wiki_up");
  // Copy base files to the webhelp directory
  copys("lib/bithelp","$base/");

  $structlib->structure_to_webhelp($struct,$dir,$top);
  $gBitSmarty->assign('generated','y');
}

// Display the template
$gBitSystem->display( 'bitpackage:wiki/create_webhelp.tpl');

?>
