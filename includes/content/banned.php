<?php
//IP地址屏蔽

if(!defined('IN_CRONLITE'))exit();

if($conf['banned'] && preg_match('/('.$conf['banned'].')/',$clientip) && $isadmin!=1) {
echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>站点信息</title>
</head>
<body bgcolor="#FFFFFF">
<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" height="85%">
<tr align="center" valign="middle">
	<td>
	<table cellpadding="20" cellspacing="0" border="0" width="80%" align="center" style="font-family: Verdana, Tahoma; color: #666666; font-size: 16px">
	<tr>
	<td valign="middle" align="center" bgcolor="#EBEBEB">
		<b style="font-size: 20px">站点信息</b>
		<br /><br /><p style="text-align:left;">抱歉，您的 IP 地址不在允许范围内，无法访问本站点！</p>
		<br /><br />
	</td>
	</tr>
	</table>
	</td>
</tr>
</table>
</body>
</html>
HTML;
exit;
}

?>