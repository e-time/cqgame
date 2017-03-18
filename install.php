<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//start to put your own code DROP TABLE IF EXISTS `pre_alj_fingerguess`;
$sql = <<<EOF
drop table IF EXISTS `pre_alj_fingerguess`;
CREATE TABLE IF NOT EXISTS `pre_alj_fingerguess` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(15) NOT NULL default '',
  `myguess` tinyint(1) NOT NULL default '0',
  `guessmoney` int(10) NOT NULL default '0',
  `guesstype` tinyint(1) NOT NULL default '0',
  `guessremark` text,
  `begintime` int(10) unsigned NOT NULL default '0',
  `yourname` varchar(15) NOT NULL default '',
  `yourguess` tinyint(1) NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `islog` tinyint(1) NOT NULL default '0',
  `result` varchar(255) NULL default '',
  PRIMARY KEY  (`id`)
);
drop table IF EXISTS `pre_alj_fingerguess_log`;
CREATE TABLE IF NOT EXISTS `pre_alj_fingerguess_log` (
  `uid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `yourname` varchar(255) NOT NULL,
  `guesstime` int(10) NOT NULL,
  `guessmoney` int(10) NOT NULL
);
EOF;

runquery($sql);
//finish to put your own code DROP TABLE IF EXISTS `pre_alj_fingerguess_log`;
$finish = TRUE;
?>