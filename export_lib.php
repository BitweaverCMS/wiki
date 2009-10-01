<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_wiki/export_lib.php,v 1.12 2009/10/01 14:17:07 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: export_lib.php,v 1.12 2009/10/01 14:17:07 wjames5 Exp $
 * @package wiki
 */

/**
 * required setup
 */
require_once( KERNEL_PKG_PATH.'BitBase.php' );
require_once( WIKI_PKG_PATH.'BitPage.php' );
/**
 * @package wiki
 * @subpackage ExportLib
 */
class ExportLib extends BitBase {
	function ExportLib() {
		BitBase::BitBase();
	}

	function MakeWikiZip( $pExportFile ) {
		global $gBitUser,$gBitSystem;
		include_once (UTIL_PKG_PATH."tar.class.php");
		$tar = new tar();
		$query = "SELECT wp.`page_id` from `".BIT_DB_PREFIX."wiki_pages` wp INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) 
				  ORDER BY lc.".$this->mDb->convertSortmode("title_asc");
		$result = $this->mDb->query($query,array());
		while ($res = $result->fetchRow()) {
			$page_id = $res["page_id"];
			$content = $this->export_wiki_page($page_id, 0);
			$tar->addData($page_id, $content, $gBitSystem->getUTCTime());
		}
		$tar->toTar( $pExportFile, FALSE); 
		return '';
	}

	function export_wiki_page($page_id, $nversions = 1) {
		global $gBitSystem;
		$head = '';
		$head .= "Date: " . $gBitSystem->mServerTimestamp->get_rfc2822_datetime(). "\r\n";
		$head .= sprintf("Mime-Version: 1.0 (Produced by Tiki)\r\n");
		$iter = $this->get_page_history($page_id);
		$gWikiPage = new BitPage( $page_id );
		$gWikiPage->load();
		$info = $gWikiPage->mInfo;
		$parts = array();
		$parts[] = MimeifyPageRevision($info);
		if ($nversions > 1 || $nversions == 0) {
			foreach ($iter as $revision) {
				$parts[] = MimeifyPageRevision($revision);
				if ($nversions > 0 && count($parts) >= $nversions)
					break;
			}
		}
		if (count($parts) > 1)
			return $head . MimeMultipart($parts);
		assert ($parts);
		return $head . $parts[0];
	}

	// Returns all the versions for this page
	// without the data itself
	function get_page_history($page_id) {
		$query = "SELECT lc.`title`, th.`description`, th.`version`, th.`last_modified`, th.`user_id`, th.`ip`, th.`data`, th.`history_comment`, uu.`login` as `creator_user`, uu.`real_name` " .
				 "FROM `".BIT_DB_PREFIX."wiki_pages` wp " .
				 "INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (lc.`content_id` = wp.`content_id`) " .
				 "INNER JOIN `".BIT_DB_PREFIX."liberty_content_history` th ON (th.`page_id` = th.`page_id`) " .
				 "INNER JOIN `".BIT_DB_PREFIX."users_users` uu ON (uu.`user_id` = th.`user_id`) " .
				 "WHERE wp.`page_id`=? order by th.".$this->mDb->convertSortmode("version_desc");
		$result = $this->mDb->query($query,array($page_id));
		$ret = array();
		while ($res = $result->fetchRow()) {
			array_push( $ret, $res );
		}
		return $ret;
	}
}
$exportlib = new ExportLib();
?>
