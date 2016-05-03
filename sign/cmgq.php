<?php
//草莓网签到 POWERED BY Ankh

error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8"); 


$user=isset($_POST['user']) ? $_POST['user'] : $_GET['user'];
$pwd=isset($_POST['pwd']) ? $_POST['pwd'] : $_GET['pwd'];
$txt=isset($_GET['txt']) ? $_GET['txt'] : "签个到～";
$site='http://caomei.vicp.co/09';

if($user && $pwd){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$log_url="{$site}/index.php?my=login&user={$user}&pass={$pwd}&date=1&css=1";//登陆url

 
$cookie=tempnam('./','cookie');

//访问登录页面
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$log_url);
curl_setopt($ch,CURLOPT_REFERER,$log_url);
curl_setopt($ch,CURLOPT_TIMEOUT,30);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie);
$html=curl_exec($ch);
curl_close($ch);

if(strpos($html, '登陆成功'))
{
$s_url="{$site}/qian.php?txt={$txt}";//签到url

//访问&签到
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$s_url);
curl_setopt($ch,CURLOPT_TIMEOUT,30);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
$str=curl_exec($ch);
curl_close($ch);

preg_match("/<div class= orgg style=''>(.*?)<\/div>/i" , $str , $matches);

$resultStr = $matches[1];


}
else
{
$resultStr = '登录失败！请检查账号密码是否输入正确。';
}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?user='.$user.'&pwd='.$pwd.'&txt='.$txt;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="草莓自动签到任务">
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