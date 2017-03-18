<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF
drop table IF EXISTS `pre_alj_fingerguess`;
drop table IF EXISTS `pre_alj_fingerguess_log`;
EOF;
runquery($sql);
$finish = TRUE;
?>