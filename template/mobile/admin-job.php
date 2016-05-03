<?php
if(!defined('IN_CRONLITE'))exit();
$title="任务数据管理";
include_once(TEMPLATE_ROOT."head.php");

$sysid=isset($_GET['sys'])?$_GET['sys']:null;

if($isadmin==1)
{

if(isset($_GET['kw'])) {
	$sql=" `url` LIKE '%{$_GET['kw']}%'";
	$link='&kw='.$_GET['kw'];
	if($sysid!=null) {
		$sql.=" and sysid='{$sysid}'";
		$link.='&sys='.$sysid;
	}
	$rownum='包含'.$_GET['kw'].'的共有';
}
elseif($sysid!=null) {
	$sql=" sysid='{$sysid}'";
	$link='&sys='.$sysid;
	$rownum='系统'.$sysname[$sysid].'下共有';
}
else {
	$sql=' 1';
	$link='';
}

echo '<div class="w h">任务数据管理</div>';
echo'<div class="row">系统共有'.$zongs.'条任务';
echo'<br>【<a href="index.php?mod=admin-job&sys=1">系统①</a>|<a href="index.php?mod=admin-job&sys=2">系统②</a>|<a href="index.php?mod=admin-job&sys=3">系统③</a>|<a href="index.php?mod=admin-job&sys=4">系统④</a>】<br>【<a href="index.php?mod=admin-job&sys=5">系统⑤</a>|<a href="index.php?mod=admin-job&sys=6">系统⑥</a>|<a href="index.php?mod=admin-job&sys=7">系统⑦</a>|<a href="index.php?mod=admin-job&sys=8">系统⑧</a>】<br>';


$numrows=$DB->count("select count(*) from ".DBQZ."_job where".$sql);
if(isset($rownum))echo $rownum.$numrows.'条任务';
if(isset($_GET['kw']))echo '<br>[<a href="index.php?mod=admin-clear&my=qlrw&kw='.urlencode($_GET['kw']).'">删除所有包含'.$_GET['kw'].'的任务</a>]';
echo '</div>';

$pagesize=$conf['pagesize'];
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);


$rs=$DB->query("select * from ".DBQZ."_job where{$sql} order by jobid desc limit $offset,$pagesize");
$i=0;
while ($myrow = $DB->fetch($rs))
{
$i++;
$pagesl=$i+($page-1)*$pagesize;
$iij = $i % 2;
if ($iij == 0) {
	echo '<div class="row">';
} else {
	echo '<div class="box">';
}
if($myrow['type']==1){
	$qq=$myrow['proxy'];
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	$myrow['url']=str_replace('[sid]',$row['sid'],$myrow['url']);
}
echo $pagesl.'.'.$myrow['mc'];
if($sysid==null)echo '-系统'.$sysname[$myrow['sysid']].'';
if($myrow['type']==3){
	$qqjob=qqjob_decode($myrow['url']);
  echo '<br>建立者:<a href="index.php?mod=admin-user&my=user&user='.$myrow['lx'].'">'.$myrow['lx'].'</a><br>'.$qqjob['info'];
} else {
echo '<br>建立者:<a href="index.php?mod=admin-user&my=user&user='.$myrow['lx'].'">'.$myrow['lx'].'</a><br>网址:<a href='.$myrow['url'].'>'.$myrow['url'].'</a>';
}
echo '<br>创建时间:'.$myrow['timea'].'<br>执行次数:'.$myrow['times'].'<br>上次执行:'.dgmdate($myrow['timeb']).'<br>运行时间:';
if ($myrow['zt'] == '1'){
echo'暂停运行...';
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
echo '|<a href="index.php?mod=sc&my=del&jobid='.$myrow['jobid'].$link.'">删除任务</a></div>';
}

echo'<div class="w">';
echo "共有".$pages."页(".$page."/".$pages.")<br>";
for ($i=1;$i<$page;$i++)
echo "<a href='index.php?mod=admin-job&page=".$i.$link."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='index.php?mod=admin-job&page=".$i.$link."'>[".$i ."]</a> ";
echo '<br>';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo "<a href='index.php?mod=admin-job&page=".$first.$link."'>首页</a>.";
echo "<a href='index.php?mod=admin-job&page=".$prev.$link."'>上一页</a>";
}
if ($page<$pages)
{
echo "<a href='index.php?mod=admin-job&page=".$next.$link."'>下一页</a>.";
echo "<a href='index.php?mod=admin-job&page=".$last.$link."'>尾页</a>";
}
echo'</div>';
##分页
}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="./">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></body></html>';
?>