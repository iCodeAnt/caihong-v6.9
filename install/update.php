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
//程序更新文件

include("update.inc.php");

$do=isset($_GET['do'])?$_GET['do']:1;

if($conf['version']>='4600') {
	echo'<div class="w h">系统提示:</div><div class="box">您已经升级到V4.9版本!</div>';
} else {

if(defined('SQLITE')) {
	exit('SQLite数据库不支持更新！请使用全新安装。');
}

if(!$conf['version'] || $conf['version']<='2500')//2.5及之前版本升级
{
if(!$conf['version'])
{
$sql="ALTER TABLE `".DBQZ."_config` 
ADD COLUMN `interval` int(10) NOT NULL DEFAULT 0,
ADD COLUMN `version` int(4) NOT NULL";
if($DB->query($sql)){
$DB->query("update ".DBQZ."_config set version='2100' where id='1'");
echo 'v2.0数据库更新成功！<br>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}

if($conf['version']=='2100')
{
$sql="ALTER TABLE `".DBQZ."_config`
ADD COLUMN `switch` int(1) NOT NULL DEFAULT 1,
ADD COLUMN `css` int(1) NOT NULL,
ADD COLUMN `sysnum` int(2) NOT NULL";
$sql2="ALTER TABLE `".DBQZ."_job` 
RENAME TO `".DBQZ."_job1`";
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `css` ='1',`sysnum` ='4',`version`='2500' where `id`='1'");
echo 'v2.5数据库更新成功！<br>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}

if($conf['version']=='2500')
{
$sql="ALTER TABLE `".DBQZ."_job1`
ADD COLUMN `zt` int(1) NOT NULL DEFAULT 0";
if($DB->query($sql)){
$DB->query("update `".DBQZ."_config` set `version`='3000' where `id`='1'");
echo 'v3.0数据库更新成功！<br/>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
}
exit();
}

if($conf['version']=='4000')
{
$sql="ALTER TABLE  `".DBQZ."_user`
ADD  `zcip` VARCHAR( 15 ) DEFAULT NULL ,
ADD  `dlip` VARCHAR( 15 ) DEFAULT NULL";
$sql2="ALTER TABLE  `".DBQZ."_chat`
ADD  `ip` VARCHAR( 15 ) DEFAULT NULL";
if($DB->query($sql) && $DB->query($sql2)){
$DB->query("update `".DBQZ."_config` set `version`='4200' where `id`='1'");
echo 'v4.2数据库更新成功！<br/>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
exit;
}

elseif($conf['version']=='4200')
{
$sql="ALTER TABLE  `".DBQZ."_config` ADD  `banned` TEXT NULL";
if($DB->query($sql)){
$DB->query("update `".DBQZ."_config` set `version`='4300' where `id`='1'");
echo 'v4.3数据库更新成功！<br/>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
exit;
}

elseif($conf['version']=='4300')
{
$sql="ALTER TABLE  `".DBQZ."_config` ADD  `multi` INT(1) NOT NULL DEFAULT '0',
ADD  `loop` VARCHAR(150) NOT NULL DEFAULT '0-0-0-0-0-0-0-0'";
if($DB->query($sql)){
$DB->query("update `".DBQZ."_config` set `version`='4600' where `id`='1'");
echo 'v4.6数据库更新成功！<br>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update2.php"> <a href="update2.php">点此手动跳转</a>';
}else{
exit("数据库更新失败，请重试！<br/>".$DB->error());
}
exit;


}else {

//4.0版本升级程序
echo '<div class="w h">V4.0更新程序</div>';
if($do=='1') {
$DB->query("update `".DBQZ."_config` `switch` ='0' where `id`='1';");
echo '<div class="box">';
echo'<pre>';
include("update.md");
echo'</pre>';
echo "<br><a href='{$_SERVER['PHP_SELF']}?do=2'>>>立即开始更新</a>";
} 

if($do=='2') {
$c=0;
$d=0;
$a=file_get_contents("update.sql");
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
if($d==0) {
echo '数据表结构更新成功<br/>SQL成功'.$c.'句/失败'.$d.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=3">>>下一步</a>';
} else {
echo '数据表结构更新失败<br/>SQL成功'.$c.'句/失败'.$d.'句<br/>错误信息：'.$error.'<br/><a href="'.$_SERVER['PHP_SELF'].'?do=2">点此进行重试</a>';
}
}

if($do=='3') {
$c=0;
for($i=2;$i<=8;$i++){
$rs=$DB->query("SELECT * FROM ".DBQZ."_job{$i} order by jobid asc");
while($row = $DB->fetch($rs))
{
if(!$row['url'])continue;
$sql="insert into `".DBQZ."_job1` (`sysid`,`url`,`post`,`postfields`,`lx`,`timea`,`timeb`,`usep`,`proxy`,`referer`,`useragent`,`start`,`stop`) values ('".$i."','".$row['url']."','".$row['post']."','".$row['postfields']."','".$row['lx']."','".$row['timea']."','".$row['timeb']."','".$row['usep']."','".$row['proxy']."','".$row['referer']."','".$row['useragent']."','".$row['start']."','".$row['stop']."')";
$DB->query($sql);
$c++;
}
}
$DB->query("ALTER TABLE `".DBQZ."_job1` RENAME TO `".DBQZ."_job`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job2`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job3`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job4`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job5`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job6`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job7`");
$DB->query("DROP TABLE IF EXISTS `".DBQZ."_job8`");
echo '<div class="box">任务数据表合并成功<br/>SQL共执行'.$c.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=4">>>下一步</a>';
}

if($do=='4') {
$u="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if(!file_exists("job.lock"))@file_put_contents("job.lock",'wone');
@file_get_contents("http://cron.aliapp.com/api/tongji.php?url=$u");
$DB->query("update `".DBQZ."_config` set `version` ='4000',`switch` ='1',`build` ='$date' where `id`='1'");
echo '<div class="box"><font color="green">更新成功！当前版本 V4.0</font><br/><br/>3秒后自动跳转... <meta http-equiv="refresh" content="3;url=update.php"> <a href="update.php">点此手动跳转</a>';
}

}
}
echo'<hr>';
include(ROOT.'includes/foot.php');
echo'</div></body></html>';
?>