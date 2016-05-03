<?php
header('Content-type:text/html;charset=utf-8');
$mc=$_POST['mc'];
$url=$_POST['url'];
$type=isset($_POST['type']) ? $_POST['type'] : 0;
$posturl='../index.php?mod=sc&my=add1&sys='.$_GET['sys'];
echo '添加任务跳转（兼容彩虹网络任务5.0）';
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$url}</font><br/>
<input type="hidden" name="mc" value="{$mc}">
<input type="hidden" name="url" value="{$url}">
<input type="hidden" name="type" value="2">
<input type="hidden" name="pl" value="18000">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
?>