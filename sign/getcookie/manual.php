<?php echo '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';?>
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<meta http-equiv="Cache-control" content="no-cache" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;  minimum-scale=1.0; maximum-scale=2.0"/>
<title>手动导入Discuz论坛cookie</title>
<link rel="stylesheet" type="text/css" href="../../style/getcookie.css">
</head>
<body>
<div class="title">手动导入Discuz论坛cookie</div>
<?php
/*
*Discuz论坛cookie提取工具
*BY 消失的彩虹海
*/
error_reporting(0);

if(isset($_POST['data']) && isset($_POST['u']))
{
if(strpos('http://',$_POST['u']))exit('域名中不用包含“http://”！');
if(strlen($_POST['data'])<500)exit('请检查Cookie是否输入正确！');

//存放Cookies的文件
$cookie_file = 'cookie_'.urlencode($_POST['u']).'_'.$_POST['cookie'].'.txt';

//修改cookie的时间
$cookie_data = $_POST['data'];
$cookie_data = str_replace('	14','	19',$cookie_data);

file_put_contents($cookie_file,$cookie_data);

$cookie=urlencode($_POST['u']).'_'.$_POST['cookie'];
echo '
以下是你的Cookie-ID：<br/><input type="text" value="'.$cookie.'" class="txt"/><br/>请将Cookie-ID填入签到机/任务助手的Cookie-ID一栏。<br/>说明：Cookie-ID相当于你cookie的特征码，不要随意泄露。此Cookie-ID还可以在本集群的其他Discuz工具中使用。</div>';

}
else
{
$cookie=substr(md5(time().rand()),8,16);
echo <<<HTML
<div class="content">论坛域名（无需输入“http://”）<br/>
<form action='manual.php' method='POST'>
<input type="text" name="u" class="txt"/><br/>
COOKIE（Netscape HTTP Cookie File 格式）<br/>
<textarea name="data" rows="10" class="txt"></textarea>
<input type="hidden" name="cookie" value="{$cookie}"/>
<p><input type="submit" value='确定'/></p>
</form></div>
<div class="read"><p><b>手动获取Cookie教程：</b></p>
<p>1.首先安装Chrome或Chrome内核浏览器（下面以360极速浏览器为例）。</p>
<p>2.下载安装EditThisCookie这款插件[<a href="https://chrome.google.com/webstore/detail/edit-this-cookie/fngmhnnpilhplaeedifhccceomclgfbg?hl=zh-CN" target="_blank">在线安装地址</a>]，安装好后EditThisCookie的小图标会出现在浏览器菜单栏里。</p>
<p>3.进入EditThisCookie的设置界面，选择“选项”，在“选择cookie导出格式”下拉框中选择“Netscape HTTP Cookie File”。（如下图）<br/>
<img src="http://cyun.aliapp.com/sign/getcookie/getcookie1.jpg" alt="loading" style="border:1px solid #999;"/></p>
<p>4.关闭设置界面。打开论坛登录自己的账号，进入EditThisCookie，点击“导出cookie”的小图标（如下图），会提示“cookie已复制到剪贴板”。然后直接粘贴到上方编辑框内即可。<br/>
<img src="http://cyun.aliapp.com/sign/getcookie/getcookie2.jpg" alt="loading" style="border:1px solid #999;"/></p></div>
HTML;
}

echo '<div class="foot">';
if(isset($_POST['u']))echo '<a href="?">>>点此重新导入cookie</a><br/>';
echo 'Powered by 彩虹!</div>';
echo '</body></html>';
?>
