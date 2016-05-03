<?php
header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);
@ignore_user_abort(true);
@set_time_limit(0);
include '../../includes/authcode.php';
$url=$_SERVER['HTTP_HOST'];
$uin=$_REQUEST['uin']?$_REQUEST['uin']:exit('{"code":-10,"msg":"No Uin！"}');
$skey=$_REQUEST['skey']?$_REQUEST['skey']:exit('{"code":-1,"msg":"No Skey！"}');
$gpid=$_REQUEST['gpid']?$_REQUEST['gpid']:0;
$touin=$_REQUEST['touin']?$_REQUEST['touin']:exit('{"code":-1,"msg":"No touin！"}');
echo get_curl("http://api.cccyun.cn/api/fenzu2.php","uin={$uin}&skey={$skey}&gpid={$gpid}&touin={$touin}&authcode={$authcode}&url={$url}");




function get_curl($url,$post=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}