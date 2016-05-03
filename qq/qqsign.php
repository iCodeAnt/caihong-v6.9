<?php
//空间自动签到API

require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if(empty($method))$method=2;

if($qq && $sid && $skey){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}


require_once 'qzone.class.php';
$qzone=new qzone($qq,$sid,$skey);
	if($method==3){
		$qzone->qiandao(1);
	}else{
		$qzone->qiandao(0);
	}

//结果输出
foreach($qzone->msg as $result){
	echo $result.'<br/>';
}

//SID失效通知
if($qzone->sidzt){
	sendsiderr($qq,$sid,'sid');
}elseif($qzone->skeyzt){
	sendsiderr($qq,$sid,'skey');
}


?>