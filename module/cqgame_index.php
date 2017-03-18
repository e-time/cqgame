<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_G['uid']){
	showmessage(lang('plugin/cqgame','cqgame_1'), '', array(), array('login' => true));
}

$new=C::t('#cqgame#alj_fingerguess')->fetch_all_by_uid(0,9);
$config=$_G['cache']['plugin']['cqgame'];
$qluid=$config['qluid'];
$qluid=str_replace('，',',',$qluid);
$qluid=explode(',',$qluid);
//debug(in_array ($_G['uid'], $qluid));
if(in_array ($_G['uid'], $qluid)){
	$ql=1;
}
$roomtsy=str_replace('{tax}',$config['tax'],$config['roomtsy']);
$navtitle = $config['title'];
$metakeywords = $config['keywords'];
$metadescription = $config['description'];
$users = unserialize ($config['user']);
if (!in_array ($_G['groupid'], $users)) {
	showmessage($config['tsy']);
}
$credit=$config['credit'];
$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);

if($_G['lj_act']=='createroom'){
	if(submitcheck('submit')){
	
		$sign=$_G['lj_myguess'];
		if($mycredit<$_G['lj_guessmoney']){
			showmessage(lang('plugin/cqgame','cqgame_2'));
		}
		 
		 //$_SESSION['ea55_suijishu_cqgame']=$ea55_wmff_suijishu;
		if($_POST['ea55_suiji']!=$_SESSION['ea55_suijishu_cqgame']){
			showmessage('提交错误！');
			exit;
		}
	 $_SESSION['ea55_suijishu_cqgame']=$ea55_wmff_suijishu;
		if($config['min']>$_G['lj_guessmoney']){
			showmessage(lang('plugin/cqgame','cqgame_3').$config['min']);
		}
		if($config['max']<$_G['lj_guessmoney']){
			showmessage(lang('plugin/cqgame','cqgame_4').$config['max']);
		}
		if(!$_G['lj_guessremark']){
			$guessermark=lang('plugin/cqgame','cqgame_5');
		}else{
			$guessermark=$_G['lj_guessremark'];
		}
		if($_G['lj_myguess']==0){
			$_G['lj_myguess']=rand(1,3);
		}
		$myguessname=checkgtype($_G['lj_myguess']);
		if(!$_G['lj_guessmoney']){
			showmessage(lang('plugin/cqgame','cqgame_6'));
		}
		$insertarray=array(
			'uid'=>$_G['uid'],
			'guessmoney'=>$_G['lj_guessmoney'],
			'myguess'=>$_G['lj_myguess'],
			'guesstype'=>$_G['lj_guesstype'],
			'guessremark'=>$guessermark,
			'begintime'=>$_G['timestamp'],
			'endtime'=>$_G['timestamp'],
		);
		if ($_G['lj_guesstype']==0) {
			if($config['isdn']){
				$yourguess=rand(1,3);
				$yourguessname=checkgtype($yourguess);
				$checkwinnum=checkwin($_G['lj_myguess'],$yourguess);
				$config=$_G['cache']['plugin']['cqgame'];
				$pj=$config['pj'];
				if ($checkwinnum==0) {
					$result=-$_G['lj_guessmoney'];
					updatemembercount($_G['uid'],array($credit=>$result));
					$guessenddis=lang('plugin/cqgame','cqgame_7').$myguessname.lang('plugin/cqgame','cqgame_8').$yourguessname.lang('plugin/cqgame','cqgame_9').$_G['lj_guessmoney'];
				} elseif ($checkwinnum==1) {
					updatemembercount($_G['uid'],array($credit=>$pj));
					$result=$pj;
					$guessenddis=lang('plugin/cqgame','cqgame_7').$myguessname.lang('plugin/cqgame','cqgame_8').$yourguessname.lang('plugin/cqgame','cqgame_10').$config['pj'];
				} elseif ($checkwinnum==2) {
					$guesstaxck=$_G['lj_guessmoney']*(str_replace('%','',$config['tax'])/100);
					$_G['lj_guessmoney']=$_G['lj_guessmoney']-$_G['lj_guessmoney']*(str_replace('%','',$config['tax'])/100);
					$result=$_G['lj_guessmoney'];
					updatemembercount($_G['uid'],array($credit=>$_G['lj_guessmoney']));
					$guessenddis=lang('plugin/cqgame','cqgame_7').$myguessname.lang('plugin/cqgame','cqgame_8').$yourguessname.lang('plugin/cqgame','cqgame_11').$guesstaxck.lang('plugin/cqgame','cqgame_12').$_G['lj_guessmoney'];
				}
				$insertarray['username']=$_G['username'];
				$insertarray['islog']=1;
				$insertarray['yourname']=lang('plugin/cqgame','cqgame_13');
				$insertarray['yourguess']=$yourguess;
				$insertarray['result']=str_replace(lang('plugin/cqgame','cqgame_14'),$_G['username'],$guessenddis);
				

				$mylogarray=array(
					'uid'=>$_G['uid'],	
					'username'=>$_G['username'],
					'yourname'=>lang('plugin/cqgame','cqgame_13'),
					'guesstime'=>$_G['timestamp'],
					'guessmoney'=>$result
				);
				C::t('#cqgame#alj_fingerguess_log')->insert($mylogarray);
				C::t('#cqgame#alj_fingerguess')->insert($insertarray);
				showmessage($guessenddis,'plugin.php?id=cqgame&act=createroom&tips='.$guessenddis);
			}
			}
			if(!$_G['lj_roomnum']){
				$num=1;
			}else if($_G['lj_roomnum']>$config['roomnum']){
				showmessage(lang('plugin/cqgame','cqgame_15').$config['roomnum'].lang('plugin/cqgame','cqgame_34'));
			}else{
				$num=$_G['lj_roomnum'];
			}
			
			if($_G['lj_guesstype']==1||$_G['lj_guesstype']==2){
				for($i=1;$i<=$num;$i++){
					$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
					if($mycredit<$_G['lj_guessmoney']){
						$bug[]=$i;
						break;
					}
					if($config['min']>$_G['lj_guessmoney']){
						$bug[]=$i;
						break;
					}
					if($config['max']<$_G['lj_guessmoney']){
						$bug[]=$i;
						break;
					}
					if(!$_G['lj_guessremark']){
						$guessermark=lang('plugin/cqgame','cqgame_16');
					}else{
						$guessermark=$_G['lj_guessremark'];
					}
					
					if($sign==0||!$sign){
						$myguess=rand(1,3);
					}else{
						$myguess=$sign;
					}
					$myguessname=checkgtype($_G['lj_myguess']);
					if(!$_G['lj_guessmoney']){
						$bug[]=$i;
						break;
					}
					$insertarray=array(
						'uid'=>$_G['uid'],
						'guessmoney'=>$_G['lj_guessmoney'],
						'myguess'=>$myguess,
						'guesstype'=>$_G['lj_guesstype'],
						'guessremark'=>$guessermark,
						'begintime'=>$_G['timestamp'],
						'endtime'=>$_G['timestamp'],
					);
					if($_G['lj_guesstype']==1){
						updatemembercount($_G['uid'],array($credit=>'-'.$_G['lj_guessmoney']));
						$insertarray['username']=$_G['username'];
						$insertarray['islog']=0;
						C::t('#cqgame#alj_fingerguess')->insert($insertarray);
						//showmessage('房间创建成功，扣除押金'.$_G['lj_guessmoney'],'plugin.php?id=cqgame');
					}else if($_G['lj_guesstype']==2){
						$insertarray['username']=$_G['username'];
						$insertarray['yourname']=$_G['lj_yourname'];
						$check=C::t('common_member')->fetch_by_username($_G['lj_yourname']);
						if(!$check){
							showmessage(lang('plugin/cqgame','cqgame_17'));
						}
						if($_G['lj_yourname']==$_G['username']){
							showmessage(lang('plugin/cqgame','cqgame_18'));
						}
						$insertarray['islog']=0;
						updatemembercount($_G['uid'],array($credit=>'-'.$_G['lj_guessmoney']));
						C::t('#cqgame#alj_fingerguess')->insert($insertarray);
						
					}
					$yes=$i;
				}
				$bugnum=count($bug);
				
			showmessage($yes.lang('plugin/cqgame','cqgame_19').$bugnum.lang('plugin/cqgame','cqgame_20').$_G['lj_guessmoney']*$yes,'plugin.php?id=cqgame');
		}
		 
	}else{
		$config=$_G['cache']['plugin']['cqgame'];
		$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
		$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
		include template('cqgame:createroom');
	}
}else if($_G['lj_act']=='enterroom'){
	if(!$_G['lj_cid']){
		showmessage(lang('plugin/cqgame','cqgame_33'));
	}
	$config=$_G['cache']['plugin']['cqgame'];
	if(submitcheck('submit')){
		$home=C::t('#cqgame#alj_fingerguess')->fetch($_G['lj_cid']);
		
		if($_G['username']==$home[username]){
			showmessage(lang('plugin/cqgame','cqgame_21'));
		}
		$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
		if($mycredit<$home['guessmoney']){
			showmessage(lang('plugin/cqgame','cqgame_22'));
		}
		if($home['islog']){
			showmessage(lang('plugin/cqgame','cqgame_23'),"plugin.php?id=cqgame");
		}
		$yourguessname=checkgtype($home[myguess]);
		if($_G[lj_myguess]==0){
			$_G[lj_myguess]=rand(1,3);
		}
		$myguessname=checkgtype($_G[lj_myguess]);
		$checkwinnum=checkwin($_G['lj_myguess'],$home[myguess]);
		$pj=$config['pj'];
		$credit=$config['credit'];
		if ($checkwinnum==0) {
			$myresult=-$home['guessmoney'];
			$yourresult=$home['guessmoney']-$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			updatemembercount($_G['uid'],array($credit=>'-'.$home['guessmoney']));
			$homemoney=$home['guessmoney']+$home['guessmoney']-$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			updatemembercount($home['uid'],array($credit=>$homemoney));
			$shuilv=$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			updatemembercount($config['uid'],array($credit=>$shuilv));
			$guessenddis=lang('plugin/cqgame','cqgame_24').$myguessname.",".$home['username'].lang('plugin/cqgame','cqgame_25').$yourguessname.lang('plugin/cqgame','cqgame_9').$home['guessmoney'];
		} elseif ($checkwinnum==1) {
			$myresult=$pj;
			$yourresult=$pj;
			updatemembercount($_G['uid'],array($credit=>$pj));
			updatemembercount($home['uid'],array($credit=>$pj+$home['guessmoney']));
			$guessenddis=lang('plugin/cqgame','cqgame_24').$myguessname.",".$home['username'].lang('plugin/cqgame','cqgame_25').$yourguessname.lang('plugin/cqgame','cqgame_10').$config['pj'];
		} elseif ($checkwinnum==2) {
			//updatemembercount($home['uid'],array($credit=>-$home['guessmoney']));
			$guesstaxck=$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			$myresult=$home['guessmoney']-$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			$yourresult=-$home['guessmoney'];
			updatemembercount($_G['uid'],array($credit=>$myresult));
			$shuilv=$home['guessmoney']*(str_replace('%','',$config['tax'])/100);
			updatemembercount($config['uid'],array($credit=>$shuilv));
			$guessenddis=lang('plugin/cqgame','cqgame_24').$myguessname.",".$home['username'].lang('plugin/cqgame','cqgame_25').$yourguessname.lang('plugin/cqgame','cqgame_11').$guesstaxck.lang('plugin/cqgame','cqgame_12').$myresult;
		}


			$mylogarray=array(
					'uid'=>$_G['uid'],	
					'username'=>$_G['username'],
					'yourname'=>$home['username'],
					'guesstime'=>$_G['timestamp'],
					'guessmoney'=>$myresult
			);
			$yourlogarray=array(
				'uid'=>$home['uid'],	
				'username'=>$home['username'],
				'yourname'=>$_G['username'],
				'guesstime'=>$_G['timestamp'],
				'guessmoney'=>$yourresult	
			);
		C::t('#cqgame#alj_fingerguess_log')->insert($mylogarray);
		C::t('#cqgame#alj_fingerguess_log')->insert($yourlogarray);
		$updatearray['islog']=1;
		$updatearray['yourname']=$_G['username'];
		$updatearray['yourguess']=$_G[lj_myguess];
		$updatearray['result']=str_replace('你',$_G['username'],$guessenddis);
		C::t('#cqgame#alj_fingerguess')->update($_G['lj_cid'],$updatearray);
		showmessage($guessenddis,'plugin.php?id=cqgame&tips='.$guessenddis);
	}else{
		$credit=$config['credit'];
		$hsuid=$config['hsuid'];
		$hsuid=str_replace('，',',',$hsuid);
		$hsuid=explode(',',$hsuid);
		$home=C::t('#cqgame#alj_fingerguess')->fetch($_G['lj_cid']);
		if(in_array ($_G['uid'], $hsuid)){
			$zuobi='('.checkgtype($home['myguess']).')';
		}
		include template('cqgame:enterroom');
	}
}else if($_G['lj_act']=='log'){
	$config=$_G['cache']['plugin']['cqgame'];
	$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
	$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
	$currpage=$_G['lj_page']?$_G['lj_page']:1;
	$perpage=18;
	$num=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
	$start=($currpage-1)*$perpage;
	$loglist=C::t('#cqgame#alj_fingerguess_log')->range_by_uid($_G['uid'],$start,$perpage,'guesstime','desc');
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=cqgame&act=log', 0, 10, false, false);
	include template('cqgame:log');
}else if($_G['lj_act']=='ranklist'){
	$config=$_G['cache']['plugin']['cqgame'];
	$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
	$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
	$currpage=$_G['lj_page']?$_G['lj_page']:1;
	$perpage=18;
	$start=($currpage-1)*$perpage;
	
	if(!$_G['lj_do']||$_G['lj_do']=='lucky'){
		$luckymoney=C::t('#cqgame#alj_fingerguess_log')->count_luckymoney_by_uid(0,50);
		foreach($luckymoney as $luckyph){
			if($luckyph['money']>0){
				$lucky[]=$luckyph;
			}
		}
		$arr=$lucky;
	}else if($_G['lj_do']=='recession'){
		$recessionmoney=C::t('#cqgame#alj_fingerguess_log')->count_recessionmoney_by_uid(0,50);
		foreach($recessionmoney as $recessionph){
			if($recessionph['money']<0){
				$recession[]=$recessionph;
			}
		}
		$arr=$recession;
	}
	if($_G['groupid']==1){
		$num=C::t('#cqgame#alj_fingerguess_log')->count_countmoney_by_uid();
		$arr=C::t('#cqgame#alj_fingerguess_log')->count_guessmoney_by_uid($start,$perpage);
	}
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=cqgame&act=ranklist', 0, 10, false, false);
	include template('cqgame:ranklist');
}else if($_G['lj_act']=='loglist'){
	$config=$_G['cache']['plugin']['cqgame'];
	$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
	$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
	$currpage=$_G['lj_page']?$_G['lj_page']:1;
	$perpage=18;
	$num=C::t('#cqgame#alj_fingerguess')->count_by_loglist();
	$start=($currpage-1)*$perpage;
	$homelist=C::t('#cqgame#alj_fingerguess')->fetch_all_by_loglist($start,$perpage);
	foreach($homelist as $key=>$home){
		if($home[guesstype]==1&&$home[islog]==0){
			$homelist[$key]['yess']=1;
		}else if($home[guesstype]==2&&$_G['username']==$home[yourname]&&$home[islog]==0){
			$homelist[$key]['yess']=1;
		}else{
			$homelist[$key]['yess']=0;
		}
	}
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=cqgame&act=loglist', 0, 10, false, false);
	include template('cqgame:loglist');
}else if($_G['lj_act']=='del'){
	
	if($_G['lj_cid']){
		C::t('#seoeye#alj_fingerguess')->delete($_G['lj_cid']);
		showmessage(lang('plugin/cqgame','cqgame_26'),'plugin.php?id=cqgame');
	}else if(submitcheck('submit')){
		if($_G['lj_del_ql']&&$ql){
			$time=$_G['timestamp']-$_G['lj_del_ql']*86400;
			C::t('#seoeye#alj_fingerguess')->delete_ql($time);
			showmessage(lang('plugin/cqgame','cqgame_26'),'plugin.php?id=cqgame&act=del');
		}
	}
	
	include template('cqgame:del');
}else{
	if($config['iszdql']){
		$xsql=C::t('#cqgame#alj_fingerguess')->fetch_all_by_ql();
		foreach($xsql as $key=>$ql){
			if($_G['timestamp']-$ql['endtime']>$config['zdqltime']*60*60){
				updatemembercount($ql['uid'],array($credit=>$ql['guessmoney']));
				C::t('#seoeye#alj_fingerguess')->delete($ql['id']);
			}
		}
	}
	$del="plugin.php?id=cqgame&act=del&&cid=";
	$config=$_G['cache']['plugin']['cqgame'];
	$mycredit=C::t('#cqgame#alj_fingerguess')->fetch_extcredits($_G['uid'],$config['credit']);
	$count=C::t('#cqgame#alj_fingerguess_log')->count_by_uid($_G['uid']);
	$currpage=$_G['lj_page']?$_G['lj_page']:1;
	$perpage=18;
	$num=C::t('#cqgame#alj_fingerguess')->count_by_log();
	$start=($currpage-1)*$perpage;
	$homelist=C::t('#cqgame#alj_fingerguess')->fetch_all_by_log($start,$perpage);
	foreach($homelist as $key=>$home){
		if($home[guesstype]==1&&$home[islog]==0){
			$homelist[$key]['yess']=1;
		}else if($home[guesstype]==2&&$_G['username']==$home[yourname]&&$home[islog]==0||($_G['username']==$home['username']&&$home[islog]==0)){
			$homelist[$key]['yess']=1;
		}else{
			$homelist[$key]['yess']=0;
		}
	}
	$paging = helper_page :: multi($num, $perpage, $currpage, 'plugin.php?id=cqgame', 0, 10, false, false);
	include template('cqgame:index');
}
//判断函数
function checkwin($var1,$var2) {
	$ckiswin = 0;
	$var1=intval(trim($var1));
	$var2=intval(trim($var2));
	$var3=$var1-$var2;
	if ($var1==$var2) {
		$ckiswin = 1;
	} elseif($var1<$var2 && $var3<>-2) {
		$ckiswin = 2;
	} elseif($var1>$var2 && $var3==2) {
		$ckiswin = 2;
	} else {
		$ckiswin = 0;
	}
	return $ckiswin;
}

function checkgtype($var1) {
	$var1=intval(trim($var1));
	switch($var1) {
		case 1: $gtypename=lang('plugin/cqgame','cqgame_27'); break;
		case 2: $gtypename=lang('plugin/cqgame','cqgame_28'); break;
		case 3: $gtypename=lang('plugin/cqgame','cqgame_29'); break;
		default: $gtypename=lang('plugin/cqgame','cqgame_33'); break;
	}
	return $gtypename;
}

function checkgch($var1) {
	$var1=intval(trim($var1));
	switch($var1) {
		case 0: $gtypename=lang('plugin/cqgame','cqgame_30'); break;
		case 1: $gtypename=lang('plugin/cqgame','cqgame_31'); break;
		case 2: $gtypename=lang('plugin/cqgame','cqgame_32'); break;
		default: $gtypename=lang('plugin/cqgame','cqgame_33'); break;
	}
	return $gtypename;
}
?>