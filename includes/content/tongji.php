<?php
//访问量统计文件
if(!defined('IN_CRONLITE'))exit();
error_reporting(0);
//
$times=
$conf['times'];
//echo'已经有<font color="#ff0000">'.$times.'</font>位友友逛过这里！<br/>';
//$DB->query("update ".DBQZ."_config set v=v+1 where k='times'");

$strtotime=strtotime($conf['build']);//获取开始统计的日期的时间戳
$now=time();//当前的时间戳
$yxts=ceil(($now-$strtotime)/86400);//取相差值然后除于24小时(86400秒)
echo'本站已安全运行<font color="#ff0000">'.$yxts.'</font>天<br/>';//输出

echo "您的IP: ".$clientip;
?>
