<?php
if(!defined('IN_CRONLITE'))exit();

/****注册限制设定****/

$timelimit = 86400; //时间周期(秒)
$iplimit = 3; //相同IP在1个时间周期内限制注册的个数
$verifyswich = 1; //验证码开关
if($isadmin==1)$verifyswich = 0;


if($islogin==1 && $isadmin!=1){
@header('Content-Type: text/html; charset=UTF-8');
exit("<script language='javascript'>alert('请不要重复注册！');history.go(-1);</script>");
}

if($conf['zc']==0 && $isadmin!=1){
@header('Content-Type: text/html; charset=UTF-8');
exit("<script language='javascript'>alert('已停止开放注册服务！');history.go(-1);</script>");
}

$gl=daddslashes(strip_tags($_POST['user']));
$pa=daddslashes(strip_tags($_POST['pass']));
$email=daddslashes(strip_tags($_POST['email']));
$verifycode=daddslashes(strip_tags($_POST['verify']));

if($_POST['my']=='reg'){
@header('Content-Type: text/html; charset=UTF-8');
if($gl=='' or $pa==''){
exit("<script language='javascript'>alert('帐号或密码不能为空！');history.go(-1);</script>");
}

/*if($_POST['pass']!==$_POST['pass2'])
{
exit("<script language='javascript'>alert('两次输入的密码不一致！');history.go(-1);</script>");
} */

if(preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$gl)){}else{
exit("<script language='javascript'>alert('注册失败！用户名只能为英文、数字与汉字！');history.go(-1);</script>");
}
if(preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$pa)){}else{
exit("<script language='javascript'>alert('注册失败！密码只能为英文、数字与汉字！');history.go(-1);</script>");
}

if(!preg_match('/^[A-z0-9._-]+@[A-z0-9._-]+\.[A-z0-9._-]+$/', $email)){
exit("<script language='javascript'>alert('邮箱格式不正确！');history.go(-1);</script>");
}

if($verifyswich==1 && $verifycode!=$_SESSION['verifycode']){
exit("<script language='javascript'>alert('验证码不正确！');history.go(-1);</script>");
}

$row2=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='$gl' limit 1");
if($row2['user']==''){}else{
exit("<script language='javascript'>alert('注册失败！此用户名已有用户使用');history.go(-1);</script>");
}

$row2=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE email='$email' limit 1");
if($row2['user']==''){}else{
exit("<script language='javascript'>alert('注册失败！此邮箱已有用户使用');history.go(-1);</script>");
}

if($conf['zc']==2 && $isadmin!=1)
{
$timelimits=date("Y-m-j H:i:s",TIMESTAMP+$timelimit);
$ipcount=$DB->count("SELECT count(*) FROM ".DBQZ."_user WHERE `date`<'$timelimits' and `zcip`='$clientip' limit ".$iplimit);
if($ipcount>=$iplimit)
{
exit("<script language='javascript'>alert('注册失败！请不要恶意刷注册！');history.go(-1);</script>");
}
}

$sql="insert into `".DBQZ."_user` (`pass`,`user`,`date`,`last`,`zcip`,`dlip`,`coin`,`email`) values ('".$pa."','".$gl."','".$date."','".$date."','".$clientip."','".$clientip."','".$rules[1]."','".$email."')";
$sds=$DB->query($sql);
if($sds){
unset($_SESSION['verifycode']);
if($isadmin!=1)
exit("<script language='javascript'>alert('注册成功！点击登录！');window.location.href='index.php?mod=user&my=login&user={$gl}&pass={$pa}';</script>");
else
exit("<script language='javascript'>alert('注册成功！');history.go(-1);</script>");
}else{
exit("<script language='javascript'>alert('注册失败！{$DB->error()}');history.go(-1);</script>");
}}

else{
include_once(TEMPLATE_ROOT."zhuce.php");
}



?>