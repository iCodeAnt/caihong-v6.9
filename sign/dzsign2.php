<?php
/*
*DiscuzX系列论坛签到
*在代码里填上你的用户名和密码~
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
function get_access($res){
	preg_match('/name="access" value="(.*?)"/i', $res, $matches);
	if(isset($matches))
        return $matches[1];
	else
		return false;
}


//心情：开心，难过，郁闷，无聊，怒，擦汗，奋斗，慵懒，衰
//{"kx","ng","ym","wl","nu","ch","fd","yl","shuai"};
$qdxq = 'kx';
$todaysay = '论坛签到天天好心情';//想说的话

$ssid = isset($_POST['id']) ? $_POST['id'] : urlencode($_GET['id']);
if($ssid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}
$str=explode("_",$ssid);

//论坛首页地址
$baseUrl = 'http://'.urldecode($str[0]);
//签到页面地址
$signPageUrl = $baseUrl.'/plugin.php?id=dsu_paulsign:sign';
//签到信息提交地址
$signSubmitUrl = $baseUrl.'/plugin.php?id=dsu_paulsign:sign&operation=qiandao&infloat=0&inajax=0';

//存放Cookies的文件
$cookie_file = './getcookie/cookie_'.$ssid.'.txt';

//访问签到页面
$res=curl_get($signPageUrl, true, true);

if(preg_match('!charset=gbk\"!i', $res) || preg_match('!charset=\"gbk\"!i', $res))$gbk=1;else $gbk=0;

if($gbk){$res=iconv('gbk', 'UTF-8//IGNORE', $res);}
//根据签到页面上的文字来判断今天是否已经签到
if(strpos($res, '您今天已经签到过了或者签到时间还未开始'))
{
	$resultStr = '今天已签过到';
}
else
{
	//获取formhash验证串
	$formhash = get_formhash($res);
	$access = get_access($res);
	//构造签到信息
	$post_data = array(
						'qdmode' => 1,
						'formhash' => $formhash,
						'qdxq' => $qdxq,
						'fastreply' => 0,
						'todaysay' => $todaysay,
						'access' => $access,
						);
	//提交签到信息
	$res = curl_get($signSubmitUrl ,true, true, $signPageUrl, $post_data);
	if($gbk){$res=iconv('gbk', 'UTF-8//IGNORE', $res);}
	if(strpos($res, '签到成功'))
	{
		$resultStr = '签到成功';
	}
	else
	{
		$resultStr = '签到失败';
	}
}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?id='.$ssid;
echo '<h3>签到结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="Discuz自动签到任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else
echo $resultStr;
?>