<?php
if(!defined('IN_CRONLITE'))exit();

$my=isset($_GET['my'])?$_GET['my']:null;

$clientip=real_ip();
$password_hash='!@#%!s!';

if($my=='login') {
$gl=daddslashes($_GET['user']);
$pa=daddslashes($_GET['pass']);
$ctime=isset($_GET['ctime'])?intval($_GET['ctime']):'86400';
$cookie=isset($_GET['cookie'])?daddslashes($_GET['cookie']):'cookie';
$session=md5($gl.$pa.$password_hash);
$token=authcode("{$gl}\t{$session}", 'ENCODE', SYS_KEY);

if(isset($gl) && isset($pa)) {
$row = $DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='$gl' limit 1");
if($row['user']=='') {
@header('Content-Type: text/html; charset=UTF-8');
exit("<script language='javascript'>alert('此用户不存在');history.go(-1);</script>");
}
elseif ($pa != $row['pass']) {
@header('Content-Type: text/html; charset=UTF-8');
exit("<script language='javascript'>alert('用户名或密码不正确,请重新输入');history.go(-1);</script>");
}
elseif($row['user']==$gl && $row['pass']==$pa)
{
	setcookie("token", $token, time() + $ctime);
	header("Location:index.php?mod=index");
	exit;
}
}
else
{
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('用户名或密码不能为空!');history.go(-1);</script>");
}
}
elseif($my=='loginout')
{
	setcookie("token", "", time() - 2592000);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('退出成功!');window.location.href='./';</script>");
}
elseif(isset($_COOKIE["token"]))
{
	$token=authcode(daddslashes($_COOKIE['token']), 'DECODE', SYS_KEY);
	list($gl, $sid) = explode("\t", $token);
	$DB->query("UPDATE ".DBQZ."_user SET last='$date',dlip='$clientip' WHERE user = '$gl'");
	$row = $DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='$gl' limit 1");
	$session=md5($row['user'].$row['pass'].$password_hash);
	if($session==$sid) {
		$islogin=1;
		if(in_array($row['userid'], explode('|', $conf['adminid'])) || $row['userid']==1)$isadmin=1;
		//检测VIP
		if($row['vip']==1){
			if(strtotime($row['vipdate'])>time()){
				$isvip=1;
			}else{
				$isvip=0;
			}
		}elseif($row['vip']==2){
			$isvip=2;
		}else{
			$isvip=0;
		}
		//检测代理
		if($row['daili']==1){
			$isdaili=1;
		}else{
			$isdaili=0;
		}

		if(isset($_GET["user"]) && $isadmin==1)
		{
			$opuser=1;
			$gl=$_GET['user'];
			$row = $DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='$gl' limit 1");
			$link = '&user='.$gl;
		}
		else
		{
			$opuser=0;
			$link = '';
		}
	}
}

include(ROOT.'includes/content/banned.php');
?>