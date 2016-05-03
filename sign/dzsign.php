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

//签到代码
$url = isset($_POST['u']) ? $_POST['u'] : $_GET['u'];
$user = isset($_POST['user']) ? $_POST['user'] : $_GET['user'];//用户名
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_GET['pwd'];//密码
$quest = isset($_POST['quest']) ? $_POST['quest'] : $_GET['quest'];//密码提示问题
$answ = isset($_POST['answ']) ? $_POST['answ'] : $_GET['answ'];//密码提示问题答案
if($url && $user && $pwd){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

//心情：开心，难过，郁闷，无聊，怒，擦汗，奋斗，慵懒，衰
//{"kx","ng","ym","wl","nu","ch","fd","yl","shuai"};
$qdxq = 'kx';
$todaysay = '论坛签到天天好心情';//想说的话

//论坛首页地址
$baseUrl = preg_match("/^http:\/\//",$url)?$url:"http://".$url."/";
//账号登录地址
$loginPageUrl = $baseUrl.'member.php?mod=logging&action=login';
//账号信息提交地址
$loginSubmitUrl = $baseUrl.'member.php?mod=logging&action=login&loginsubmit=yes&loginhash=LNvu3';
//签到页面地址
$signPageUrl = $baseUrl.'plugin.php?id=dsu_paulsign:sign';
//签到信息提交地址
$signSubmitUrl = $baseUrl.'plugin.php?id=dsu_paulsign:sign&operation=qiandao&infloat=0&inajax=0';

//存放Cookies的文件
$cookie_file = tempnam('./','cookie');

//访问论坛登录页面，保存Cookies
$res=curl_get($loginPageUrl, false, true);

if(preg_match('!charset=gbk\"!i', $res) || preg_match('!charset=\"gbk\"!i', $res))$gbk=1;else $gbk=0;

//获取DiscuzX论坛的formhash验证串
$formhash = get_formhash($res);

//构建登录信息
$login_array=array(
					'username'=>$user,
                    'password'=>$pwd,
                    'referer'=>$baseUrl,
                    'questionid'=>$quest,
                    'answer'=>$answ,
					'formhash'=>$formhash,
					);

//携带cookie提交登录信息
$res=curl_get($loginSubmitUrl ,true, true, null, $login_array);
	//访问签到页面
	$res=curl_get($signPageUrl, true, true);
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
		$res = curl_get($signSubmitUrl ,true, true, null, $post_data);
		if($gbk){$res=iconv('gbk', 'UTF-8//IGNORE', $res);}
		if(strpos($res, '签到成功'))
		{
			$resultStr = '签到成功';
		}
		elseif(strpos($res, '需要先登录才能继续本操作'))
		{
			$resultStr = '登录失败！可能需要验证码';
		}
		else
		{
			$resultStr = '签到失败';
		}
	}


if(isset($_REQUEST['sys']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$_REQUEST['sys'];
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?u='.$url.'&user='.$user.'&pwd='.$pwd.'&quest='.$quest.'&answ='.$answ;
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

@unlink($cookie_file);//删除cookie文件
?>