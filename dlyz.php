<?php
$mod='blank';
include("./includes/common.php");
@ini_set("short_open_tag", "on");
$uin=is_numeric($_GET['uin'])?$_GET['uin']:null;
if($uin){
	$result=$DB->query("select * from ".DBQZ."_user where daili_qq='$uin' and daili>0 limit 1");
	if($row = $DB->fetch($result)){
		$msg="<div class='alert alert-warning'>恭喜！该QQ({$uin})是本站代理！可以进行交易</div>";
	}else{
		$msg="<div class='alert alert-warning'>警告！该QQ({$uin})不是代理，请结束交易</div>";
	}
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<title>代理验证-<?=$conf['sitename']?></title>
<!--baidu-->
<meta name="baidu-site-verification" content="4IPJiuihDj"/>
<!-- Bootstrap -->
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
	body{
		margin: 0 auto;
		text-align: center;
	}
	.container {
	  max-width: 580px;
	  padding: 15px;
	  margin: 0 auto;
	}
</style>
<script type="text/javascript">
	  function getValue(obj,str){
	  var input=window.document.getElementById(obj);
	  input.value=str;
	  }
  </script>
</head>
<body>
<div class="container">
	<div class="header">
		<ul class="nav nav-pills pull-right" role="tablist">
			<li role="presentation" class="active"><a href="./">返回首页</a></li>
			<li role="presentation"><a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$conf['kfqq']?>&site=qq&menu=yes">购买代理
			</a></li>
		</ul>
		<h3 class="text-muted" align="left">代理验证</h3>
	</div>
	<hr><?=$msg?>
	﻿
	<form method="GET" action="?" class="form-sign">
		<div class="input-group">
			<span class="input-group-addon">平台名称</span><input type="text" class="form-control" value="<?=$conf['sitename']?>" disabled="ture">
		</div>
		<div class="input-group">
			<span class="input-group-addon">代理扣扣</span>
			<input type="text" class="form-control" name="uin" value="" placeholder="">
		</div>
		<br/>
		<input type="submit" class="btn btn-primary btn-block" value="提交验证">
	</form>
	<p style="text-align:center">
		<br>
		<a href="./"><span class="label label-info"><?=$conf['sitename']?></span></a>
		<a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$conf['kfqq']?>&site=qq&menu=yes"><span class="label label-info">联系客服</span></a>
	</p>
</div>
</body>
</html>