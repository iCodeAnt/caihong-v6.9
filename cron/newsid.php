<?php
//自动更新SID&Skey 监控文件

function curlget($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
	}
	if($ua){
		curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	}else{
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0');
	}
	if($nobaody){
		curl_setopt($ch, CURLOPT_NOBODY,1);
	}
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function checkvc($uin){
	$url='http://check.ptlogin2.qzone.com/check?pt_tea=1&uin='.$uin.'&appid=549000929&ptlang=2052&r=0.071823'.time();
	$data=curlget($url);
	if(preg_match('/ptui_checkVC'."\(".'\'(.*?)\''."\)".';/', $data, $arr)){
		$r=explode('\',\'',$arr[1]);
		if($r[0]==0){
			return array('0',$r[1],$r[3]);
		}else{
			return array('1');
		}
	}else{
		return array('2');
	}
}
function getsid($url, $do = 0)
{
	$do++;
	if ($ret = curlget($url)) {
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
function qqlogin($uin,$p,$vcode,$pt_verifysession){
	$v1=0;
	$url='http://ptlogin2.qzone.com/login?verifycode='.$vcode.'&u='.$uin.'&p='.$p.'&pt_randsalt=0&ptlang=2052&low_login_enable=0&u1=http%3A%2F%2Fm.qzone.com%2Finfocenter%3Fg_f%3D&from_ui=1&fp=loginerroralert&device=2&aid=549000929&pt_ttype=1&pt_3rd_aid=0&ptredirect=1&h=1&g=1&pt_uistyle=9&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&';
	$ret = curlget($url,0,0,0,1);
	if(preg_match("/ptuiCB\('(.*?)'\);/", $ret, $arr)){
		$r=str_replace("', '","','",$arr[1]);
		$r=explode('\',\'',$r);
		if($r[0]==0){
			preg_match('/skey=(@.{9});/',$ret,$skey);
			$array['uin']=$uin;
			$array['skey']=$skey[1];
			if($sid=getsid($r[2])){
				$array['sid']=$sid;
			}
			return $array;
		}elseif($r[0]==4){
			return 4;
		}elseif($r[0]==3){
			return 3;
		}elseif($r[0]==19){
			return 19;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}

include_once("../includes/cron.inc.php");

/*更新sid配置*/
$szie=3; //每次更新的QQ个数

$result=$DB->query("select * from `".DBQZ."_qq` where status='0' or status2='0' limit {$szie}");

while($row=$DB->fetch($result)){

$uin=$row['qq'];
$pwd=authcode($row['pw'],'DECODE',SYS_KEY);
$sql='';
$check=checkvc($uin);
if($check[0]==0){
	$vcode=$check[1];
	$p=curlget('http://qqapp.aliapp.com/?uin='.$uin.'&pwd='.strtoupper(md5($pwd)).'&vcode='.strtoupper($vcode));
	if($p=='error'||$p=='')exit($uin.' getp failed!<br/>');
	$arr=qqlogin($uin,$p,$vcode,$check[2]);
	if($arr==3){
		if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
		$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
//		$DB->query("DELETE FROM ".DBQZ."_qq WHERE qq='$uin'");
//		$DB->query("DELETE FROM ".DBQZ."_job WHERE proxy='$uin'");
		//发送邮件
		$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
		if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
		echo $uin.' Invaid Password!<br/>';
	}elseif(is_array($arr)){
		$sid=$arr['sid'];
		$skey=$arr['skey'];
		$DB->query("UPDATE ".DBQZ."_qq SET sid='$sid',skey='$skey',status='1',status2='1' WHERE qq='".$uin."'");
		echo $uin.' Update Success!<br/>';
	}else{
		if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
		$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
		echo $uin.' Update failed!<br/>';
		//发送邮件
		$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
		if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
	}
}else{
	if($row['status']==0)$DB->query("UPDATE ".DBQZ."_qq SET status='4' WHERE qq='".$row['qq']."'");
	$DB->query("UPDATE ".DBQZ."_qq SET status2='4' WHERE qq='".$row['qq']."'");
	echo $uin.' Need Code!<br/>';
	//发送邮件
	$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_user WHERE user='{$row['lx']}' limit 1");
	if(!empty($myrow['email']) && $myrow['mail_on']==1) send_mail_qqgx($myrow['email'],$uin);
}

}

echo 'OK!';
?>