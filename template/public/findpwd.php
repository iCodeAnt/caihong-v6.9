<?php
if(!defined('IN_CRONLITE'))exit();
$title='找回密码';
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1 && $isadmin!=1){
exit("<script language='javascript'>alert('您已登录！');history.go(-1);</script>");
}

if(isset($_POST['email'])){
$email=daddslashes($_POST['email']);
$verifycode=daddslashes(strip_tags($_POST['verify']));

if(!preg_match('/^[A-z0-9._-]+@[A-z0-9._-]+\.[A-z0-9._-]+$/', $email)){
exit("<script language='javascript'>alert('邮箱格式不正确！');history.go(-1);</script>");
}
if(empty($verifycode) || $verifycode!=$_SESSION['verifycode']){
exit("<script language='javascript'>alert('验证码不正确！');history.go(-1);</script>");
}
$row=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE email='$email' limit 1");
if($row['user']==''){
exit("<script language='javascript'>alert('此邮箱不存在！');history.go(-1);</script>");
}
$code=base64_encode(authcode($row['user'].'||||'.time(),'ENCODE',SYS_KEY));
unset($_SESSION['verifycode']);
if(send_mail_findpwd($email, $row['user'], $code))
	exit("<script language='javascript'>alert('重置密码链接已经发送至{$email}！请到邮箱查看连接，重设密码！');history.go(-1);</script>");
else
	exit("<script language='javascript'>alert('邮件发送失败，请联系站长！');history.go(-1);</script>");

}elseif(isset($_GET['code'])){
$code=authcode(base64_decode($_GET['code']),'DECODE',SYS_KEY);
$arr=explode('||||',$code);
$user=$arr[0];
$timestamp=$arr[1];
if($timestamp+3600*24*2<time()){
exit("<script language='javascript'>alert('此链接已失效！');window.location.href='index.php?mod=findpwd';</script>");
}
$row=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='$user' limit 1");
if($row['user']==''){
exit("<script language='javascript'>alert('此用户不存在！');window.location.href='index.php?mod=findpwd';</script>");
}

if(isset($_POST['mm'])){
$mm=daddslashes($_POST['mm']);
$mm2=daddslashes($_POST['mm2']);
if($_GET['mm']!==$_GET['mm2'])
	exit("<script language='javascript'>alert('两次输入的密码不一致！');history.go(-1);</script>");
if($mm=='' or $mm2=='')
	exit("<script language='javascript'>alert('新密码不能为空！');history.go(-1);</script>");
if(!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$mm))
	exit("<script language='javascript'>alert('密码只能为英文、数字与汉字！');history.go(-1);</script>");

$sql18="update `".DBQZ."_user` set `pass` ='{$mm}' where `user`='$user'";
$sds=$DB->query($sql18);
if($sds){
showmsg('修改成功！请<a href="index.php?mod=login">重新登录</a>。',1);
}else{
showmsg('修改失败!<br/>'.$DB->error(),4);
}
exit;
}
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">设置一个新密码</h3></div><div class="panel-body box">';
echo '<form action="index.php?mod=findpwd&code='.$_GET['code'].'" method="POST">
<div class="form-group">
<label>请输入新密码:</label><br><input type="password" class="form-control" name="mm" value=""></div>
<div class="form-group">
<label>重新输入新密码:</label><br><input type="password" class="form-control" name="mm2" value=""></div>
<input type="submit" class="btn btn-success btn-block" value="修改密码"></form>';
echo'</div></div>';

}
else{
echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">找回密码</h3></div><div class="panel-body box">';
echo '<form action="index.php?mod=findpwd" method="POST">
<div class="form-group">
<label>绑定的邮箱:</label><br>
<input type="email" class="form-control" name="email" value=""></div>
<div class="form-group"><label>验证码: </label><img title="点击刷新" src="verifycode.php" onclick="this.src=\'verifycode.php?\'+Math.random();"><br>
<input type="text" class="form-control" name="verify" value="" autocomplete="off" required></div>
<font color="green">如果收不到邮件请到垃圾信箱查看。</font>
<input type="submit" class="btn btn-primary btn-block" value="找回密码"></form>';
echo'</div></div>';
}


echo'<div class="copy"><a href="'.$siteurl.'index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
echo'</div></div></div></body></html>';
?>