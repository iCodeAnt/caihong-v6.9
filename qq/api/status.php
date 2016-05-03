<?php
/*空间状态查询
Author:消失的彩虹海
*/
error_reporting(0);
function get_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if ($header) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if ($referer) {
		curl_setopt($ch, CURLOPT_REFERER, "http://m.qzone.com/infocenter?g_f=");
	}
	if ($ua) {
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	}
	else {
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
	}
	if ($nobaody) {
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}

header("Content-type: text/html; charset=utf-8"); 
$uin = $_GET["uin"];
$skey = $_GET["skey"];
if(!$uin||!$skey)exit;

$cookie='uin=o0'.$uin.'; skey='.$skey.';';
$url='http://kf.qq.com/cgi-bin/common?rand=0.4104280'.time().'&command=command%3DC00070%26input1%3DgetStatus%26input5%3D'.$uin.'%26input6%3D1%26fromuserid%3Dmarvinlin%26fromtoolid%3Dtouch0006%26fromtype%3Dtouch';

$data=get_curl($url,0,0,$cookie);
$arr=json_decode($data,true);
print_r($arr);exit;
if(@array_key_exists('code',$arr) && $arr['code']==0) {
	if($arr['data']['ret']==0) exit('<font color="green">成功</font>');
	else exit('<font color="red">失败</font>');
} elseif($arr['code']==-3000) {
	exit("<script language='javascript'>alert('SKEY已失效，请更新SKEY！');top.location.href='../../index.php?mod=qqlist'</script>");
} else {
	exit('<font color="red">失败</font>');
}
?>
