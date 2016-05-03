<?php
if(!defined('IN_CRONLITE'))exit();

function qqjob_encode($func) {
switch($func)
{
case 'guaq':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'method'=>$_POST['method'],'msg'=>$_POST['msg']);
break;
case '3gqq':
	$str=array('func'=>$func,'qq'=>$_GET['qq']);
break;
case 'zan':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'forbid'=>$_POST['forbid'],'method'=>$_POST['method']);
break;
case 'pl':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'content'=>$_POST['content'],'forbid'=>$_POST['forbid'],'method'=>$_POST['method']);
break;
case 'qqsign':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'method'=>$_POST['method']);
break;
case 'qqss':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'nr'=>$_POST['nr'],'img'=>$_POST['img'],'ua'=>$_POST['ua'],'method'=>$_POST['method']);
break;
case 'del':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'method'=>$_POST['method']);
break;
case 'zfss':
	$str=array('func'=>$func,'qq'=>$_GET['qq'],'method'=>$_POST['method'],'uin'=>$_POST['uin'],'reason'=>$_POST['reason']);
break;
default:
	$str=array('func'=>$func,'qq'=>$_GET['qq']);
break;
}
return json_encode($str);
}

function qqjob_decode($string) {
global $DB,$siteurl,$qqapi_server;
$qqrow=json_decode($string,true);
$func=$qqrow['func'];
$qq=$qqrow['qq'];
$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");

$interapi=$siteurl;
//$interapi='http://cronsite.aliapp.com/';

$methodarr=array(0=>'系统默认',1=>'3G版协议',2=>'触屏版协议',3=>'PC版协议',4=>'PC版协议New','diy'=>'自定义回复语','robot'=>'智能机器人(茉莉API)','robot2'=>'智能机器人(星空API)','robot3'=>'智能机器人(小幽API)','no'=>'不自动回复');
$method=isset($qqrow['method'])?$qqrow['method']:0;
switch($func)
{
case 'guaq':
	$pwd=authcode($row['pw'],'DECODE',SYS_KEY);
	$url=$qqapi_server.'qq/guaq.php?qq='.$qq.'&pwd='.md5($pwd).'&method='.$qqrow['method'].'&msg='.$qqrow['msg'];
	$info='QQ：<u>'.$qq.'</u><br/>自动回复方式：<u>'.$methodarr[$method].'</u><br/>自定义回复语：<u>'.$qqrow['msg'].'</u>';
break;
case '3gqq':
	$url=$qqapi_server.'qq/3gqq.php?qq='.$qq.'&sid='.$row['sid'];
	$info='QQ：<u>'.$qq.'</u><br/>正在3GQQ挂机中。';
break;
case 'zan':
	if($row['status2']!=1 && $qqrow['method']>=3)$qqrow['method']=2;
	$url=$qqapi_server.'qq/zan.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'].'&forbid='.$qqrow['forbid'];
	$info='QQ：<u>'.$qq.'</u><br/>秒赞协议：<u>'.$methodarr[$method].'</u><br/>不秒赞以下QQ：<u>'.$qqrow['forbid'].'</u>';
break;
case 'pl':
	if($row['status2']!=1 && $qqrow['method']>=3)$qqrow['method']=2;
	$url=$qqapi_server.'qq/pl.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'].'&forbid='.$qqrow['forbid'].'&content='.$qqrow['content'];
	$info='QQ：<u>'.$qq.'</u><br/>秒评协议：<u>'.$methodarr[$method].'</u><br/>评论内容：<u>'.$qqrow['content'].'</u><br/>不秒评以下QQ：<u>'.$qqrow['forbid'].'</u>';
break;
case 'qqsign':
	if($row['status2']!=1 && $qqrow['method']>=3)$qqrow['method']=2;
	$url=$qqapi_server.'qq/qqsign.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'];
	$info='QQ：<u>'.$qq.'</u><br/>签到协议：<u>'.$methodarr[$method].'</u>';
break;
case 'qqss':
	if($row['status2']!=1 && $qqrow['method']>=3)$qqrow['method']=2;
	$url=$qqapi_server.'qq/ss.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'].'&nr='.urlencode($qqrow['nr']).'&img='.urlencode($qqrow['img']).'&ua='.urlencode($qqrow['ua']);
	$info='QQ：<u>'.$qq.'</u><br/>运行协议：<u>'.$methodarr[$method].'</u><br/>内容：<u>'.$qqrow['nr'].'</u><br/>图片地址：<u>'.$qqrow['img'].'</u><br/>浏览器UA：<u>'.$qqrow['ua'].'</u>';
break;
case 'ht':
	$url=$qqapi_server.'qq/ht.php?qq='.$qq.'&sid='.$row['sid'];
	$info='QQ：<u>'.$qq.'</u><br/>正在花藤挂机中。';
break;
case 'del':
	if($row['status2']!=1 && $qqrow['method']>=3)$qqrow['method']=2;
	$url=$qqapi_server.'qq/del.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'];
	$info='QQ：<u>'.$qq.'</u><br/>运行协议：<u>'.$methodarr[$method].'</u>';
break;
case 'zfss':
	$url=$qqapi_server.'qq/zfss.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'].'&method='.$qqrow['method'].'&uin='.$qqrow['uin'].'&nr='.$qqrow['reason'];
	$info='QQ：<u>'.$qq.'</u><br/>运行协议：<u>'.$methodarr[$method].'</u><br/>好友QQ：<u>'.$qqrow['uin'].'</u><br/>转发原因：<u>'.$qqrow['reason'].'</u>';
break;
case 'scqd':
	$url=$qqapi_server.'qq/scqd.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在书城签到中。';
break;
case 'lzqd':
	$url=$qqapi_server.'qq/lzqd.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在绿钻签到中。';
break;
case 'vipqd':
	$url=$qqapi_server.'qq/vipqd.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在VIP签到中。';
break;
case 'payqd':
	$url=$qqapi_server.'qq/payqd.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在钱包签到中。';
break;
case 'zyzan':
	$url=$interapi.'qq/zyzan.php?qq='.$qq;
	$info='QQ：<u>'.$qq.'</u><br/>正在互赞主页中。<br/>运行协议：PC版';
break;
case 'liuyan':
	$url=$interapi.'qq/liuyan.php?qq='.$qq;
	$info='QQ：<u>'.$qq.'</u><br/>正在互刷留言中。<br/>运行协议：触屏版';
break;
case 'gift':
	$url=$interapi.'qq/gift.php?qq='.$qq;
	$info='QQ：<u>'.$qq.'</u><br/>正在互送礼物中。<br/>运行协议：触屏版';
break;
case 'delll':
	$url=$qqapi_server.'qq/delll.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在删除留言中。<br/>运行协议：触屏版';
break;
case 'quantu':
	$url=$qqapi_server.'qq/quantu.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在圈说说图中。<br/>运行协议：PC版';
break;
case 'qqpet':
	$url=$qqapi_server.'qq/qqpet.php?qq='.$qq.'&sid='.$row['sid'].'&skey='.$row['skey'];
	$info='QQ：<u>'.$qq.'</u><br/>正在QQ宠物挂机中。';
break;
}
$info.='<br/>【<a data-toggle="modal" data-target="#showresult" href="#" id="showresult" onclick="showresult(\''.urlencode($url).'\')">手动执行测试</a>】';
if(defined('IN_CRONJOB') && $row['status']!=1)
	$url='no';
return array('url'=>$url,'info'=>$info);
}

?>