<?php
/*
*DiscuzX系列论坛挂在线时间
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


$ssid = isset($_POST['id']) ? $_POST['id'] : urlencode($_GET['id']);
if($ssid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}
$str=explode("_",$ssid);

//论坛首页地址
$baseUrl = 'http://'.urldecode($str[0]);
//个人资料地址
$applyUrl = $baseUrl.'/home.php?mod=space';


//存放Cookies的文件
$cookie_file = './getcookie/cookie_'.$ssid.'.txt';

curl_get($baseUrl ,true, false, null);

//访问个人资料页面
$res=curl_get($applyUrl, true, false, null);

if(preg_match('!charset=gbk\"!i', $res) || preg_match('!charset=\"gbk\"!i', $res))$gbk=1;else $gbk=0;

if($gbk){$res=iconv('gbk', 'UTF-8//IGNORE', $res);}


if(isset($_REQUEST['sys']))
{
$posturl='http://'.urldecode($_GET['backurl']).'/index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?id='.$ssid;
echo <<<HTML
<form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="Discuz挂积时任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type='submit' id='submit' value='添加到任务列表'/><hr/>
HTML;
echo '<h3>签到结果:</h3>';
echo $res;
}
else
echo $res;
?>