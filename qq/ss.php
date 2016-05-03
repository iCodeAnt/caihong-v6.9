<?php
//自动发表图片说说API
require 'qq.inc.php';
require 'shuo.func.php';

$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];
$ua=isset($_POST['ua']) ? $_POST['ua'] : $_GET['ua'];
$nr=isset($_POST['nr']) ? $_POST['nr'] : $_GET['nr'];
$img=isset($_POST['img']) ? $_POST['img'] : $_GET['img'];
$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if(empty($method))$method=2;
if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}


if($nr=='语录' || $nr=='随机' || empty($nr))
$content=randstr();
elseif($nr=='笑话')
$content=jokei_txt();
elseif($nr=='时间')
$content=date("Y-m-d H:i:s");
elseif($nr=='表情')
$content="[em]e" . rand(100, 204) . "[/em]";
else $content=$nr;

if($img=='随机')
$imgurl = randimg();
elseif($img=='搞笑')
$imgurl = jokei_img();
else $imgurl=$img;


include_once "qzone.class.php";
	$qzone=new qzone($qq,$sid,$skey);
	if($method==3){
		$qzone->shuo(1,$content,$imgurl,$ua);
	}else{
		$qzone->shuo(0,$content,$imgurl,$ua);
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