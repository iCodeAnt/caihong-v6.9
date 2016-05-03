<?php
//程序更新文件
include("update.inc.php");

$do=isset($_GET['do'])?$_GET['do']:1;
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="width=device-width; initial-scale=1.0;  minimum-scale=1.0; maximum-scale=2.0"/>
<link rel="stylesheet" type="text/css" href="../style/css1.css">
<link rel="shortcut icon" href="../images/favicon.ico">
<title>更新程序</title>
</head>
<body style="max-width:480px;">
<?php

if($conf['version']=='5090') {
	echo'<div class="w h">系统提示:</div><div class="box">您已经升级到V5.9版本!</div>';
} else {

if(defined('SQLITE')) {
	exit('SQLite数据库不支持更新！请使用全新安装。');
}

if(!$conf['version'] || $conf['version']<'4600')//4.x及之前版本升级
{
exit('<div class="w h">系统提示:</div><div class="box">请 <a href="update.php">点击此处</a> 完成升级。</div>');
}

if($conf['version']=='5040')
{
$sql="ALTER TABLE `".DBQZ."_config`
ADD `kfqq` VARCHAR(150) NULL,
ADD `mail_api` int(1) NOT NULL DEFAULT 0,
ADD `footer` TEXT NULL";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `version`='5090' where `id`='1'");
echo 'v5.9数据库更新成功！<br><br><a href="../index.php">>>返回网站首页</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}
elseif($conf['version']=='5030')
{
$sql="ALTER TABLE `".DBQZ."_qq`
ADD `status2` int(4) NOT NULL DEFAULT 1";
$sql2="ALTER TABLE `".DBQZ."_config`
ADD  `qqloginid` INT(4) NOT NULL DEFAULT 1";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `version`='5040' where `id`='1'");
echo 'v5.4数据库更新成功！<br><br><a href="../index.php">>>返回网站首页</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}
elseif($conf['version']=='5010')
{
$sql="ALTER TABLE  `".DBQZ."_job` ADD  `cookie` text NULL";
$sql2="ALTER TABLE  `".DBQZ."_config` ADD  `sitetitle` text NULL,
 ADD  `mail_name` VARCHAR(150) NULL,
 ADD  `mail_pwd` VARCHAR(150) NULL,
 ADD  `mail_stmp` VARCHAR(150) NULL,
 ADD  `mail_port` VARCHAR(150) NULL,
 ADD  `siteurl` VARCHAR(150) NULL";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `version`='5030',`sitetitle`='-分布式秒赞挂机系统',`mail_stmp`='smtp.163.com',`mail_port`='25',`siteurl`='$siteurl' where `id`='1'");
echo 'v5.3数据库更新成功！<br><br><a href="../index.php">>>返回网站首页</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}
elseif($conf['version']=='5001')
{
$sql="ALTER TABLE `".DBQZ."_qq` MODIFY COLUMN  `qq` varchar(20) NOT NULL";
$sql2="ALTER TABLE `".DBQZ."_config`
ADD  `cronkey` VARCHAR(150) DEFAULT NULL,
ADD  `qqapiid` INT(4) NOT NULL DEFAULT 0";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `version`='5010' where `id`='1'");
echo 'v5.1数据库更新成功！<br><br><a href="../index.php">>>返回网站首页</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}
elseif($conf['version']<='5000')
{
echo '<div class="w h">V5.0更新程序</div>';
if($do=='1') {
$DB->query("update `".DBQZ."_config` `switch` ='0' where `id`='1';");
echo '<div class="box">';
echo'<pre>';
include("../readme.txt");
echo'</pre>';
echo "<hr><a href='{$_SERVER['PHP_SELF']}?do=2'>>>立即开始更新</a>";
} 

elseif($do=='2') {
$c=0;
$d=0;
$a=file_get_contents("update2.sql");
$a=str_replace('{DBQZ}', DBQZ, $a);
$a=explode(";",$a);
$error='';
for($i=0;$i<count($a);$i++)
{
	if($DB->query($a[$i]))
	{
		$c++;
	}else{
		$d++;
		$error.=$DB->error().'<br/>';
	}
}
echo '<div class="box">';
//if($d==0) {
echo '数据表结构更新成功<br/>SQL成功'.$c.'句/失败'.$d.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=3">>>下一步</a>';
//} else {
//echo '数据表结构更新失败<br/>SQL成功'.$c.'句/失败'.$d.'句<br/>错误信息：'.$error.'<br/><a href="'.$_SERVER['PHP_SELF'].'?do=2">点此进行重试</a>';
//}
}

elseif($do=='3') {
$u="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if(!file_exists("job.lock"))@file_put_contents("job.lock",'wone');
@file_get_contents("http://cron.aliapp.com/api/tongji2.php?url=$u");
$DB->query("update `".DBQZ."_config` set `version` ='5040',`css2` ='2',`sitetitle`='-分布式秒赞挂机系统',`mail_stmp`='smtp.163.com',`mail_port`='25',`siteurl`='$siteurl' where `id`='1';");
echo '<div class="box"><font color="green">更新成功！当前版本 V5.4</font><br/><br/><a href="../index.php">>>返回网站首页</a>';
}

}
}

echo'<hr>';
include(ROOT.'includes/foot.php');
echo'</div></body></html>';
?>