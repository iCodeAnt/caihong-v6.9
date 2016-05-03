<?php
 /*
　*　用户中心文件
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="任务列表";
include_once(TEMPLATE_ROOT."head.php");

if($islogin==1){
if(isset($_GET['qq'])) {
$qq=daddslashes($_GET['qq']);
$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE proxy='{$qq}' and lx='{$gl}'");

echo '<div class="w h">★这里是QQ '.$qq.' 的任务列表★[<a href="index.php?mod=qqlist&'.$link.'">返回我的QQ列表</a>]</div><div class="box">★此QQ号码下总共有 '.$gls.' 个任务！<br/>';
echo '
<a href="index.php?mod=qqjob&my=default&qq='.$qq.$link.'">添加QQ挂机任务</a><br/>
<a role="menuitem" href="index.php?mod=set&my=qkqqrw&qq='.$qq.$link.'">清空所有</a></div>
';
$sql="proxy='".$qq."'";
}
elseif(isset($_GET['sys'])) {
$sysid=(int)$_GET['sys'];
if($sysid>$conf['sysnum'] || $sysid<=0)exit('参数错误！');

$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='{$gl}' and sysid='{$sysid}'");

$my_job=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='{$gl}'");//更新任务总数
if($row['num']==$my_job){}else{
$DB->query("UPDATE ".DBQZ."_user SET num= '$my_job' WHERE user = '$gl'");}

echo '<div class="w h">★系统'.$sysname[$sysid].'任务列表★[<a href="index.php?mod=user&'.$link.'">切换系统</a>]</div>';
echo '<div class="box">你总共建立了'.$gls.'个任务！<br/><a href="index.php?mod=sc&my=add&sys='.$sysid.$link.'">创建一个新任务</a>|<a href="index.php?mod=sc&my=bulk&sys='.$sysid.$link.'">批量添加任务</a><br/><a href="index.php?mod=signer&my=default&sys='.$sysid.$link.'">添加签到任务</a>|<a href="index.php?mod=qqlist&sys='.$sysid.$link.'">添加QQ挂机任务</a><br/><a href="index.php?mod=set&my=qk&sys='.$sysid.$link.'">清空所有</a>|<a href="index.php?mod=output&sys='.$sysid.$link.'">导出所有</a>|<a href="index.php?mod=sc&my=upload&sys='.$sysid.$link.'">文件导入</a></div>';

$sql="sysid='".$sysid."'";
}

$pagesize=$conf['pagesize'];
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

$i=0;
$rs=$DB->query("SELECT * FROM ".DBQZ."_job WHERE lx='{$gl}' and {$sql} order by jobid desc limit $pageu,$pagesize");
while($myrow = $DB->fetch($rs))
{
$i++;
$pagesl = $i + ($page - 1) * $pagesize;
$iij = $i % 2; 
if ($iij == 0) {
	echo '<div class="row">';
} else {
	echo '<div class="box">';
}
if($myrow['type']==1){
	$qq=$myrow['proxy'];
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['lx']==$gl)$myrow['url']=str_replace('[sid]',$row['sid'],$myrow['url']);
}
if($myrow['type']==3){
	$qqjob=qqjob_decode($myrow['url']);
  echo $pagesl.'.'.$myrow['mc'].'<br>'.$qqjob['info'];
} else {
  echo $pagesl.'.'.$myrow['mc'].'<br>网址:<a href="'.$myrow['url'].'" target="_blank">'.$myrow['url'].'</a>';
}
  echo '<br>创建时间:'.$myrow['timea'].'<br>执行次数:'.$myrow['times'].'<br>上次执行:'.dgmdate($myrow['timeb']).'<br>运行时间:';
if ($myrow['zt'] == '1'){
echo '暂停运行...';
}else{
echo $myrow['start'].'时 - '.$myrow['stop'].'时';
if($myrow['pl']!=0)
echo '<br>运行频率:'.$myrow['pl'].'秒/次';
echo '<br>已开启:';
}
if($myrow['usep']==1)echo '代理IP.';
if($myrow['post']==1)echo '模拟POST.';
if($myrow['cookie']!='')echo '模拟Cookie.';
if($myrow['referer']!='')echo '模拟来源.';
if($myrow['useragent']!='')echo '模拟浏览器.';
echo '<br>';
if($myrow['type']==3)echo '<a href="index.php?mod=qqjob&jobid='.$myrow['jobid'].$link.'">编辑任务</a>|';
elseif($myrow['type']!=1)echo '<a href="index.php?mod=sc&my=edit&jobid='.$myrow['jobid'].$link.'">编辑任务</a>|';
if ($myrow['zt'] == '1') {
echo '<a href="index.php?mod=sc&my=kq&jobid='.$myrow['jobid'].$link.'">开启运行</a>';
}else{
echo '<a href="index.php?mod=sc&my=zt&jobid='.$myrow['jobid'].$link.'">暂停运行</a>';}
echo '|<a href="index.php?mod=sc&my=del&jobid='.$myrow['jobid'].$link.'">删除任务</a></div>';}

echo'<div class="row">';
$s = ceil($gls / $pagesize);
echo "共有" . $s . "页(" . $page . "/" . $s . ")<br>";
$pagea = $page - 1;
$pageb = $page + 1;
if ($page != 1) { 
echo'<a href="index.php?mod=list&sys='.$sysid.'&page='.$pagea.$link.'">上一页</a> ';
}
if($page!=$s){
echo'<a href="index.php?mod=list&sys='.$sysid.'&page='.$pageb.$link.'">下一页</a>';
}
if($opuser==1)
	echo '<form action="index.php" method="get"><input type="hidden" name="mod" value="list"><input type="hidden" name="sys" value="'.$sysid.'"><input type="hidden" name="user" value="'.$gl.'"><input type="text" name="page" value="'.$page.'"><br><input type="submit" value="跳转"></form>'; 
else
	echo '<form action="index.php" method="get"><input type="hidden" name="mod" value="list"><input type="hidden" name="sys" value="'.$sysid.'"><input type="text" name="page" value="'.$page.'"><br><input type="submit" value="跳转"></form>'; 
echo'</div>'; 
#分页

echo '<div class="w h">运行日志:&nbsp&nbsp<a href="all.php">详细>></a></div><div class="box">系统共有'.$zongs.'条任务<br>系统累计运行了'.$info['times'].'次<br>上次运行:'.$info['last'].'<br>当前时间:'.$date.'</div>';
if(function_exists(sys_getloadavg)){$arr=sys_getloadavg();
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



echo'<div class="copy"><a href="index.php">返回首页</a>-<a href="index.php?mod=help">功能介绍</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</body></html>';
?>