<?php
 /*
　*　用户中心文件
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="任务管理";
include_once(TEMPLATE_ROOT."head.php");


if($islogin==1){

$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='$gl'");
if($row['num']==$gls){}else{
$DB->query("UPDATE ".DBQZ."_user SET num= '$gls' WHERE user = '$gl'");}

navi();

echo'<div class="w h">网址监控任务控制面板</div>';
echo'<div class="row">请从以下'.$conf['sysnum'].'个系统中选择一个总任务数较少的来添加你的网址监控任务。<br/>本系统有以下功能：自定义运行时间,自定义使用代理,自定义代理ip及端口号,自定义POST模拟,自定义POST数据,自定义COOKIE,自定义来源地址,自定义模拟浏览器。</div>';
echo'<div class="box">你总共建立了'.$gls.'条任务<hr/>';

$show=explode('|',$conf['show']);

for($i=1;$i<=$conf['sysnum'];$i++) {
	$addstr='';
	$all_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$i'");
	$my_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$i' and lx='$gl'");
	if(in_array($i,$vip_sys))$addstr='<font color="red">[VIP]</font>';
	echo'【系统'.$sysname[$i].'】'.$addstr.'<a href="index.php?mod=list&sys='.$i.$link.'">>>点击进入</a><br/>系统'.$sysname[$i].'总任务数：'.$all_sys.'/'.$conf['max'].'<br/>你的任务数：'.$my_sys.'<br/>执行频率：<font color=#0000FF>'.$show[($i-1)].'</font><hr/>';
}
echo'</div>';
echo '<div class="w h">运行日志:&nbsp&nbsp<a href="index.php?mod=all">详细>></a></div><div class="box">系统共有'.$zongs.'条任务<br>系统累计运行了'.$info['times'].'次<br>上次运行:'.$info['last'].'<br>当前时间:'.$date.'</div>';
if(function_exists("sys_getloadavg")){
$arr=sys_getloadavg();
echo '<div class="w h">系统负载:</div>';
$f=sys_getloadavg();
echo'<div class="box">';
echo"1min：{$f[0]}";
echo"|5min：{$f[1]}";
echo"|15min：{$f[2]}";
echo'</div>';
	}
  }
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);}

echo'<div class="copy"><a href="index.php">返回首页</a>-<a href="index.php?mod=help">功能介绍</a>-<a href="index.php?my=loginout">退出</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</body></html>';
?>