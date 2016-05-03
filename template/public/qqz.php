<?php
 /*
　*刷圈圈赞
*/ 
if(!defined('IN_CRONLITE'))exit();
@header('Content-Type: text/html; charset=UTF-8');
if($islogin==1){
if(in_array('10',$vip_func) && $isvip==0 && $isadmin==0) {
	exit('<script language=\'javascript\'>alert(\'抱歉，您还不是网站VIP会员，无法使用此功能。\');history.go(-1);</script>');
}
$qq=daddslashes($_GET['qq']);
if(!$qq) {
	exit('<script language=\'javascript\'>alert(\'参数不能为空！\');history.go(-1);</script>');
}
$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
if($row['lx']!=$gl && $isadmin==0) {
	exit('<script language=\'javascript\'>alert(\'你只能操作自己的QQ哦！\');history.go(-1);</script>');
}

if(!$_SESSION['qqz'] || $_SESSION['qqz']<time()){
	if($conf['qqz_api']) {
		$qqzurl=$conf['qqz_api'].$qq;
		$str=get_curl($qqzurl);
		$str=mb_convert_encoding($str, "UTF-8", "GB2312");
		if($str) {
			$next=time()+60*60*12;
			$_SESSION['qqz']=$next;
			exit('<script language=\'javascript\'>alert(\''.$str.'\');history.go(-1);</script>');
		} else {
			exit('<script language=\'javascript\'>alert(\'添加失败，接口关闭！\');history.go(-1);</script>');
		}
	} else {
		$qqzurl=$allapi.'api/qqz.php?qq='.$qq.'&authcode='.$authcode;
		$str=get_curl($qqzurl);
		if($str=='未授权')
			exit('<script language=\'javascript\'>alert(\'您的网站未授权，授权请联系QQ1277180438\');history.go(-1);</script>');
		elseif($str=='none')
			exit('<script language=\'javascript\'>alert(\'添加失败，接口关闭！\');history.go(-1);</script>');
		else{
			$next=time()+60*60*12;
			$_SESSION['qqz']=$next;
			exit('<script language=\'javascript\'>alert(\''.$str.'\');history.go(-1);</script>');
		}
	}
}else{
	exit('<script language=\'javascript\'>alert(\'今天已经添加过，请勿重复添加！\');history.go(-1);</script>');
}
}else{
exit('<script language=\'javascript\'>alert(\'登录失败，可能是密码错误或者身份失效了，请重新登录\');window.location.href=\'index.php?mod=login\';</script>');
}
?>