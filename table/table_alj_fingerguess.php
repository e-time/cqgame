<?php
/**
 *      [Liangjian] (C)2001-2099 Liangjian Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_alj_fingerguess.php liangjian $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_alj_fingerguess extends discuz_table
{
	public function __construct() {

		$this->_table = 'alj_fingerguess';
		$this->_pk    = 'id';

		parent::__construct();
	}
	public function fetch_extcredits($uid,$id){
		return DB::result_first("select extcredits".$id." from ".DB::table('common_member_count')." where uid=".$uid);
	}
	public function count_by_uid($uid){
		return DB::result_first("select count(*) from ".DB::table($this->_table)." where islog=1 and uid=".$uid);
	}
	public function fetch_all_by_loglist($start,$limit){
		return DB::fetch_all("select *  from ".DB::table($this->_table)." where islog=1 order by id desc limit $start,$limit");
	}
	public function fetch_all_by_log($start,$limit){
		return DB::fetch_all("select *  from ".DB::table($this->_table)." where islog=0 order by id desc limit $start,$limit");
	}
	public function fetch_all_by_ql(){
		return DB::fetch_all("select *  from ".DB::table($this->_table)." where islog=0 order by id asc ");
	}
	public function fetch_all_by_uid($start,$limit){
		return DB::fetch_all("select *  from ".DB::table($this->_table)." group by uid order by id desc limit $start,$limit");
	}
	public function count_by_loglist(){
		return DB::result_first("select count(*)  from ".DB::table($this->_table)." where islog=1 ");
	}
	public function count_by_log(){
		return DB::result_first("select count(*)  from ".DB::table($this->_table)." where islog=0 ");
	}
	public function delete_ql($time){
		return DB::query("delete from ".DB::table($this->_table)." where islog='1' and endtime<'".$time."'");
	}

}


?>