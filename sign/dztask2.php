<?php
/*
*DiscuzX系列论坛任务系统
*By Perfare
*update 2014-3-23
*/
set_time_limit(0);
ignore_user_abort(true);
header("content-Type: text/html; charset=utf-8");

function curl_get($url, $use = false, $save = false, $referer = null, $post_data = null){
	global $cookie_file;
    $ch=curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//需要使用cookies
	if($use){
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    }
	//需要保存cookies
	if($save){
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    }
	//需要referer伪装
	if(isset($referer))
		curl_setopt($ch, CURLOPT_REFERER, $referer);
	//需要post数据
	if(is_array($post_data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

function get_formhash($res){
	preg_match('/name="formhash" value="(.*?)"/i', $res, $matches);
	if(isset($matches))
        return $matches[1];
	else
		exit('没有找到formhash');
}


$task = isset($_POST['task']) ? $_POST['task'] : $_GET['task'];//任务ID

$ssid = isset($_POST['id']) ? $_POST['id'] : urlencode($_GET['id']);
if($ssid && $task){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}
$str=explode("_",$ssid);

//论坛首页地址
$baseUrl = 'http://'.urldecode($str[0]);
//任务申请地址
$applyUrl = $baseUrl.'/home.php?mod=task&do=apply&id='.$task;
//任务完成地址
$drawUrl = $baseUrl.'/home.php?mod=task&do=draw&id='.$task;
//系统提醒页面
$noticeUrl = $baseUrl.'/home.php?mod=space&do=notice&view=system';

//存放Cookies的文件
$cookie_file = './getcookie/cookie_'.$ssid.'.txt';


//访问申请任务页面
$res=curl_get($applyUrl, true, false, null);

if(preg_match('!charset=gbk\"!i', $res) || preg_match('!charset=\"gbk\"!i', $res))$gbk=1;else $gbk=0;

if($gbk){$res=iconv('gbk', 'UTF-8//IGNORE', $res);}


//访问完成任务页面
$res2 = curl_get($drawUrl ,true, false, null);
if($gbk){$res2=iconv('gbk', 'UTF-8//IGNORE', $res2);}


if(strpos($res2, '任务已成功完成'))
{
	$resultStr = '任务已成功完成';
	//访问系统提醒页面
	curl_get($noticeUrl ,true, false, null);
}
else
{
	$resultStr = '任务失败';
}
if(strpos($res, '您已申请过此任务'))
{
	$resultStr = '本期您已申请过此任务';
}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?id='.$ssid.'&task='.$task;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="Discuz任务助手任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else
echo $resultStr;
?>