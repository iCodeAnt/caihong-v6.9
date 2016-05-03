<?php
//QQ空间花藤挂机
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];

if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$jwa = curl_get('http://wap.flower.qzone.com/cgi-bin/get_profile_page?sid=' . $sid . '&B_UID=' . $qq);
$jw = htmlspecialchars($jwa, ENT_QUOTES);

if (preg_match('/浇花/', $jw)) {
$url='http://wap.flower.qzone.com/cgi-bin/plant_flower?act=rain&B_UID=' . $qq . '&sid=' . $sid . '&g_ut=2';
$html=curl_get($url);
}
if (preg_match("/修剪/", $jw)) {
$url='http://wap.flower.qzone.com/cgi-bin/plant_flower?act=love&B_UID=' . $qq . '&sid=' . $sid . '&g_ut=2';
$html=curl_get($url);
}
if (preg_match("/日照/", $jw)) {
$url='http://wap.flower.qzone.com/cgi-bin/plant_flower?act=sun&B_UID=' . $qq . '&sid=' . $sid . '&g_ut=2';
$html=curl_get($url);
}
if (preg_match("/施肥/", $jw)) {
$url='http://wap.flower.qzone.com/cgi-bin/plant_flower?act=nutri&B_UID=' . $qq . '&sid=' . $sid . '&g_ut=2';
$html=curl_get($url);
}
if (preg_match("/摘果/", $jw)) {
$url='http://wap.flower.qzone.com/cgi-bin/get_profile_page?sid=' . $sid . '&g_ut=2&func=1';
$html=curl_get($url);
}

require_once 'qzone.class.php';
$qzone=new qzone($qq,$sid);
$qzone->flower();

$resultStr='执行完毕。';

echo $resultStr;
?>