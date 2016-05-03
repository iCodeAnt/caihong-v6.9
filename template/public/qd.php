<?php
/*
 *签到领VIP
 *Original:零欧喵喵
*/
if(!defined('IN_CRONLITE'))exit();
$jfjf = $conf['qd_jifen'] ;//这个是几积分换一天vip

if($jfjf==0)exit("<script> alert('本站未开启签到领VIP，请管理员到后台【VIP规则设定】中开启相关配置！');parent.location.href='index.php?mod=index';</script>");
if($islogin==1){
	$user = $row['user'];
	$uid = $row['userid'];
	//$sid = $row['sid'];
	$vipjf = $row['vipjf'];
	$vipsign = $row['vipsign'];
	$rmb = $row['coin'];
	$vipsigntime = $row['vipsigntime'];
	$vipend = $row['vipdate'];
	$vipsj = floor($vipjf/$jfjf);
	if($row['vip']==1 || $row['vip']==2){
		$vip = '<font color="#FF0000">是</font>';
	}else{
		$vip = '<font color="#0000C6">否</font>';
	}
	if($row['daili']==1){
		$daili = '<font color="#FF0000">是</font>';
	}else{
		$daili = '<font color="#0000C6">否</font>';
	}
	$time = time();
	$viptime = (strtotime($row['vipdate'])-strtotime(date("y-m-d",$time)))/3600/24;
	if($viptime < 0){
		$viptime = 0;
	}
	if($isvip==2)$viptime='永久';
	$datatime = date("y-m-d",$time);
	if(strtotime($datatime)==strtotime($vipsigntime)){
		$qdzt = '已签到';
	}else{
		$qdzt = '未签到';
	}
	if(@$_GET['qd']==1){
		$vipsign = $vipsign+1;
		$vipjf = $vipjf+1;
		$time = time();
		$datatime = date("y-m-d",$time);
		if(strtotime($datatime)==strtotime($vipsigntime)){
			echo"<script> alert('今天你已经签到了');history.go(-1); </script>";
		}else{
			$vipsign_sql="update ".DBQZ."_user set vipsign='".$vipsign."',vipjf='".$vipjf."',vipsigntime='".$datatime."' where userid='".$uid."'";
			$DB->query($vipsign_sql);
			$mark = $DB->affected();
			if($mark>0){
				echo"<script> alert('签到成功，你已签到 ".$vipsign." 天。请刷新查看最新信息！');window.location = 'index.php?mod=qd'; </script>";
			}else{
				echo"<script> alert('签到失败');history.go(-1); </script>";
			}
		}
	}
	if(isset($_POST['data'])){
		$xg_vipjf = $vipjf - @$_POST['data'] * $jfjf;
		if($_POST['data'] < 0){
			echo"<script> alert('兑换天数不能为负数！'); </script>";
		}elseif($row['vip']==2){
			echo"<script> alert('你已经是永久VIP，无法兑换！'); </script>";
		}else{
			if(floor($_POST['data']) < 1){
				echo"<script> alert('兑换天数不能为小数！'); </script>";
				}else{
					if($xg_vipjf < 0){
						echo"<script> alert('剩余积分不够！'); </script>";
					}else{
						if(empty($_POST['data'])){
							echo"<script> alert('兑换天数不能为空！'); </script>";
						}else{
							$xg_vipend = $row['vipdate'];
							$time = date("y-m-d",time());
							if(strtotime($row['vipdate']) < time()){
								$xg_vipend = date("y-m-d",time());
							}
							$xg_vipend = strtotime($xg_vipend) + 3600 * 24 * floor(@$_POST['data']);
							$xg_vipend = date('y-m-d',$xg_vipend);
							$viplq_sql="update ".DBQZ."_user set vipjf='".$xg_vipjf."',vipdate='".$xg_vipend."',vip='1' where userid='".$uid."'";
							$DB->query($viplq_sql);
							$mark = $DB->affected();
							if($mark>0){
								echo"<script> alert('兑换成功，请刷新查看最新信息！');window.location = 'index.php?mod=qd'; </script>";
							}else{
								echo"<script> alert('兑换失败！');history.go(-1); </script>";
							}
						}
					}
			}
		}
	}

echo '<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
		<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">
		<meta name="MobileOptimized" content="320">
		<meta http-equiv="cleartype" content="on">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<title>
			签到领VIP活动-'.$conf['sitename'].'
		</title>
		<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="http://xinshi.aliapp.com/wailian/css/app.v2.css" rel="stylesheet" type="text/css">
		<style>
		.container {
	max-width: 580px;
	padding: 10px;
    margin: 0 auto;
	border: 1px solid rgba(33,123,198,0.3);
}</style>
	</head>
	<body style="background:url(http://i1.tietuku.com/716da235536f4d9a.jpg);background-size:cover;background-position: center;">
		<div class="container">
			<div class="header">
				<div class="logo">
					<span>
					</span>
				</div>
				<div>
					<ul class="nav nav-pills pull-right" role="tablist">
						<li role="presentation" class="active">
							<a href="index.php?mod=index">
								返回主页
							</a>
						</li>
					</ul>
				</div>
			</div>
			<hr />';
echo '<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">
			当前的登陆信息
		</h3>
	</div>
	<table class="table table-bordered">
		<tbody align="center">
			<tr>
				<td>
					UID
				</td>
				<td>
					用户名
				</td>
				<td>
					是否VIP
				</td>
				<td>
					VIP剩余天数
				</td>
				<td>
					是否代理
				</td>
				<td>
					'.$conf['coin_name'].'
				</td>
			</tr>
			<tr>
				<td>
					'. @$uid . '
				</td>
				<td>
					'. @$user . '
				</td>
				<td>
					'. @$vip . '
				</td>
				<td>
					' . @$viptime . '
				</td>
				<td>
					' . @$daili . '
				</td>
				<td>
					' . @$rmb . '
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">
			签到信息
		</h3>
	</div>
	<table class="table table-bordered">
		<tbody align="center">
			<tr>
				<td colspan="2">
					签到状态
				</td>
				<td colspan="1">
					签到天数
				</td>
				<td colspan="1">
					签到积分
				</td>
				<td colspan="2">
					点击签到
				</td>
			</tr>
			<tr>
				<td colspan="2">
					' . @$qdzt . '
				</td>
				<td colspan="1">
					' . @$vipsign . '
				</td>
				<td colspan="1">
					' . @$vipjf . '
				</td>
				<td colspan="2">
					<a href="index.php?mod=qd&qd=1" class="btn btn-info">
						签到
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">
			领取VIP( ' . $jfjf . ' 积分兑换 1 天VIP)
		</h3>
	</div>
	<table class="table table-bordered">
		<tbody align="center">
			<tr>
				<td colspan="2">
					签到积分
				</td>
				<td colspan="1">
					可领天数
				</td>
				<td colspan="1">
					领取天数
				</td>
				<td colspan="2">
					点击领取
				</td>
			</tr>
			<tr>
				<td colspan="2">
					' . @$vipjf . '
				</td>
				<td colspan="1">
					' . @$vipsj . '
				</td>
				<form method="post" action="index.php?mod=qd">
					<td colspan="1">
						<input type="text" style="width:100px;" name="data" class="form-control"
						/>
					</td>
					<td colspan="2">
						<input type="submit" name="submit" value="兑换" class="btn btn-success"
						/>
					</td>
				</form>
			</tr>
		</tbody>
	</table>
</div>
';
echo '<hr />
<div align="center">签到领卡密©<a href="./">'.$conf['sitename'].'</a></div>
		</div>
	</body>
</html>';

}else
echo "<script>alert('请先登录！');parent.location.href='index.php?mod=index';</script>";
?>
