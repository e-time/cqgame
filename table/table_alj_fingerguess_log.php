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

class table_alj_fingerguess_log extends discuz_table
{
	public function __construct() {

		$this->_table = 'alj_fingerguess_log';
		$this->_pk    = 'uid';

		parent::__construct();
	}
	public function range_by_uid($uid,$start,$limit,$sort,$order='desc'){
		return DB::fetch_all("select * from ".DB::table($this->_table)." where uid=$uid order by $sort $order limit $start,$limit");
	}
	public function count_by_uid($uid){
		return DB::result_first("select count(*) from ".DB::table($this->_table)." where uid=$uid");
	}
	public function count_guessmoney_by_uid($start,$perpage,$count){
		return DB::fetch_all("select username,sum(guessmoney) money  from ".DB::table($this->_table)." group by username order by money desc  limit $start,$perpage");
	}
	public function count_countmoney_by_uid(){
		$num=DB::fetch_all("select username,sum(guessmoney) money  from ".DB::table($this->_table)." group by username ");
		$conutnum=count($num);
		return $conutnum;
	}
	public function count_luckymoney_by_uid($start,$perpage){
		return DB::fetch_all("select username,sum(guessmoney) money  from ".DB::table($this->_table)." group by username order by money desc  limit $start,$perpage");
		
	}
	public function count_recessionmoney_by_uid($start,$perpage){
		return DB::fetch_all("select username,sum(guessmoney) money  from ".DB::table($this->_table)." group by username order by money asc  limit $start,$perpage");
		
	}
	public function count_right_by_uid($uid){
		return DB::result_first("select count(*)  from ".DB::table($this->_table)." where uid=$uid and guessmoney>0");
	}
	public function delete_ql($time){
		return DB::query("delete from ".DB::table($this->_table)." where guesstime<'".$time."'");
	}
	
	
}


?>