<?php
if(!defined('IN_CRONLITE'))exit();
$title="任务数据管理";
include_once(TEMPLATE_ROOT."head.php");

$sysid=isset($_GET['sys'])?$_GET['sys']:null;
echo '<div class="col-md-9" role="main">';

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

echo '<h3>任务数据管理</h3>';
echo'<div class="alert alert-info">系统共有'.$zongs.'条任务';
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

?>
<script>
function showresult(surl) {
  htmlobj=$.ajax({url:"template/default/display.php?list=8&url="+surl,async:false});
  $("#myDiv").html(htmlobj.responseText);
}
</script>
<div id="myDiv"></div>

<style>
.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space: pre-wrap;}
</style>
<div class="panel panel-default table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>任务名称/网址</th>
			<th>其他信息</th>
			<th>状态/操作</th>
		</tr>
	</thead>
	<tobdy>
<?php

$rs=$DB->query("select * from ".DBQZ."_job where{$sql} order by jobid desc limit $offset,$pagesize");
$i=0;
while ($myrow = $DB->fetch($rs))
{
$i++;
$pagesl=$i+($page-1)*$pagesize;
if($myrow['type']==1){
	$qq=$myrow['proxy'];
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	$myrow['url']=str_replace('[sid]',$row['sid'],$myrow['url']);
}
  echo '<tr><td style="width:40%;"><b>'.$pagesl.'.'.$myrow['mc'];
  if($sysid==null)echo '-系统'.$sysname[$myrow['sysid']];
if($myrow['type']==3){
	$qqjob=qqjob_decode($myrow['url']);
  echo '</b><br/>建立者:<a href="index.php?mod=admin-user&my=user&user='.$myrow['lx'].'">'.$myrow['lx'].'</a><br/>'.$qqjob['info'];
} else {
  echo '</b><br/>建立者:<a href="index.php?mod=admin-user&my=user&user='.$myrow['lx'].'">'.$myrow['lx'].'</a><br><a href="'.$myrow['url'].'" target="_blank">'.$myrow['url'].'</a><br>';
if($myrow['usep']==1)echo '{代理IP}';
if($myrow['post']==1)echo '{模拟POST}';
if($myrow['cookie']!='')echo '{模拟Cookie}';
if($myrow['referer']!='')echo '{模拟来源}';
if($myrow['useragent']!='')echo '{模拟浏览器}';
}
  echo '</td><td style="width:40%">创建时间:<font color="blue">'.$myrow['timea'].'</font><br>执行次数:<font color="red">'.$myrow['times'].'</font><br>上次执行:<font color="blue">'.dgmdate($myrow['timeb']).'</font><br/>运行时间:<font color="blue">';
echo $myrow['start'].'时 - '.$myrow['stop'].'时</font>';
if($myrow['pl']!=0)
echo '<br>运行频率:<font color="red">'.$myrow['pl'].'</font>秒/次';

echo '</td><td style="width:20%">';
if ($myrow['zt'] == '1'){
echo '暂停运行...<br/>';
}else{
echo '<font color="green">正在运行</font><br/>';}
if($myrow['type']==3)echo '<a href="index.php?mod=qqjob&jobid='.$myrow['jobid'].$link.'" class="btn btn-primary btn-sm">编辑任务</a><br/>';
elseif($myrow['type']!=1)echo '<a href="index.php?mod=sc&my=edit&jobid='.$myrow['jobid'].$link.'" class="btn btn-primary btn-sm">编辑任务</a><br/>';
if ($myrow['zt'] == '1') {
echo '<a href="index.php?mod=sc&my=kq&jobid='.$myrow['jobid'].$link.'" class="btn btn-success btn-sm">开启运行</a>';
}else{
echo '<a href="index.php?mod=sc&my=zt&jobid='.$myrow['jobid'].$link.'" class="btn btn-success btn-sm">暂停运行</a>';}
echo '<br/><a href="index.php?mod=sc&my=del&jobid='.$myrow['jobid'].$link.'" onclick="return confirm(\'你确实要删除此任务吗？\');" class="btn btn-danger btn-sm">删除任务</a></div></td></tr>';}

?>
	</tbody>
</table>
</div>

<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="index.php?mod=admin-job&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=admin-job&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=admin-job&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?mod=admin-job&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?mod=admin-job&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=admin-job&page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页

}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></div></body></html>';
?>