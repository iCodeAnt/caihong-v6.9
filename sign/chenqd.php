<?php
//CHEN挂机签到 by 年华

error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8"); 


$user=isset($_POST['user']) ? $_POST['user'] : $_GET['user'];
$pwd=isset($_POST['pwd']) ? $_POST['pwd'] : $_GET['pwd'];
$url=isset($_POST['url']) ? $_POST['url'] : $_GET['url'];
$txt=isset($_GET['txt']) ? $_GET['txt'] : "签个到～";

if($url && $user && $pwd){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$loginurl = "{$url}/login.php?my=login&gl={$user}&pass={$pwd}&cok=2592000";
$cookie = tempnam('./','cookie');

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $loginurl );
curl_setopt( $ch, CURLOPT_REFERER, $loginurl);
curl_setopt( $ch, CURLOPT_TIMEOUT, 30);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie);
$result = curl_exec($ch);
curl_close($ch);

if (strpos($result, '登录成功')) {
$qianurl = "{$url}/user/qian.php?txt={$txt}";
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $qianurl);
curl_setopt( $ch, CURLOPT_TIMEOUT, 30);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie);
$str=curl_exec($ch);
curl_close($ch);
preg_match("!</div><div class='box'>(.*?)</div><div class='box'><form action=\"qian.php\"!i" , $str , $matches);
$resultStr = $matches[1];
}
else
{
$resultStr = '登录失败！请检查账号密码是否输入正确。';
}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?url='.urlencode($url).'&user='.$user.'&pwd='.$pwd.'&txt='.$txt;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="CHEN自动签到任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else{
echo $resultStr;}

unlink($cookie);
?>