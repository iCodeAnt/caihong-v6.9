<?php
/*
 *QQ手机挂机模式API
*/
error_reporting(0);
header("Content-type:text/html;charset=utf-8");
$sysid=isset($_POST['sys']) ? $_POST['sys'] : 1;
$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$pwd=isset($_POST['pwd']) ? md5($_POST['pwd']) : $_GET['pwd'];
$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
$msg=isset($_POST['msg']) ? $_POST['msg'] : $_GET['msg'];
if($qq && $pwd){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

if($sysid==-1){echo"<font color='red'>该系统任务数量已满，请重新选择一个系统！</font><br/><a href='javascript:history.back();'><< 返回上一页</a>";exit;}

@set_time_limit(0);
@ignore_user_abort(true);
require 'mqq.class.php';


$mqq = new SET_MQQ($qq, $pwd);
$rand = $mqq->ChangeStat(10);
$return = json_decode($rand, true);
if ($return['status'] == 'ok') {
	$resultStr = 'QQ【'.$qq.'】已成功上线！';
	$mqq->GetMsg($msg);
} else {
	if($_POST['submit']=='确定')
		$rand = $mqq->Verifycode($_POST['verifycode']);
	else
		$rand = $mqq->Login();
	$return = json_decode($rand, true);
	if ($return['status'] == 'ok') {
		$resultStr = 'QQ【'.$qq.'】已成功上线！';
		if($method='robot' || $method='diy')$mqq->GetMsg($msg);
	} elseif ($return['status'] == 'vc') {
		$resultStr = 'QQ【'.$qq.'】执行时遇到验证码。';
	} elseif ($return['status'] == 'invaid password') {
		$resultStr = 'QQ【'.$qq.'】密码错误。【rand：'.$rand.'】';
	} else {
		$resultStr = 'QQ【'.$qq.'】执行时遇到未知参数。【rand：'.$rand.'】';
	}
}



if(isset($_GET['isadd']))
{
if($return['status'] != 'ok') {
	if($return['status'] == 'vc'){
		if(defined("SAE_ACCESSKEY"))
		{
		$filename = 'saeimg.php?p='.md5($return['res']).'.png';
		$fp = fopen('saemc://'.md5($return['res']).'.png', 'w');
		}else{
		$filename = md5($return['res']).'.png';
		$fp = fopen($filename, 'w');
		}
		fwrite($fp, $mqq->HexToText($return['res']));
		fclose($fp);
		echo '<form method="post" action="guaq.php?isadd=1">请输入QQ验证码(手机协议登录)<br/><img src="' . $filename . '"><br/><input type="text" name="verifycode" value=""><input type="hidden" name="sys" value="' . $sysid . '"><input type="hidden" name="qq" value="' . $qq . '"><input type="hidden" name="pwd" value="' . $_POST['pwd'] . '"><input type="hidden" name="msg" value="' . $msg . '"><input type="hidden" name="method" value="' . $method . '"><input type="submit" name="submit" value="确定"></form>';
	} else {
		echo '发生未知错误！请刷新。或返回检查密码。';
	}
} else {
$posturl='../index.php?mod=sc&my=add1&sys='.$sysid;
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?qq='.$qq.'&pwd='.$pwd.'&method='.$method.'&msg='.$msg;
echo '<h3>运行结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的挂机任务网址：<br/>
注：QQ密码已进行MD5加密，以更好地保证账号信息安全。<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="手机QQ挂机任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="1">
<input type="hidden" name="proxy" value="{$qq}">
<input type='submit' id='submit' value='添加到任务列表'/>
</form>
HTML;
	}
}
else
{
	if($return['status'] == 'vc') {
		if(preg_match('/index.php/',$_SERVER['HTTP_REFERER'])) {
			if(defined("SAE_ACCESSKEY"))
			{
			$filename = 'saeimg.php?p='.md5($return['res']).'.png';
			$fp = fopen('saemc://'.md5($return['res']).'.png', 'w');
			}else{
			$filename = md5($return['res']).'.png';
			$fp = fopen($filename, 'w');
			}
			fwrite($fp, $mqq->HexToText($return['res']));
			fclose($fp);
			echo '<form method="post" action="guaq.php">请输入QQ验证码(手机协议登录)<br/><img src="' . $filename . '"><br/><input type="text" name="verifycode" value=""><input type="hidden" name="qq" value="' . $qq . '"><input type="hidden" name="pwd" value="' . $_POST['pwd'] . '"><input type="hidden" name="msg" value="' . $msg . '"><input type="hidden" name="method" value="' . $method . '"><input type="submit" name="submit" value="确定"></form>';
		} else {
			//---------------ChangeStat_BUG-------------------
			$rand = $mqq->ChangeStat_BUG(10);
			$return = json_decode($rand, true);
			if ($return['status'] == 'ok') {
				echo 'QQ【'.$qq.'】已成功上线！';
				if($method='robot' || $method='diy')$mqq->GetMsg($msg);
			}
			//---------------ChangeStat_BUG-------------------
		}
	} else {
		echo $resultStr;
	}
}

?>