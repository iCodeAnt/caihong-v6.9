<?php
if(!defined('IN_CRONLITE'))exit();
$title='自助购买';
include_once(TEMPLATE_ROOT."head.php");

if($islogin==1){
$kind=isset($_GET['kind'])?$_GET['kind']:1;

if($kind==1) {
	$sql=" kind='1'";
	$link='&kind='.$kind;
	$name='充值卡';
} elseif($kind==2) {
	$sql=" kind='2'";
	$link='&kind='.$kind;
	$name='VIP卡';
} elseif($kind==3) {
	$sql=" kind='3'";
	$link='&kind='.$kind;
	$name='试用卡';
}

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($kind==1) {
	echo '<ul class="nav nav-tabs">
	  <li class="active"><a href="#">充值</a></li>
	  <li><a href="index.php?mod=shop&kind=2">VIP</a></li>
	  <li><a href="index.php?mod=shop&kind=3">试用</a></li>
</ul>';
} elseif($kind==2) {
	echo '<ul class="nav nav-tabs">
	  <li><a href="index.php?mod=shop&kind=1">充值</a></li>
	  <li class="active"><a href="#">VIP</a></li>
	  <li><a href="index.php?mod=shop&kind=3">试用</a></li>
</ul>';
} elseif($kind==3) {
	echo '<ul class="nav nav-tabs">
	  <li><a href="index.php?mod=shop&kind=1">充值</a></li>
	  <li><a href="index.php?mod=shop&kind=2">VIP</a></li>
	  <li class="active"><a href="#">试用</a></li>
</ul>';
}

echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">自助购买</h3></div><div class="panel-body box">';

if(isset($_POST['km'])){
$km=daddslashes($_POST['km']);
$myrow=$DB->get_row("select * from ".DBQZ."_kms where km='$km' and{$sql} limit 1");
$kid=$myrow['id'];

if(!$myrow)
{
showmsg('此'.$name.'密不存在！',3);
exit;
}
if($myrow['isuse']==1){
showmsg('此'.$name.'密已被使用！',3);
exit;
}

if($kind==1) {
	$sql=$DB->query("update ".DBQZ."_user set coin=coin+{$myrow['value']} where user='".$gl."'");
	if($sql){
		$DB->query("update `".DBQZ."_kms` set `isuse` ='1',`user` ='$gl',`usetime` ='$date' where `id`='$kid'");
		showmsg('<font color="red">'.$myrow['value'].'</font> '.$conf['coin_name'].'充值成功！<br/>你当前拥有：<font color="red">'.($row['coin']+$myrow['value']).'</font> '.$conf['coin_name'].'',1);
	}else{
		showmsg('充值失败！',4);
	}
} elseif($kind==2) {
	if($myrow['value']==0) {
		$sql=$DB->query("update ".DBQZ."_user set vip='2' where user='".$gl."'");
		$myrow['value']='无限';
	} else {
		if($isvip==1) $vipdate = date("Y-m-d", strtotime("+ {$myrow['value']} months", strtotime($row['vipdate'])));
		else $vipdate = date("Y-m-d", strtotime("+ {$myrow['value']} months"));
		$sql=$DB->query("update ".DBQZ."_user set vip='1',vipdate='$vipdate' where user='".$gl."'");
	}
	if($sql){
		$DB->query("update `".DBQZ."_kms` set `isuse` ='1',`user` ='$gl',`usetime` ='$date' where `id`='$kid'");
		showmsg('VIP开通/续期成功！<br/>成功开通/续期 <font color="red">'.$myrow['value'].'</font> 个月VIP，你的VIP到期日期:'.$vipdate,1);
	}else{
		showmsg('VIP开通/续期失败！',4);
	}
} elseif($kind==3) {
	if($isvip==0) {
		if($DB->get_row("SELECT * FROM ".DBQZ."_kms WHERE kind='3' and user='$gl' LIMIT 1")){
			showmsg('VIP试用开通失败！您已使用过试用卡！',4);
			exit;
		}
		$vipdate = date("Y-m-d", strtotime("+ {$myrow['value']} days"));
		$sql=$DB->query("update ".DBQZ."_user set vip='1',vipdate='$vipdate' where user='".$gl."'");
		if($sql){
			$DB->query("update `".DBQZ."_kms` set `isuse` ='1',`user` ='$gl',`usetime` ='$date' where `id`='$kid'");
			showmsg('VIP试用开通成功！<br/>成功开通 <font color="red">'.$myrow['value'].'</font> 天VIP，你的VIP到期日期:'.$vipdate,1);
		}else{
			showmsg('VIP试用开通失败！',4);
		}
	}else{
		showmsg('你已是VIP，不能使用试用卡！',3);
	}
}


}
elseif(isset($_POST['value'])){
$value=daddslashes($_POST['value']);
if(!is_numeric($value) || $value<=0 || $value>12)
{
showmsg('月数只能为数字，不能超过12个月！',3);
exit;
}
if($isvip==2)
{
showmsg('你已经是永久VIP，不能兑换！',3);
exit;
}
$need=$value*$conf['coin_tovip'];
if($need>$row['coin'])
{
showmsg('兑换'.$value.'个月VIP需要'.$need.$conf['coin_name'].'，你只有'.$row['coin'].$conf['coin_name'].'！',3);
exit;
}
if($isvip==1) $vipdate = date("Y-m-d", strtotime("+ {$value} months", strtotime($row['vipdate'])));
else $vipdate = date("Y-m-d", strtotime("+ {$value} months"));
$sql=$DB->query("update ".DBQZ."_user set vip='1',vipdate='$vipdate' where user='".$gl."'");
if($sql){
	$DB->query("update ".DBQZ."_user set coin=coin-{$need} where user='".$gl."'");
	showmsg('VIP开通/续期成功！<br/>成功开通/续期 <font color="red">'.$value.'</font> 个月VIP，你的VIP到期日期:'.$vipdate,1);
}else{
	showmsg('VIP开通/续期失败！',4);
}
}
else
{
echo $conf['shop'];
echo '</div></div>
<div class="panel panel-primary">
<div class="panel-body box">';
if($kind==1) {
	echo '<li class="list-group-item"><b>你当前拥有：</b><font color="red">'.$row['coin'].'</font> '.$conf['coin_name'].'</li>';
} elseif($kind==2 || $kind==3) {
	if($isvip==1)$vipstatus='到期时间:<font color="green">'.$row['vipdate'].'</font>';
	elseif($isvip==2)$vipstatus='<font color="green">永久 VIP</font>';
	else $vipstatus='<font color="red">非 VIP</font>';
	echo '<li class="list-group-item"><b>VIP状态：</b>'.$vipstatus.'</li>';
}
echo '</div></div>
<div class="panel panel-primary">
<div class="panel-body box">';
echo '<form action="index.php?mod=shop&kind='.$kind.'" method="POST">
<div class="form-group">
<label>'.$name.'卡密：</label><br>
<input type="text" class="form-control" name="km" value="" autocomplete="off"></div>
<input type="submit" class="btn btn-success btn-block" value="确认使用"></form>';
}
if($conf['coin_tovip'] && $kind==2){
echo '</div></div>
<div class="panel panel-primary">
<div class="panel-body box">';
echo '<form action="index.php?mod=shop&kind='.$kind.'" method="POST">
<div class="form-group">
<label>VIP兑换：</label><br>
兑换价格：<font color="red">'.$conf['coin_tovip'].'</font> '.$conf['coin_name'].'＝1个月VIP会员<br>
<input type="text" class="form-control" name="value" value="" autocomplete="off" placeholder="要兑换的VIP月数"></div>
<input type="submit" class="btn btn-success btn-block" value="确认兑换"></form>';
}
echo '</div></div>';

}
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);
}

echo'<div class="copy"><a href="'.$siteurl.'index.php?mod=index">返回首页</a>-<a href="index.php?mod=user">用户中心</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
echo'</div></div></div></body></html>';
?>