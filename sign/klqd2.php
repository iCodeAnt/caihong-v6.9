<?php
error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);
header("Content-type:text/html;charset=utf-8"); 
$content=isset($_POST['content']) ? $_POST['content'] : $_GET['content'];
$domain=isset($_POST['u']) ? $_POST['u'] : $_GET['u'];
$siteid=isset($_POST['siteid']) ? $_POST['siteid'] : $_GET['siteid'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
if($domain && $sid && $siteid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

$url="http://{$domain}/Signin/Signin.aspx?Action=index&Mod=Signin&siteid={$siteid}&sid={$sid}";
$post="content={$content}";
$ch=curl_init();
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
curl_setopt($ch,CURLOPT_TIMEOUT,30);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$str=curl_exec($ch);
curl_close($ch);

preg_match('@</div><div class="tip">(.+)</div><div class="content">您的签到次数@i' , $str , $matches);
$resultStr = $matches[1];


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?u='.$domain.'&siteid='.$siteid.'&sid='.$sid.'&content='.$content;
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

?>
