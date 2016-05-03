<?php
 /*
　*　用户中心文件
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="任务列表";
include_once(TEMPLATE_ROOT."head.php");

if($islogin==1){
echo '<div class="col-md-9" role="main">';

if(isset($_GET['qq'])) {
$qq=daddslashes($_GET['qq']);
$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE proxy='{$qq}' and lx='{$gl}'");
$qqrow=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
if($qqrow['lx']!=$gl && $isadmin!=1)
{showmsg('你只能操作自己的QQ哦！',3);
exit;
}
echo '<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li class="active">'.$qq.'</li>
</ol>';
echo '<div class="alert alert-info">★这里是QQ '.$qq.' 的任务列表★[<a href="index.php?mod=qqlist&'.$link.'">返回我的QQ列表</a>]<br/>★此QQ号码下总共有 '.$gls.' 个任务！<br/><a href="http://m.qzone.com/infocenter?sid='.$qqrow['sid'].'&g_ut=3&g_f=6676" target="_blank" style="color:grey">进入我的QQ空间</a>.<a href="search.php?q='.$qq.'" target="_blank" style="color:red">秒赞认证</a></div>';
echo '
<span class="dropdown">
   <button href="#" class="btn btn-primary" data-toggle="dropdown" role="button">添加QQ挂机任务 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#qqjob01" href="#" id="qqlist01">挂Ｑ类任务</a></li>
	<li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#qqjob02" href="#" id="qqlist02">空间类任务</a></li>
	<li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#qqjob03" href="#" id="qqlist03">互刷类任务</a></li>
	<li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#qqjob04" href="#" id="qqlist04">签到类任务</a></li>
   </ul>
</span>
<span class="dropdown">
   <button href="#" class="btn btn-success" data-toggle="dropdown" role="button">QQ小工具 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
   <li role="presentation"><a role="menuitem" href="index.php?mod=qqz&qq='.$qq.'">刷圈圈赞99+</a></li>
   <li role="presentation"><a role="menuitem" href="index.php?mod=rq&qq='.$qq.'" target="_blank">空间刷人气</a></li>
    <li role="presentation"><a role="menuitem" href="index.php?mod=dx&qq='.$qq.'" target="_blank">单向好友检测</a></li>
	<li role="presentation"><a role="menuitem" href="index.php?mod=mzjc&qq='.$qq.'" target="_blank">秒赞检测</a></li>
	<li role="presentation"><a role="menuitem" href="index.php?mod=sz&qq='.$qq.'" target="_blank">说说刷赞</a></li>
	<li role="presentation"><a role="menuitem" href="http://kf.qq.com/qzone/remove_qzone.html" target="_blank">解除禁言</a></li>
	<li role="presentation"><a role="menuitem" href="index.php?mod=friend&qq='.$qq.'" target="_blank">批量添加好友</a></li>
	<li role="presentation"><a role="menuitem" href="index.php?mod=qzmusic&qq='.$qq.'" target="_blank">空间背景音乐</a></li>
	<li role="presentation"><a role="menuitem" href="index.php?mod=qzyc&qq='.$qq.'" target="_blank">空间状态查询</a></li>
	<li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#joinGroup" href="#" id="qqlist05">在线申请加群</a></li>
	<li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#inviteJoinGroup" href="#" id="qqlist06">在线拉人进群</a></li>
	<li role="presentation"><a role="menuitem" href="http://kiss.3g.qq.com/activeQQ/mqq/qqGroup/myGroupList.jsp?sid='.$qqrow['sid'].'" target="_blank">看群聊天记录</a></li>
   </ul>
</span>
<span class="dropdown">
   <button href="#" class="btn btn-info" data-toggle="dropdown" role="button">批量操作 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" href="index.php?mod=set&my=qkqqrw&qq='.$qq.$link.'">清空所有</a></li>
   </ul>
</span>
';
$sql="proxy='{$qq}'";
}
elseif(isset($_GET['sign'])) {

$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE type='2' and lx='{$gl}'");

echo '<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li class="active">自动签到</li>
</ol>';
echo '<div class="alert alert-info">★你总共建立了'.$gls.'个自动签到任务！<br/>(此为各大网站自动签到，腾讯类自动签到请到QQ管理中添加)</div>';
echo '
<button href="#" data-toggle="modal" data-target="#signer" class="btn btn-success" id="qdlist">添加网站签到任务</button>
<span class="dropdown">
   <button href="#" class="btn btn-info" data-toggle="dropdown" role="button">任务操作 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" href="index.php?mod=set&my=qkqd'.$link.'">清空所有</a></li>
   </ul>
</span>
';
$sql="lx='{$gl}' and type='2'";
}
elseif(isset($_GET['sys'])) {
$sysid=(int)$_GET['sys'];
if($sysid>$conf['sysnum'] || $sysid<=0)exit('参数错误！');

$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='{$gl}' and sysid='{$sysid}'");

echo '<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=user">任务管理</a></li>
  <li class="active">系统'.$sysname[$sysid].'</li>
</ol>';
echo '<div class="alert alert-info">★系统'.$sysname[$sysid].'任务列表★[<a href="index.php?mod=user&'.$link.'">切换系统</a>]<br/>★你总共建立了'.$gls.'个任务！</div>';
echo '
<span class="dropdown">
   <button href="#" class="btn btn-primary" data-toggle="dropdown" role="button">添加任务 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?mod=sc&my=add&sys='.$sysid.$link.'">添加网址监控任务</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?mod=sc&my=bulk&sys='.$sysid.$link.'">批量添加网址任务</a></li>
   </ul>
</span>
<span class="dropdown">
   <button href="#" class="btn btn-info" data-toggle="dropdown" role="button">任务操作 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" href="index.php?mod=output&sys='.$sysid.$link.'">导出所有</a></li>
    <li role="presentation"><a role="menuitem" href="index.php?mod=sc&my=upload&sys='.$sysid.$link.'">文件导入</a></li>
	<li role="presentation" class="divider"></li>
    <li role="presentation"><a role="menuitem" href="index.php?mod=set&my=qk&sys='.$sysid.$link.'">清空所有</a></li>
   </ul>
</span>
';
$sql="lx='{$gl}' and sysid='{$sysid}' and type!=3";
}

$pagesize=$conf['pagesize'];
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

?>

<script>
$(document).ready(function(){
$("#qqlist01").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=1&qq=<?php echo $qq ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qqlist02").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=2&qq=<?php echo $qq ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qqlist03").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=3&qq=<?php echo $qq ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qqlist04").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=4&qq=<?php echo $qq ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qdlist").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=5&sys=<?php echo $sysid ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qqlist05").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=6&qq=<?php echo $qq ?>&sid=<?php echo $qqrow['sid'] ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
$("#qqlist06").click(function(){
  htmlobj=$.ajax({url:"template/default/display.php?list=7&qq=<?php echo $qq ?>&sid=<?php echo $qqrow['sid'] ?>",async:false});
  $("#myDiv").html(htmlobj.responseText);
});
});
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
$i=0;
$rs=$DB->query("SELECT * FROM ".DBQZ."_job WHERE {$sql} order by jobid desc limit $pageu,$pagesize");
while($myrow = $DB->fetch($rs))
{
$i++;
$pagesl = $i + ($page - 1) * $pagesize;
if($myrow['type']==1){
	$qq=$myrow['proxy'];
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['lx']==$gl)$myrow['url']=str_replace('[sid]',$row['sid'],$myrow['url']);
}
if($myrow['type']==3){
	$qqjob=qqjob_decode($myrow['url']);
  echo '<tr><td style="width:40%;"><b>'.$pagesl.'.'.$myrow['mc'].'</b>-系统'.$sysname[$myrow['sysid']].'<br/>'.$qqjob['info'];
} else {
  echo '<tr><td style="width:40%;"><b>'.$pagesl.'.'.$myrow['mc'].'</b><br/><a href="'.$myrow['url'].'" target="_blank">'.$myrow['url'].'</a><br>';
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
$s = ceil($gls / $pagesize);
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$s;
if ($page>1)
{
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=list&sys='.$sysid.'&page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页

}
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);
}


include(ROOT.'includes/foot.php');

if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</div></div></div></body></html>';
?>