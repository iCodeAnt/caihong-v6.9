<?php
error_reporting(0);
function ifdx($uin, $touin, $sid)
{
	$url = "http://m.qzone.com/friendship/get_friendship?fromuin={$uin}&touin={$touin}&isReverse=1&res_type=4&refresh_type=1&format=json&sid={$sid}";
	$json = get_curl($url,0,'http://m.qzone.com/infocenter?g_ut=3&g_f=6676');
	$json = json_decode($json, true);//print_r($json);

	if ($json["code"] == 0) {

	if ($json["message"] == "请先登录") {
		exit('{"code":-1,"msg":"SID已过期!"}');
	}
	else if ($json["data"]["friendShip"][0]["add_friend_time"] == "-1") {
		exit('{"code":0,"is":0}');
	}
	else {
		exit('{"code":0,"is":1}');
	}
	}else{
		exit('{"code":-2,"msg":"'.$json["message"].'"}');
	}
}
function getGTK($skey){
	$len = strlen($skey);
	$hash = 5381;
	for($i = 0; $i < $len; $i++){
		$hash += ($hash << 5) + ord($skey[$i]);
	}
	return $hash & 0x7fffffff;//计算g_tk
}
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

@header("Content-Type: text/html; charset=UTF-8");
$uin = $_POST["uin"];
$sid = $_POST["sid"];
$touin = $_POST["touin"];
if(!$uin||!$sid||!$touin)exit;

ifdx($uin, $touin, $sid);
/*
$gtk=getGTK($skey);
$cookie='uin=o0'.$uin.'; skey='.$skey.';';
$ua='Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36';
$url='http://r.qzone.qq.com/cgi-bin/friendship/cgi_friendship?activeuin='.$touin.'&passiveuin='.$uin.'&situation=1&isCalendar=1&g_tk='.$gtk;

$data=get_curl($url,$post,0,$cookie,0,$ua);
preg_match('/callback\((.*?)\)\;/is',$data,$json);
$arr=json_decode($json[1],true);
if(@array_key_exists('code',$arr) && $arr['code']==0) {
	if($arr['data']['addFriendTime']==-1) exit('{"code":0,"is":0}');
	else exit('{"code":0,"is":1}');
} elseif($arr['code']==-3000) {
	exit('{"code":-1,"msg":"SKEY已过期!"}');
} else {
	exit('{"code":-2,"msg":"'.$arr["message"].'"}');
}*/
?>
