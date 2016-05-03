<?php
if(!defined('IN_CRONLITE'))exit();
$title="试用卡获取平台";
$result = $DB->query("SELECT * FROM ".DBQZ."_kms WHERE kind='3' and isuse='0' LIMIT 30");
echo '<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">
		<meta name="MobileOptimized" content="320">
		<meta http-equiv="cleartype" content="on">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<title>
		试用卡获取平台-'.$conf['sitename'].'
		</title>
		<meta name="Keywords" content="试用卡获取平台,秒赞网,离线秒赞,QQ秒赞网,离线秒赞网,秒赞平台,自动秒赞网,离线秒赞平台,24小时离线秒赞">
		<meta name="Description" content="试用卡获取平台,秒赞网,离线秒赞,QQ秒赞网,离线秒赞网,秒赞平台,自动秒赞网,离线秒赞平台,24小时离线秒赞">
		<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="http://xinshi.aliapp.com/wailian/css/app.v2.css" rel="stylesheet" type="text/css">
		<style>
		body{background-position: center;}
		.container {
	max-width: 580px;
	padding: 10px;
    margin: 0 auto;
	border: 1px solid rgba(33,123,198,0.3);
}</style>
	</head>
	<body background="http://xinshi.aliapp.com/wailian/images/VIPshiyong.jpg">
		<div class="container">
			<div class="header">
				<div class="logo">
				</div>
				<div>
					<ul class="nav nav-pills pull-right" role="tablist">
						<li role="presentation" class="active">
							<a href="./index.php?mod=index">
								返回主页
							</a>
						</li>
					</ul>
				</div>
			</div>
			<hr />';
echo '<table class="table table-bordered">
<tbody align="center">
<tr>
<td>卡密</td>
<td>时长</td>
<td>是否使用</td>
<td>使用ID</td>
<td>使用时间</td>
<td>使用</td>
<!--<td>生成时间</td>-->
</tr>';
while($rows = $DB->fetch($result))
  {
if($rows['isuse']==1){
	$sfsy='<font color="#FF0000">已使用</font>';
}else{
	$sfsy='<font color="#0000C6">未使用</font>';
}
	  echo '<tr>
<td>'. $rows['km'] . '</td>
<td>'. $rows['value'] . '天</td>
<td>' . $sfsy . '</td>
<td>' . $rows['user'] . '</td>
<td>' . $rows['usetime'] . '</td>
<td><form action="index.php?mod=shop&kind=3" method="POST">
<input name="km" type="hidden" value="'. $rows['km'] . '">
<button type="submit" name="submit" class="btn btn-info">使用</button>
</form>
</td>
<!--<td>' . $rows['addtime'] . '</td>-->
</tr>';
	}

echo '</tbody></table><hr />
<div align="center">Copyright &copy; 2015.Company name All rights reserved.</div>
		</div>
	</body>
</html>';

?>