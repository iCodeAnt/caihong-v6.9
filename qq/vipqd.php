<?php
/*
 *VIP签到
*/
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
if($qq && $sid && $skey){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

include '../includes/authcode.php';

$fromapi=curl_get('http://clouds.aliapp.com/api/vipqd.php?uin='.$qq.'&sid='.$sid.'&skey='.$skey.'&authcode='.$authcode);
echo $fromapi;

//SID失效通知
if($qzone->sidzt){
	sendsiderr($qq,$sid,'sid');
}elseif($qzone->skeyzt){
	sendsiderr($qq,$sid,'skey');
}
?>