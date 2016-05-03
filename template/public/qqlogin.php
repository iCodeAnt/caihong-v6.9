<?php
//协助打码QQ登录获取SID
if(!defined('IN_CRONLITE'))exit();

/*
 *系统QQ状态码(SID&SKEY)说明：
 *status=0 失效
 *status=1 正常
 *status=4 待打码
 *status=5 无法打码
*/
$uin=empty($_POST['uin'])?exit('{"saveOK":-1,"msg":"uin不能为空"}'):daddslashes($_POST['uin']);
$vcode=empty($_POST['vcode'])?exit('{"saveOK":-1,"msg":"vcode不能为空"}'):strtoupper($_POST['vcode']);
$pt_verifysession=empty($_POST['pt_verifysession'])?exit('{"saveOK":-1,"msg":"pt_verifysession不能为空"}'):$_POST['pt_verifysession'];

$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$uin}' limit 1");
$pw=authcode($row['pw'],'DECODE',SYS_KEY);
$p=get_curl('http://qqapp.aliapp.com/?uin='.$uin.'&pwd='.strtoupper(md5($pw)).'&vcode='.strtoupper($vcode));
if($p=='error'||$p=='')exit('{"saveOK":-1,"msg":"p值获取失败"}');

if(strpos('s'.$vcode,'!')){
	$v1=0;
}else{
	$v1=1;
}
$url='http://ptlogin2.qzone.com/login?verifycode='.$vcode.'&u='.$uin.'&p='.$p.'&pt_randsalt=0&ptlang=2052&low_login_enable=0&u1=http%3A%2F%2Fm.qzone.com%2Finfocenter%3Fg_f%3D&from_ui=1&fp=loginerroralert&device=2&aid=549000929&pt_ttype=1&pt_3rd_aid=0&ptredirect=1&h=1&g=1&pt_uistyle=9&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&';
$ret = get_curl($url,0,1,0,1);
if(preg_match("/ptuiCB\('(.*?)'\);/", $ret, $arr)){
	$r=str_replace("', '","','",$arr[1]);
	$r=explode("','",$r);
	if($r[0]==0){
		preg_match('/skey=@(.{9});/',$ret,$skey);
		if($sid=getsid($r[2])){
			$DB->query("update `".DBQZ."_qq` set `sid` ='{$sid}',`skey` ='{$skey[1]}',`status` ='1',`status2` ='1' where `qq`='{$uin}'");
			if($conf['jifen']==1) {
				$DB->query("update ".DBQZ."_user set coin=coin+{$rules[5]} where user='".$gl."'");
				$DB->query("update ".DBQZ."_user set coin=coin-{$rules[6]} where user='".$row['lx']."'");
			}
			exit('{"saveOK":0,"uin":"'.$uin.'"}');
		}else{
			exit('{"saveOK":-3,"msg":"登录成功，获取SID失败！"}');
		}
	}elseif($r[0]==4){
		exit('{"saveOK":4,"uin":"'.$uin.'","msg":"验证码错误"}');
	}elseif($r[0]==3){
		$DB->query("UPDATE ".DBQZ."_qq SET status='5' WHERE qq='".$uin."'");
//		$DB->query("DELETE FROM ".DBQZ."_qq WHERE qq='$uin'");
//		$DB->query("DELETE FROM ".DBQZ."_job WHERE proxy='$uin'");
		exit('{"saveOK":3,"uin":"'.$uin.'","msg":"密码错误"}');
	}elseif($r[0]==19){
		$DB->query("UPDATE ".DBQZ."_qq SET status='5' WHERE qq='".$uin."'");
		exit('{"saveOK":19,"uin":"'.$uin.'","msg":"您的帐号暂时无法登录，请到 http://aq.qq.com/007 恢复正常使用"}');
	}else{
		$DB->query("UPDATE ".DBQZ."_qq SET status='5' WHERE qq='".$uin."'");
		exit('{"saveOK":-6,"msg":"'.str_replace('"','\'',$r[4]).'"}');
	}
}else{
	exit('{"saveOK":-2,"msg":"'.$ret.'"}');
}

function getsid($url, $do = 0)
{
	$do++;
	if ($ret = get_curl($url)) {
		$ret = preg_replace('/([\x80-\xff]*)/i','',$ret);
		if (preg_match('/\{"sid":"(.{24})"/iU', $ret, $sid)) {
			return $sid[1];
		} else {
			if ($do < 5) {
				return getsid($url, $do);
			} else {
				return;
			}
		}
	} else {
		return;
	}
}
?>