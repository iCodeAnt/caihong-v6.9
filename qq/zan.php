<?php
/*
 *QQ空间说说云点赞API
*/
require 'qq.inc.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if(empty($method))$method=2;
$forbid=isset($_POST['forbid']) ? $_POST['forbid'] : $_GET['forbid'];
if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}


//执行刷赞
$forbid=explode('|',$forbid);
require_once 'qzone.class.php';
$qzone=new qzone($qq,$sid,$skey);
if($method==1)
$qzone->is_zan();
elseif($method==2)
$qzone->like(0,$forbid);
elseif($method==3)
$qzone->like(1,$forbid);
elseif($method==4)
$qzone->like(2,$forbid);

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