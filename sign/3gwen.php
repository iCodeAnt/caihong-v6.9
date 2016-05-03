<?php
//文网签到 BY 无道

@set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8");

$myid=isset($_POST['myid']) ? $_POST['myid'] : $_GET['myid'];
$txt=isset($_POST['txt']) ? $_POST['txt'] : $_GET['txt'];
if($myid && $txt){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

#开始签到
$s_url="http://wap.3gwen.com/hy/in.asp?cc=2&myid={$myid}";
$s_post="texts36={$txt}&bq=01";
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$s_url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$s_post);
curl_setopt($ch,CURLOPT_COOKIE,'wenwangvv=1;');
curl_setopt($ch,CURLOPT_TIMEOUT,60);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$content=curl_exec($ch);
curl_close($ch);

preg_match('@－－－－－<br/>(.*?)<br/>－－－－－<br/>@i' , $content , $matches);
$resultStr = $matches[1].'<br/>';

#开始抽奖
$s_url="http://3gwen.com/game/dzprun.asp?myid={$myid}";
$s_post="mobi=+&activity_id=544";
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$s_url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$s_post);
curl_setopt($ch,CURLOPT_COOKIE,'wenwangvv=1;');
curl_setopt($ch,CURLOPT_TIMEOUT,60);//超时
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$content=curl_exec($ch);
curl_close($ch);
$arr=json_decode($content,true);
if(array_key_exists('code',$arr) && $arr['code']==0){
	$resultStr .= '抽奖成功！获得奖励：'.$arr['data']['name'];
}elseif($arr['code']==12){
	$resultStr .= '今天抽奖机会已用完，请明天再来！';
}else{
	$resultStr .= '抽奖失败'.$content;
}

if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?myid='.$myid.'&txt='.$txt;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="文网自动签到任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else
echo $resultStr;
?>