<?php
class CopyrightsLib extends BitBase {
	function CopyrightsLib() {
		BitBase::BitBase();
	}
	function list_copyrights( $pPageId ) {
		$query = "select * from `".BIT_DB_PREFIX."tiki_copyrights` WHERE `page_id`=? order by ".$this->convert_sortmode( "copyright_order_asc" );
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_copyrights` WHERE `page_id`=?";
		$result = $this->query($query, array( $pPageId ));
		$cant = $this->getOne($query_cant, array( $pPageId ));
		$ret = array();
		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}
		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
	function top_copyright_order( $pPageId ) {
		$query = "select MAX(`copyright_order`) from `".BIT_DB_PREFIX."tiki_copyrights` where `page_id` = ?";
		return $this->getOne($query, array( $pPageId ));
	}
	function unique_copyright( $pPageId , $title) {
		$query = "select `copyrightID` from `".BIT_DB_PREFIX."tiki_copyrights` where `page_id`=? and `title`=?";
		return $this->getOne($query, array( $pPageId ,$title));
	}
	function add_copyright( $pPageId , $title, $year, $authors, $pUserId) {
		//$unique = $this->unique_copyright( $pPageId ,$title);
		//if($unique != 0) {
		// security here?
		//$this->edit_copyright($unique,$title,$year,$authors,$pUserId);
		//return;
		//}
		$top = $this->top_copyright_order( $pPageId );
		$order = $top + 1;
		$query = "insert into `".BIT_DB_PREFIX."tiki_copyrights` (`page_id`, `title`, `year`, `authors`, `copyright_order`, `user_id`) values (?,?,?,?,?,?)";
		$this->query($query,array( $pPageId ,$title,$year,$authors,$order,$pUserId));
		return true;
	}
	function edit_copyright($id, $title, $year, $authors, $pUserId) {
		$query = "update `".BIT_DB_PREFIX."tiki_copyrights` SET `year`=?, `title`=?, `authors`=?, `user_id`=? where `copyright_id`=?";
		$this->query($query,array($year,$title,$authors,$pUserId,(int)$id));
		return true;
	}
	function remove_copyright($id) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_copyrights` where `copyright_id`=?";
		$this->query($query,array((int)$id));
		return true;
	}
	function up_copyright($id) {
		$query = "update `".BIT_DB_PREFIX."tiki_copyrights` set `copyright_order`=`copyright_order`-1 where `copyright_id`=?";
		$result = $this->query($query,array((int)$id));
		return true;
	}
	function down_copyright($id) {
		$query = "update `".BIT_DB_PREFIX."tiki_copyrights` set `copyright_order`=`copyright_order`+1 where `copyright_id`=?";
		$result = $this->query($query,array((int)$id));
		return true;
	}
}

global $copyrightslib;
$copyrightslib = new CopyrightsLib();

?>
