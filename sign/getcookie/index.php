<?php
/*
*Discuz论坛cookie提取工具
*BY 消失的彩虹海
*/
error_reporting(0);

$act = trim($_REQUEST['act']);

function curl_get($url, $use = false, $save = false, $referer = null, $post_data = null){
	global $cookie_file;
    $ch=curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3; en-us) AppleWebKit/999+ (KHTML, like Gecko) Safari/999.9');
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
	preg_match('/name="formhash" id="formhash" value=\'(.*?)\'/i', $res, $matches);
	if(isset($matches))
        return $matches[1];
	else
		exit('没有找到formhash');
}

function get_loginhash($res){
	preg_match('/loginhash=(.*?)&amp;/i', $res, $matches);
	if(isset($matches))
        return $matches[1];
	else
		exit('没有找到loginhash');
}

function get_seccodehash($res){
	preg_match('/name="seccodehash" type="hidden" value="(.*?)"/i', $res, $matches);
	if(isset($matches))
        return $matches[1];
	else
		exit('没有找到seccodehash');
}

function head(){
header("content-Type: text/html; charset=UTF-8");
echo '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<meta http-equiv="Cache-control" content="no-cache" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;  minimum-scale=1.0; maximum-scale=2.0"/>
<title>Discuz论坛cookie提取工具</title>
<link rel="stylesheet" type="text/css" href="../../style/getcookie.css">
</head>
<body>
';
}

function foot(){
echo '<div class="foot">';
if($_GET['u'])echo '<a href="?">>>点此重新获取cookie</a><br/>';
echo 'Powered by 彩虹!</div>';
echo '</body></html>';
}


if(!$_GET['u'])
{
head();
$cookie=substr(md5(time().rand()),8,16);
echo <<<HTML
<div class="title">Discuz论坛cookie提取工具</div>
<div class="content">论坛域名（无需输入“http://”）<br/>
<form action='?' method='GET'>
<input type="text" name="u" class="txt"/><br/>
<input type="hidden" name="cookie" value="{$cookie}"/>
<p><input type="submit" value='确定'/></p>
</form><a href="manual.php">>>手动导入cookie</a></div>
HTML;
foot();
exit;
}
else
{
	if(preg_match('!http://!i', $_GET['u']))
	{head();
	exit('填写不合法，网址中不要带有http://');
	}

//论坛地址
$baseUrl = 'http://'.$_GET['u'].'/';

//账号登录地址
$loginPageUrl = $baseUrl.'member.php?mod=logging&action=login&mobile=1';

//存放Cookies的文件
$cookie_file = 'cookie_'.urlencode($_GET['u']).'_'.$_GET['cookie'].'.txt';
}

if($act=='authcode')
{//获取验证码
	$imgurl=urldecode($_GET['url']);
	do
	{
		$ch=curl_init($imgurl);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3; en-us) AppleWebKit/999+ (KHTML, like Gecko) Safari/999.9');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
		{curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);$nodirect=1;}
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt($ch, CURLOPT_REFERER, $loginPageUrl);
    	$imgsrc = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if (($code == 301 || $code == 302 || $code == 303 || $code == 307) && !isset($nodirect))
    	{
			$ch=curl_init($imgurl);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3; en-us) AppleWebKit/999+ (KHTML, like Gecko) Safari/999.9');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
			curl_setopt($ch, CURLOPT_REFERER, $loginPageUrl);
    		$imgsrc = curl_exec($ch);
			curl_close($ch);
			preg_match('/Location:(.*?)\n/i', $imgsrc, $matches);
			$imgurl = trim($matches[1]);
		}
		else
		{
			$code = 0;
		}
	}
	while($code);
	header('Content-Type:image/png');
	echo $imgsrc;
	exit;
}


if($act=='login')
{
head();
$fastloginfield=$_POST["fastloginfield"];
$user=$_POST["username"];
$pwd=$_POST["password"];
$quest=$_POST["questionid"];
$answ=$_POST["answer"];
$seccodeverify=$_POST["seccodeverify"];

$formhash=$_POST["formhash"];
$loginhash=$_POST["loginhash"];
$seccodehash=$_POST["seccodehash"];

$loginSubmitUrl = $baseUrl.'member.php?mod=logging&action=login&loginsubmit=yes&loginhash='.$loginhash.'&mobile=yes';
//构建登录信息
$login_array=array(
					'fastloginfield'=>$fastloginfield,
					'username'=>$user,
					'password'=>$pwd,
					'referer'=>$baseUrl,
					'questionid'=>$quest,
					'answer'=>$answ,
					'formhash'=>$formhash,
					'seccodehash'=>$seccodehash,
					'seccodeverify'=>$seccodeverify,
					'cookietime'=>'2592000',
					);
//携带cookie提交登录信息
$res=curl_get($loginSubmitUrl ,true, true, $loginPageUrl, $login_array);
preg_match('!<div id="messagetext">(.*?)<p><a href=\"javascript!is',$res,$content);
if(strpos($res, '欢迎您回来'))
{
	//修改cookie的时间
	$fp = fopen($cookie_file,'r+');
	$buffer=fread($fp,4096);
	$buffer = str_replace('	14','	19',$buffer);
	fseek($fp,0);
	fwrite($fp,$buffer);
	fclose($fp);

	$cookie=urlencode($_GET['u']).'_'.$_GET['cookie'];
	preg_match('!欢迎您回来，(.*?)，现在将转入登录前页面!is',$res,$welcome);
	echo '<div class="content"><font color="green">'.$welcome[1].'，你的cookie已成功获取！</font><br/>注:cookie已延长有效期处理，只要不改密码保证10年以上不会失效。<hr/>
	以下是你的Cookie-ID：<br/><input type="text" value="'.$cookie.'" class="txt"/><br/>请将Cookie-ID填入签到机/任务助手的Cookie-ID一栏。<br/>说明：Cookie-ID相当于你cookie的特征码，不要随意泄露。此Cookie-ID还可以在本集群的其他Discuz工具中使用。</div>';
}
elseif(strpos($res, '登录失败'))
{
	echo $content[1].'<p><a href="?u='.$_GET['u'].'&cookie='.$_GET['cookie'].'">[ 点此返回上一页 ]</a></p>';
}
elseif(strpos($res, '验证码填写错误'))
{
	echo $content[1].'<p><a href="?u='.$_GET['u'].'&cookie='.$_GET['cookie'].'">[ 点此返回上一页 ]</a></p>';
}
else echo '获取失败!';
foot();
exit;
}




$res=curl_get($loginPageUrl, true, true);

//获取hash验证串
$formhash = get_formhash($res);
$loginhash = get_loginhash($res);


//获取安全提问
preg_match('!<select name=\"questionid\"(.*?)class=\"txt\" />!is', $res, $matches2);
$question=$matches2[0];

head();

if(strpos($res, 'seccodeverify'))
{
$seccodehash = get_seccodehash($res);
//获取验证码url
preg_match('!<img src=\"misc.php\?mod=seccode(.*?)\"!i', $res, $matches);
$imgurl=str_replace('&amp;','&',$matches[1]);
$imgurl=urlencode($baseUrl.'misc.php?mod=seccode'.$imgurl);

echo <<<HTML
<div class="title">登录{$_GET['u']}账号</div>
<div class="content">
<form action="?act=login&u={$_GET['u']}&cookie={$_GET['cookie']}" method="post">
<select name="fastloginfield"><option value="username">用户名</option><option value="email">Email</option></select><br/>
用户名:<br/>
<input type="text" name="username" class="txt"/><br/>
密码:<br/>
<input type="password" name="password" class="txt"/><br/>
{$question}
<br/><strong>验证码:</strong>(<a href="?u={$_GET['u']}&cookie={$_GET['cookie']}">刷新</a>)<br/>
<img src="?act=authcode&u={$_GET['u']}&cookie={$_GET['cookie']}&url={$imgurl}"><br/>
<input name="seccodeverify" type="text" autocomplete="off" class="txt"/><br/>

<input type="hidden" name="formhash" value="{$formhash}"/>
<input type="hidden" name="loginhash" value="{$loginhash}"/>
<input type="hidden" name="seccodehash" value="{$seccodehash}"/>
<p>
<input type="submit" name="submit" value='登录'/>
<input type="reset" value="重填" />
</p>
</form></div>
HTML;
}
else
{
echo <<<HTML
<div class="title">登录{$_GET['u']}账号</div>
<div class="content">
<form action="?act=login&u={$_GET['u']}&cookie={$_GET['cookie']}" method="post">
<select name="fastloginfield"><option value="username">用户名</option><option value="email">Email</option></select><br/>
用户名:<br/>
<input type="text" name="username" class="txt"/><br/>
密码:<br/>
<input type="password" name="password" class="txt"/><br/>
{$question}
<input type="hidden" name="formhash" value="{$formhash}"/>
<input type="hidden" name="loginhash" value="{$loginhash}"/>
<p>
<input type="submit" name="submit" value='登录'/>
<input type="reset" value="重填" />
</p>
</form></div>
HTML;
}
foot();
?>
