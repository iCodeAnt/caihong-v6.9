<?php
/*删除好友
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
function getGTK($skey){
	$len = strlen($skey);
	$hash = 5381;
	for($i = 0; $i < $len; $i++){
		$hash += ($hash << 5) + ord($skey[$i]);
	}
	return $hash & 0x7fffffff;//计算g_tk
}

header("Content-type: text/html; charset=utf-8"); 
$uin = $_POST["uin"];
//$sid = $_POST["sid"];
$skey = $_POST["skey"];
$touin = $_POST["touin"];
if(!$uin||!$skey||!$touin)exit;

$gtk=getGTK($skey);
$cookie='uin=o0'.$uin.'; skey='.$skey.';';
$ua='Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36';
$url='http://w.qzone.qq.com/cgi-bin/tfriend/friend_delete_qqfriend.cgi?g_tk='.$gtk;
$post='uin='.$uin.'&fupdate=1&num=1&fuin='.$touin.'&qzreferrer=http://user.qzone.qq.com/'.$uin.'/myhome/friends';

$data=get_curl($url,$post,0,$cookie,0,$ua);
preg_match('/callback\((.*?)\)\;/is',$data,$json);
$arr=json_decode($json[1],true);
if(@array_key_exists('code',$arr) && $arr['code']==0) {
	if($arr['data']['ret']==0) exit('{"code":0}');
	else exit('{"code":-2,"msg":"'.$arr["message"].'"}');
} elseif($arr['code']==-3000) {
	exit('{"code":-1,"msg":"SKEY已过期!"}');
} else {
	exit('{"code":-2,"msg":"'.$arr["message"].'"}');
}
?>
