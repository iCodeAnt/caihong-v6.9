<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="width=device-width; initial-scale=1.0;  minimum-scale=1.0; maximum-scale=2.0"/>
<link rel="stylesheet" type="text/css" href="../style/css1.css">
<link rel="shortcut icon" href="../images/favicon.ico">
<title>安装彩虹网络任务</title>
</head>
<body style="max-width:480px;">
<?php
error_reporting(0);
$do=isset($_GET['do'])?$_GET['do']:'0';

echo'<div class="w h">安装彩虹网络任务</div><div class="box">';

if(file_exists("job.lock")) {
 	echo"<pre>";
	include("../readme.txt");
	echo"</pre>";
	echo'<hr> ';
	echo'您已经安装过，如需重新安装请删除<font color=red> install/job.lock </font>文件后再安装!如果你不是管理员请自觉离开!';
} else {

if(!file_exists("../includes/authcode.php"))
	exit('此非完整安装包，仅供升级使用！要想获得完整安装包，只需将新版更新包解压后覆盖到旧版安装包即可。');
if($do=='0') {
echo'<pre>';
include("../readme.txt");
echo'</pre>';

function job_check($f) {
echo "{$f}";
if (function_exists($f))
{
echo ' <font color="green">支持</font><br/>';
} else {
echo ' <font color="red">不支持</font><br/>';
}
}
echo '<hr/>函数检测:';
job_check("curl_init");
job_check("fsockopen");
job_check("file_get_contents");
echo "<br>注意:以上函数如有一项不支持，都将无法使用本程序。<br><br>";
echo "<a href='{$_SERVER['PHP_SELF']}?do=2'>>>下一步</a>";
}

if($do=='1') {
echo <<<HTML
请选择要使用的数据库类型：<br><br>
【<a href="{$_SERVER['PHP_SELF']}?do=2">MySQL</a>】<br>
<font color="blue">功能全面，综合化，追求最大并发效率。</font><br><br>
【<a href="{$_SERVER['PHP_SELF']}?do=3&sqlite=1">SQLite</a>】<br>
<font color="blue">安装方便，小型化，追求最大磁盘效率。（不支持更新，不推荐！）</font><br><br>
HTML;
}

if($do=='2') {
if(ini_get('acl.app_id'))
echo <<<HTML
检测到您使用的是ACE空间，请在本地填写好config.php里的数据库相关配置，再用SVN软件上传。千万不能直接用爱特等在线文件管理器直接修改，因为ACE的本地文件读写都是临时性的。<br><br>
<font color="blue">数据库信息填写提示：<br>
进入ACE管理控制台－扩展服务－数据库(MySQL)，成功开通后就可以显示数据库所需配置信息。“外网地址”即为MYSQL主机，“账户名”即为MYSQL用户名，“数据库”即为数据库名，数据库密码填写开通MySQL服务时填写的密码（并非阿里云登录密码）。</font>
<br><br>
如果已填写好config.php数据库相关配置，请点击 <a href="{$_SERVER['PHP_SELF']}?do=4">下一步</a>
HTML;
elseif(defined("SAE_ACCESSKEY"))
echo <<<HTML
检测到您使用的是SAE空间，支持一键安装，请点击 <a href="{$_SERVER['PHP_SELF']}?do=4">下一步</a>
HTML;
else
echo <<<HTML
<form action="{$_SERVER['PHP_SELF']}?do=3" method='post'>
<br>数据库地址:<br>
<input type='text' name='host' value='localhost'/>
<br>数据库端口:<br>
<input type='text' name='port' value='3306'/>
<br>数据库用户名:<br>
<input type='text' name='user' value=''/>
<br>数据库密码:<br>
<input type='text' name='pwd' value=''/>
<br>数据库名:<br>
<input type='text' name='db' value=''/><br/>
<input type='submit' value='保存'/>
</form><br/>
（如果已事先填写好config.php相关数据库配置，请 <a href="{$_SERVER['PHP_SELF']}?do=4">点击此处</a> 跳过这一步！）
HTML;
}

if($do=='3') {
if(isset($_GET['sqlite']))
{
	$db_file = md5(time().rand());
	$config = "<?php\r\ndefine('SQLITE',true);\r\n\$db_file='{$db_file}';\r\n?>";
	$result1 = file_put_contents('../config.php',$config);
	$result2 = rename('../includes/sqlite/cron.db', '../includes/sqlite/'.$db_file.'.db');
	if($result1 && $result2)
		echo "保存成功!<br><a href='{$_SERVER['PHP_SELF']}?do=5'>下一步</a>";
	else
		echo "保存失败!";
}
else
{
	$host=isset($_POST['host'])?$_POST['host']:NULL;
	$port=isset($_POST['port'])?$_POST['port']:NULL;
	$user=isset($_POST['user'])?$_POST['user']:NULL;
	$pwd=isset($_POST['pwd'])?$_POST['pwd']:NULL;
	$db=isset($_POST['db'])?$_POST['db']:NULL;
	$db_qz='wjob';

	if($host==NULL or $user==NULL or $pwd==NULL or $db==NULL){
		echo '保存错误,请确保每项都不为空';
	} else {
		$config='<?php
/*数据库配置*/
$host = "'.$host.'"; //MYSQL主机
$port = '.$port.'; //MYSQL主机
$user = "'.$user.'"; //MYSQL用户
$pwd ="'.$pwd.'"; //MYSQL密码
$dbname = "'.$db.'"; //数据库名

/*系统配置*/
$db_qz = "'.$db_qz.'"; //数据表前缀
$cache = 0; //缓存方式(0为数据库1为文件)
?>';
		file_put_contents('../config.php',$config);
		echo "保存成功!<br><a href='{$_SERVER['PHP_SELF']}?do=4'>下一步</a>";
	}
}
}

if($do=='4') {
if(defined("SAE_ACCESSKEY"))include_once '../includes/sae.php';
else include_once '../config.php';
if(!isset($port))$port='3316';
if(!isset($db_qz))$db_qz='wjob';
if(!$user||!$pwd||!$dbname) {
echo "请先填写好数据库并保存后再安装";
} else {
$sql=file_get_contents("install.sql");
$sql=str_replace('{DBQZ}', $db_qz, $sql);
$sql=explode(';</explode>',$sql);
$cn = mysql_connect($host.':'.$port,$user,$pwd);
if (!$cn)
die('err:'.mysql_error());
mysql_select_db($dbname,$cn) or die('err:'.mysql_error());
mysql_query("set sql_mode = ''",$cn);
mysql_query("set names utf8",$cn);
$t=0;
$e=0;
$error='';
for($i=0;$i<count($sql);$i++) {
if ($sql[$i]!='') {
if(mysql_query($sql[$i],$cn)) {
++$t;
} else {
++$e;
$error.=mysql_error().'<br/>';
}
}
}
if($e==0) {
echo "安装成功<br/>SQL成功{$t}句/失败{$e}句 <a href='{$_SERVER['PHP_SELF']}?do=5'>下一步</a>";
} else {
echo "安装失败<br/>SQL成功{$t}句/失败{$e}句<br/>错误信息：{$error}<br/><a href='{$_SERVER['PHP_SELF']}?do=4'>点此进行重试</a>";
}
}
}

if($do=='5') {
echo <<<HTML
</div><div class="w h">网站信息配置</div><div class="box"><form action="{$_SERVER['PHP_SELF']}?do=6" method="POST">管理员账号(ID=1):<br><input type="text" name="user" value="" maxlength="32"><br>管理员密码:<br><input type="text" name="mm" value="" maxlength="32"><br>网站名称:<br><input type="text" name="sitename" value="彩虹网络任务"><br><input type="submit"
value="保存配置"></form>
HTML;
}

if($do=='6') {
$gl=isset($_POST['user'])?$_POST['user']:NULL;
$pa=isset($_POST['mm'])?$_POST['mm']:NULL;
$sitename=isset($_POST['sitename'])?$_POST['sitename']:NULL;

if($gl==NULL or $sitename==NULL or $pa==NULL){
echo '保存错误,请确保每项都不为空';
} else {
$u="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
@file_get_contents("http://cron.aliapp.com/api/tongji3.php?url=$u");
require_once "update.inc.php";
saveSetting('sitename',$sitename);
saveSetting('build',$date);
$syskey = random(32);
saveSetting('syskey',$syskey);
saveSetting('version','6150');
$ad=$DB->query("insert into `".DBQZ."_user` (`pass`,`user`,`date`,`last`,`coin`) values ('".$pa."','".$gl."','".$date."','".$date."','10000')");
if($ad){
@file_put_contents("job.lock",'安装锁');
echo '<font color="green">安装完成！</font><br/><br/><a href="../">>>网站首页</a><hr/>提示：更多设置选项请登录后台管理进行修改。默认开启4个系统，可以在后台设置中最高调至8个系统。<br/>（登录你的账号后在顶部菜单可以看到后台管理入口）<br/><br/><font color="blue">要想正常运行，请根据需要监控
http://你的域名/cron/job1.php～job8.php<br/>自动更新SKEY请监控 http://你的域名/cron/newsid.php</font><br/><br/><font color="#FF0033">如果你的空间不支持本地文件读写，请自行在install/ 目录建立 job.lock 文件！</font>';
}else{echo'保存失败!'.$DB->error();}
}
}

}
echo'<hr>';
include('../includes/foot.php');
echo'</div></body></html>';
?>