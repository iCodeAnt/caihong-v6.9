<?php
/*
*刀云论坛签到
*By 消失的彩虹海
*/
error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8"); 

$user=isset($_POST['user']) ? $_POST['user'] : $_GET['user'];
$pwd=isset($_POST['pwd']) ? $_POST['pwd'] : $_GET['pwd'];
$site='http://dyml.net/';

if($user && $pwd){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$log_url="{$site}user/login.shtml";//登陆url
$log_post="userwoname={$user}&password={$pwd}&refer=%2findex.shtml&postcode=";//登陆post

$s_url="{$site}usersign.shtml";//签到url
$s_post="postcode=&mypretime=0";//签到post

 
$cookie=tempnam('./','cookie');

//访问登录页面
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$log_url);
curl_setopt($ch,CURLOPT_REFERER,$log_url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$log_post);
curl_setopt($ch,CURLOPT_TIMEOUT,30);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie);
$html=curl_exec($ch);
$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);

if($httpcode<200 || $httpcode>=300)exit('对方网站无法访问！（HTTP错误码：'.$httpcode.'）');

if(strpos($html, '登录成功'))
{

//访问签到页面
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$s_url);
curl_setopt($ch,CURLOPT_REFERER,$s_url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$s_post);
curl_setopt($ch,CURLOPT_TIMEOUT,30);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
$str=curl_exec($ch);
curl_close($ch);

if(strpos($str, '签到成功'))
{
	preg_match('@签到成功!<br />(.*?)<br />正在自动转向@i' , $str , $matches);
	$resultStr = '签到成功!'.$matches[1];
}
elseif(strpos($str, '已经签到,明天继续哦'))
{
	preg_match('@<p class="red">(.*?)</p>@i' , $str , $matches);
	$resultStr = $matches[1];
}
}
else
{
$resultStr = '登录失败！请检查账号密码是否输入正确。';
}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?user='.$user.'&pwd='.$pwd;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="刀云自动签到任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/></form>
HTML;
}
else
echo $resultStr;

@unlink($cookie);
?>