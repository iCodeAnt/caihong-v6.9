<?php
/*
*柯林签到
*By 无道&消失的彩虹海
*/
error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8"); 

$user=isset($_POST['user']) ? $_POST['user'] : $_GET['user'];
$pwd=isset($_POST['pwd']) ? $_POST['pwd'] : $_GET['pwd'];
$content=isset($_POST['txt']) ? $_POST['txt'] : $_GET['txt'];
$siteid=isset($_POST['siteid']) ? $_POST['siteid'] : $_GET['siteid'];
$ym=isset($_POST['ym']) ? $_POST['ym'] : $_GET['ym'];

if($user && $pwd && $content && $siteid && $ym){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$log_url="http://{$ym}/waplogin.aspx";//登陆url
$log_post="logname=$user&logpass=$pwd&saveid=0&action=login&classid=0&siteid={$siteid}&sid=-2-0-0-0-320&backurl=myfile.aspx?siteid={$siteid}";//登陆post

$s_url="http://{$ym}/Signin/Signin.aspx?Action=index&Mod=Signin&siteid={$siteid}";//签到url
$s_post="content={$content}";//签到post

 
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

if($httpcode<200 || $httpcode>=300){exit('对方网站无法访问！（HTTP错误码：'.$httpcode.'）<br/>1.请检查域名是否输入正确。<br/>2.对方网站正在维护。<br/>3.与对方网站连接受阻。<br/>4.对方网站可能开启了安全狗。');@unlink($cookie);}

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

if(strpos($str, '您的签到次数'))
{
	preg_match('@</div><div class="tip">(.+)</div><div class="content">您的签到次数@i' , $str , $matches);
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
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?ym='.$ym.'&siteid='.$siteid.'&user='.$user.'&pwd='.$pwd.'&txt='.$content;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="柯林自动签到任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else
echo $resultStr;

@unlink($cookie);
?>