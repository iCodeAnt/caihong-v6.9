DROP TABLE IF EXISTS `{DBQZ}_user`;</explode>
create table `{DBQZ}_user` (
`userid` int(11) NOT NULL auto_increment,
`pass` varchar(150) NOT NULL,
`user` varchar(150) NOT NULL,
`num` varchar(100) NOT NULL default '0',
`qqnum` INT(150) NOT NULL default '0',
`date` datetime NOT NULL,
`last` datetime NOT NULL,
`zcip` VARCHAR( 15 ) DEFAULT NULL,
`dlip` VARCHAR( 15 ) DEFAULT NULL,
`vip` INT(1) NOT NULL DEFAULT '0',
`coin` INT(150) NOT NULL DEFAULT '0',
`email` varchar(150) NULL,
`vipdate` date NULL,
`phone` VARCHAR(30) NULL,
`active` int(1) NULL,
`daili` int(1) NOT NULL DEFAULT '0',
`daili_rmb` VARCHAR(100) NOT NULL DEFAULT '0',
`daili_qq` VARCHAR(20) NOT NULL DEFAULT '0',
`mail_on` int(1) NOT NULL DEFAULT '1',
`vipsign` int(11) NOT NULL DEFAULT 0,
`vipjf` int(11) NOT NULL DEFAULT 0,
`vipsigntime` date NULL,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

DROP TABLE IF EXISTS `{DBQZ}_chat`;</explode>
CREATE TABLE `{DBQZ}_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(150) DEFAULT NULL,
  `sj` varchar(150) DEFAULT NULL,
  `nr` varchar(500) DEFAULT NULL,
  `to` varchar(150) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

DROP TABLE IF EXISTS `{DBQZ}_info`;</explode>
create table `{DBQZ}_info` (
`sysid` int(11) NOT NULL,
`last` datetime NULL,
`times` int(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sysid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

INSERT INTO `{DBQZ}_info`(`sysid`, `times`) VALUES
('0', '0'),
('1', '0'),
('2', '0'),
('3', '0'),
('4', '0'),
('5', '0'),
('6', '0'),
('7', '0'),
('8', '0'),
('9', '0'),
('10', '0'),
('11', '0'),
('12', '0'),
('13', '0'),
('14', '0'),
('15', '0'),
('16', '0'),
('3001', '0');</explode>

DROP TABLE IF EXISTS `{DBQZ}_job`;</explode>
create table `{DBQZ}_job` (
`jobid` int(11) NOT NULL auto_increment,
`sysid` INT(150) NOT NULL,
`type` INT(4) NOT NULL default '0',
`url` text NOT NULL,
`lx` varchar(150) NOT NULL default '0',
`mc` VARCHAR( 255 ) NOT NULL default '网址挂刷任务',
`usep` int(1) NULL,
`proxy` varchar(30) NULL,
`referer` varchar(250) NULL,
`useragent` varchar(250) NULL,
`start` int(2) NULL,
`stop` int(2) NULL,
`zt` int(1) NOT NULL default '0',
`post` int(1) NULL,
`postfields` text NULL,
`cookie` text NULL,
`timea` datetime NOT NULL,
`timeb` datetime NOT NULL,
`times` varchar(250) NOT NULL default '0',
`server` varchar(250) NOT NULL default '1',
`pl` INT(150) NOT NULL DEFAULT '0',
`time` INT(150) NOT NULL DEFAULT '0',
`day` VARCHAR(30) NULL,
 PRIMARY KEY (`jobid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

DROP TABLE IF EXISTS `{DBQZ}_qq`;</explode>
create table `{DBQZ}_qq` (
`id` int(11) NOT NULL auto_increment,
`lx` varchar(150) NOT NULL default '0',
`qq` varchar(20) NOT NULL,
`pw` varchar(250) NULL,
`sid` varchar(150) NULL,
`skey` varchar(150) NULL,
`apiid` INT(4) NOT NULL default '0',
`status` INT(1) NOT NULL default '0',
`status2` int(4) NOT NULL default '0',
`time` datetime NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

DROP TABLE IF EXISTS `{DBQZ}_kms`;</explode>
CREATE TABLE `{DBQZ}_kms` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`kind` tinyint(1) NOT NULL DEFAULT '1',
`daili` int(11) NOT NULL DEFAULT '0',
`km` varchar(50) NULL,
`value` int(11) NOT NULL DEFAULT '0',
`isuse` tinyint(1) DEFAULT '0',
`user` varchar(50) DEFAULT NULL,
`usetime` datetime DEFAULT NULL,
`addtime` datetime DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

DROP TABLE IF EXISTS `{DBQZ}_config`;</explode>
create table `{DBQZ}_config` (
`k` varchar(32) NOT NULL,
`v` text NULL,

PRIMARY KEY  (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</explode>

INSERT INTO `{DBQZ}_config` (`k`, `v`) VALUES
('zc', '1'),
('max', '120'),
('sjyl', '0'),
('pagesize', '30'),
('sitename', '雨峰网络任务'),
('sitetitle', '-分布式秒赞秒评挂机平台'),
('gg', '<center><iframe width="250" scrolling="no" height="25" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&id=10&icon=1"></iframe>
<p class="bg-warning" style="padding: 10px; font-size: 100%;max-width:480px;">雨峰网络任务VIP开放购买，VIP会员可免扣雨峰币，并能使用更多功能！<br/>VIP5元/月。充值卡：1元100雨峰币<br/><a class="btn btn-success btn-block" href="http://cron.917ka.com/" target="_blank">立即购买VIP会员获尊贵身份</a></p>
<p class="bg-danger" style="padding: 10px; font-size: 90%;max-width:480px;">★本站正式开启雨峰币服务，所有用户全部赠送100雨峰币。任务运行会消耗雨峰币，当雨峰币不足时会自动暂停任务。</p>
<p class="bg-success" style="padding: 10px; font-size: 90%;text-align:center;max-width:480px;"><a href="dlyz.php" input class="btn radius btn-info" type="button">代理验证</a>
<a href="http://wpa.qq.com/msgrd?v=3&uin=1048340641&site=qq&menu=yes" input class="btn radius btn-info" type="button">联系站长</a></p></center>'),
('guang', '<table class="table table-bordered">
<tbody>
<tr height="50">
<td><button type="button" class="btn btn-block btn-warning"> <a href="http://52yuf.com/" target="_blank"><span style="color:#fff;">♚雨峰云任务♚</span></a></button></td>
<td><button type="button" class="btn btn-block btn-warning"><a href="http://52yuf.com/" target="_blank"><span style="color:#fff;">♚雨峰导航网♚</span></a></button></td>
</tr>
<tr height="50">
<td><button type="button" class="btn btn-block btn-danger"><a href="http://52yuf.com/" target="_blank"><span style="color:#fff;">♚雨峰博客♚</span></a></button></td>
<td><button type="button" class="btn btn-block btn-danger"><a href="http://52yuf.com/" target="_blank"><span style="color:#fff;">♚雨峰工具网♚</span></a></button></td>
</tr>
<tr height="50">
<td><button type="button" class="btn btn-block btn-success"><a href="52yuf.com" target="_blank"><span style="color:#fff;">♚友链♚</span></a></button></td>
<td><button type="button" class="btn btn-block btn-success"><a href="52yuf.com" target="_blank"><span style="color:#fff;">♚友链♚</span></a></button></td>
</tr>
<tr height="50">
<td><button type="button" class="btn btn-block btn-info"><a href="52yuf.com" target="_blank"><span style="color:#fff;">♚友链♚</span></a></button></td>
<td><button type="button" class="btn btn-block btn-info"><a href="52yuf.com" target="_blank"><span style="color:#fff;">♚友链♚</span></a></button></td>
</tr></tbody>
</table>'),
('bottom', '域名:<a href="http://52yuf.com/">52yuf.com</a><br/>
<li class="list-group-item"><font color="red">站长QQ：</font>1048340641<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1048340641&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:1048340641:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a><br>
<font color="red">用户QQ群：</font>26133449<a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=6d6e3247cecfbbab20deb1e2e4c5dfa354a2f64ae878c4f5dc881e5581fd8b74"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="点击加群" title="点击加群"></a></li>
<li class="list-group-item">还没有账号？赶快<a href="index.php?mod=reg" input class="btn btn-sm btn-info" type="button">注册</a>一个吧！</li>'),
('times', '1'),
('interval', '50'),
('version', '6150'),
('switch', '1'),
('css', '5'),
('css2', '2'),
('sysnum', '8'),
('bulk', '10'),
('adminid', '1'),
('seconds', '0-0-0-0-0-0-0-0'),
('show', '1分钟|1分钟|1分钟|1分钟|1分钟|5分钟|5分钟|6小时'),
('block', ''),
('banned', ''),
('apiserver', '2'),
('multi', '0-0-0-0-0-0-0-0'),
('loop', '0-0-0-0-0-0-0-0'),
('cronkey', ''),
('jifen', '1'),
('rules', '100|100|1|2|3|3|10|5'),
('qqapiid', '0'),
('qqloginid', '1'),
('mail_name', 'net909@163.com'),
('mail_pwd', '123456'),
('mail_stmp', 'smtp.163.com'),
('mail_port', '25'),
('siteurl', ''),
('cache', ''),
('vip_coin', '1'),
('coin_name', '雨峰币'),
('vip_func', ''),
('shop', '<p style="color:red">1、VIP卡卡密和充值卡卡密是分开的，注意区分！<br>2、自助购买卡密:<a target="_blank" href="http://52yuf.com/">点击进入</a><br>3、购买卡密联系QQ:<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1048340641&site=qq&menu=yes">1048340641</a><br>4、<a href="index.php?mod=free">点此获取试用卡密</a></p>'),
('footer', '<br>Powered by <a href="http://52yuf.com">雨峰</a>! V6.9 模板设计:<a href="http://52yuf.com/" target="_blank">Kenvix</a>'),
('qqlimit', '10'),
('qqlimit2', '0'),
('app_version', '1.0'),
('app_log', ''),
('app_start_is', '0'),
('app_start', '');