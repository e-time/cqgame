<?php
/**
 *      [Liangjian] (C)2001-2099 Liangjian Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: fingerguess.inc.php liangjian $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$_G['setting']['switchwidthauto']=0;
$_G['setting']['allowwidthauto']=1;
$modarray = array('index');
foreach($_GET as $k => $v) {
	$_G['lj_'.$k] = daddslashes($v); 
	
}
session_start(); 
$ea55_wmff_suijishu=rand(10000,90000);
$mod = isset($_G['lj_mod']) ? $_G['lj_mod'] : '';
$mod = !in_array($mod, $modarray) ? 'index' : $mod;

if(!$_SESSION['ea55_suijishu_cqgame']){
$_SESSION['ea55_suijishu_cqgame']=$ea55_wmff_suijishu;
}
require DISCUZ_ROOT.'./source/plugin/cqgame/module/cqgame_'.$mod.'.php';
?>