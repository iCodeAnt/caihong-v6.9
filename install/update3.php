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
<title>更新程序V6</title>
</head>
<body style="max-width:480px;">
<?php

if($conf['version']=='6150') {
	echo'<div class="w h">系统提示:</div><div class="box">您已经升级到V6.4版本!</div>';
} else {

if(defined('SQLITE')) {
	exit('SQLite数据库不支持更新！请使用全新安装。');
}

if(!$conf['version'] || $conf['version']<'5040')//5.x及之前版本升级
{
exit('<div class="w h">系统提示:</div><div class="box">请 <a href="update2.php">点击此处</a> 完成升级。</div>');
}

if($conf['version']=='6140')
{
$sql="ALTER TABLE `".DBQZ."_user`
ADD `mail_on` int(1) NOT NULL DEFAULT '1',
ADD `vipsign` int(11) NOT NULL DEFAULT 0,
ADD `vipjf` int(11) NOT NULL DEFAULT 0,
ADD `vipsigntime` date NULL";
$sql2="ALTER TABLE  `".DBQZ."_job` MODIFY COLUMN `url` text NOT NULL";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
saveSetting('version','6150');
echo 'v6.5数据库更新成功！<br><br><a href="../index.php">>>返回网站首页</a>';
}else{
saveSetting('version','6150');
exit('你已更新过！<br/>'.$DB->error().'<br><a href="../index.php">>>返回网站首页</a>');
}
}
elseif($conf['version']=='6000')
{
$sql="ALTER TABLE `".DBQZ."_user`
ADD `daili` int(1) NOT NULL DEFAULT '0',
ADD `daili_rmb` VARCHAR(100) NOT NULL DEFAULT '0',
ADD `daili_qq` VARCHAR(20) NOT NULL DEFAULT '0'";
$sql2="ALTER TABLE `".DBQZ."_kms`
ADD `daili` int(11) NOT NULL DEFAULT '0'";
echo'<div class="box">';
if($DB->query($sql) && $DB->query($sql2)){
saveSetting('version','6140');
echo 'v6.4数据库更新成功！<br><br><a href="?">>>点此继续</a>';
}else{
saveSetting('version','6140');
exit('你已更新过！<br/>'.$DB->error().'<br><a href="?">>>点此继续</a>');
}
}
elseif($conf['version']<'6000')
{
echo '<div class="w h">V6.0更新程序</div>';
if($do=='1') {
$_SESSION['updatestep']=1;
$DB->query("update `".DBQZ."_config` `switch` ='0' where `id`='1';");
echo '<div class="box">';
echo'<pre>';
include("../readme.txt");
echo'</pre>';
echo "<hr><a href='{$_SERVER['PHP_SELF']}?do=2'>>>立即开始更新</a>";
} 

elseif($do=='2') {
if($_SESSION['updatestep']<=1){
$c=0;
$row=$DB->get_row("SELECT * FROM ".DBQZ."_config where id='1' limit 1");
$DB->query("DROP TABLE IF EXISTS ".DBQZ."_config");
$DB->query("create table `".DBQZ."_config` (
`k` varchar(32) NOT NULL,
`v` text NULL,
PRIMARY KEY  (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

foreach($row as $k => $v) {
if($k=='id')continue;
$sql="insert into ".DBQZ."_config (`k`,`v`) values ('$k','$v')";
$DB->query($sql);
$c++;
}
$_SESSION['updatestep']=2;
}
echo '<div class="box">数据表转换成功（一）<br/>SQL共执行'.$c.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=3">>>下一步</a>';
}

elseif($do=='3') {
if($_SESSION['updatestep']<=2){
$c=0;
$syskey = random(32);
saveSetting('syskey',$syskey);
$rs=$DB->query("SELECT * FROM ".DBQZ."_qq order by id asc");
while($row = $DB->fetch($rs))
{
$qq=$row['qq'];
$pw=authcode($row['pw'],'ENCODE',$syskey);
$sql="update ".DBQZ."_qq set pw='{$pw}' where qq='".$qq."'";
$DB->query($sql);
$c++;
}
$_SESSION['updatestep']=3;
}
echo '<div class="box">数据表转换成功（二）<br/>SQL共执行'.$c.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=4">>>下一步</a>';
}
elseif($do=='4') {
if($_SESSION['updatestep']<=3){
$c=0;
$d=0;
$a=file_get_contents("update3.sql");
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
$_SESSION['updatestep']=4;
}
echo '<div class="box">';
echo '数据表结构更新成功<br/>SQL成功'.$c.'句/失败'.$d.'句<br/><a href="'.$_SERVER['PHP_SELF'].'?do=5">>>下一步</a>';
}

elseif($do=='5') {
$_SESSION['updatestep']=5;
$u="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if(!file_exists("job.lock"))@file_put_contents("job.lock",'wone');
@file_get_contents("http://cron.aliapp.com/api/tongji3.php?url=$u");
saveSetting('version','6000');
echo '<div class="box"><font color="green">更新成功！当前版本 V6.0</font><br/><br/><a href="?">>>点此继续</a>';
}

}
}

echo'<hr>';
include(ROOT.'includes/foot.php');
echo'</div></body></html>';
?>