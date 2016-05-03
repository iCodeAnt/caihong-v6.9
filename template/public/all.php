<?php
//挂机统计文件
if(!defined('IN_CRONLITE'))exit();
$title="系统数据统计";
include_once(TEMPLATE_ROOT."head.php");

navi();

$all_sys1=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='1'");
$all_sys2=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='2'");
$all_sys3=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='3'");
$all_sys4=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='4'");
$all_sys5=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='5'");
$all_sys6=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='6'");
$all_sys7=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='7'");
$all_sys8=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='8'");

$info_sys1=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='1' limit 1");
$info_sys2=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='2' limit 1");
$info_sys3=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='3' limit 1");
$info_sys4=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='4' limit 1");
$info_sys5=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='5' limit 1");
$info_sys6=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='6' limit 1");
$info_sys7=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='7' limit 1");
$info_sys8=$DB->get_row("SELECT * from ".DBQZ."_info WHERE sysid='8' limit 1");
##数据获得

echo'<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">挂机统计</h3></div>';
echo'<div class="panel-body box">本站共有<font color=red>'.$users.'</font>位用户<br>';
echo'系统共有<font color=red>'.$zongs.'</font>条任务<br>';
for($i=1;$i<=$conf['sysnum'];$i++) {
	$all_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$i'");
	$info_sys=$DB->get_row("SELECT last from ".DBQZ."_info WHERE sysid='$i' limit 1");
	echo'[系统'.$sysname[$i].']有<font color=red>'.$all_sys.'</font>条任务<br>';
	echo'上次运行:'.$info_sys['last'].'<br>';
}

echo'<hr>系统累计运行了<font color=red>'.$info['times'].'</font>次.<br>';
echo'上次运行:'.$info['last'].'<br>';
echo'当前时间:'.$date.'</div></div>';

//注：只有Linux主机才支持显示负载。
if(function_exists("sys_getloadavg")){
echo'<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">系统负载:</h3></div>';
$f=sys_getloadavg();
echo'<div class="panel-body box">';
echo"1min:{$f[0]}";
echo"|5min:{$f[1]}";
echo"|15min:{$f[2]}";
echo'</div></div>';}

echo'<div class="panel panel-primary"><div class="panel-body box" style="text-align: center;">';
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo $txt[0];}
echo'</div>
</div>
</div></body></html>';
?>