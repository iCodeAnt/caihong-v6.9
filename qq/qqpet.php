<?php
/*
 *宠物挂机
*/
require 'qq.inc.php';
$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
$skey=isset($_POST['skey']) ? $_POST['skey'] : $_GET['skey'];

if($qq && $skey && $sid){
}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

include_once "qzone.class.php";
$qzone=new qzone($qq,$sid,$skey);
$cw=$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet',0,0,$qzone->cookie);

$p=explode("petid=",$cw);
$pet=explode("&",$p[1]);
$id=$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=160&cmd=3&feed=1&page=1',0,$qzone->cookie);
$id=htmlspecialchars($id);
$ida=explode('冰红茶',$id);
$ids=explode('goodid=',$ida[1]);
$id1=explode('&quot;',$ids[1]);
$id2=explode('&quot;',$ids[2]);
if(preg_match('/洗澡/',$cw)){
	$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=0&cmd=3&eatwash=0&feed=6&goodid='.$id2[0].'&gcount=1',0,$qzone->cookie);
}
if(preg_match('/喂食/',$cw)){
	$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=0&cmd=3&eatwash=0&feed=6&goodid='.$id1[0].'&gcount=1',0,$qzone->cookie);
}
if(preg_match('/看病/',$cw)){
	$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=0&cmd=3&feed=7',0,$qzone->cookie);
}
if($sx=file_get_contents('./cookie/pet'.$qq.'.txt')){} else $sx=0;
if($sx>=27){//如果完成了全部学科，开始打工。
	$u1='http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet';
	$qzone->get_curl($u1,'sid='.$sid.'&petid='.$pet[0].'&g_f=0&cmd=6&job=122&work=1&duration=2',0,$qzone->cookie);
}else{
	$u2='http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet';
	$cw=$qzone->get_curl($u2,'sid='.$sid.'&petid='.$pet[0].'&g_f=160&cmd=5&courseid='.$sx.'&study=2',0,$qzone->cookie);
if(preg_match('/完成了/',$cw)){
##自动进行学习科目提升
$sx=$sx+1;
$file=file_put_contents('./cookie/pet'.$qq.'.txt',$sx);
}
}
$cw=$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=160&cmd=30&gift=0',0,$qzone->cookie);##打开签到有礼
if(preg_match('/day=/',$cw)){
	$qzone->get_curl("http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet","sid=$sid&petid=$pet[0]&g_f=0&cmd=30&gift=2&gift_get=1",0,$qzone->cookie);
	$day=explode('day=',$cw);
	$day=explode('&',$day[1]);
	$qzone->get_curl('http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/phone_pet','sid='.$sid.'&petid='.$pet[0].'&g_f=160&cmd=30&day='.$day[0].'&gift=0&gift_get=1',0,$qzone->cookie);
}
$cw=$qzone->get_curl("http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/fish","sid=$sid&petid=$pet[0]&g_f=160&cmd=11",0,$qzone->cookie);
if(preg_match('/一键收获/',$cw)){
	$qzone->get_curl("http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/fish","sid=$sid&petid=$pet[0]&g_f=160&cmd=11&fish_sub=10&fryid=1",0,$qzone->cookie);
	$qzone->get_curl("http://qqpet.wapsns.3g.qq.com/qqpet/fcgi-bin/fish","sid=$sid&petid=$pet[0]&g_f=160&cmd=11&fish_sub=12&fryid=8&prc=16&sname=tPPR29Pj",0,$qzone->cookie);##8级大眼鱼
}

echo $qq." 宠物正在挂机中.";
?>