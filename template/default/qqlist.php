<?php
 /*
　*　用户中心文件
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="QQ挂机列表";
include_once(TEMPLATE_ROOT."head.php");


if($islogin==1){

if(isset($_GET["super"]) && $isadmin==1) {
$gls=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");
$gxsid=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE status!='1'");
} else {
$gls=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE lx='{$gl}'");//更新任务总数
if($row['qqnum']==$gls){}else{
$DB->query("UPDATE ".DBQZ."_user SET qqnum= '$gls' WHERE user = '$gl'");}

$gxsid=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE status!='1' and lx='{$gl}'");
}
echo '<div class="col-md-9" role="main">';
echo '<ul class="nav nav-tabs">
	  <li class="active" ><a href="#">ＱＱ管理</a></li>
	  <li class="" ><a href="index.php?mod=dama">协助打码</a></li>
	  <li class="" ><a href="index.php?mod=wall">ＱＱ墙</a></li>
</ul>';
echo '<div class="alert alert-info">★你总共添加了 <font color="red">'.$gls.'</font> 个QQ账号！
<br/>你当前有 <font color="red">'.$gxsid.'</font> 个QQ的SID&Skey等待更新！<br/>
[<a href="index.php?mod=set&my=mail&'.$link.'">点此设置SID&Skey失效提醒邮箱</a>]</div>';
echo '
<a href="'.$qqlogin.'" class="btn btn-success">添加QQ账号</a>&nbsp;
<span class="dropdown">
   <button href="#" class="btn btn-info" data-toggle="dropdown" role="button">批量操作 <span class="caret"></span></button>
   <ul class="dropdown-menu" role="menu">
    <li role="presentation"><a role="menuitem" href="index.php?mod=set&my=qkqq'.$link.'">清空所有</a></li>
   </ul>
   &nbsp;<button href="#" class="btn btn-default" data-toggle="modal" data-target="#help">帮助</button>
</span>
';

$pagesize=$conf['pagesize'];
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

?>

<div class="modal fade" align="left" id="help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">QQ挂机系统使用说明</h4>
      </div>
      <div class="modal-body">
首先添加一个QQ账号，添加完成后回到本页面，点击你的QQ号码进入任务列表，在任务列表里可以添加秒赞、秒评、自动说说、QQ机器人等QQ挂机服务。<br/>以后要经常来此页面更新失效的SID&Skey。<br/>
<font color="red">PC协议不容易提示频繁，但由于是使用skey所以失效很快，基本每天都会失效一次。</font><br/>
<font color="blue">【关于不能赞与秒评说明】<br/>首先看看是否能手动点赞，如果自己手动都不能点赞，平台当然也不行(腾讯限制,会自动解除,最好把秒赞关了,一般半小时内会解除)。如果手动可以赞，平台不行，请检查skey是否失效，如果没有失效，就强制更新下skey即可！秒评建议大家不要开启，容易被腾讯禁言！</font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space: pre-wrap;}
</style>
<div class="panel panel-default table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>QQ账号</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tobdy>
<?php
$i=0;
if(isset($_GET['super']) && $isadmin==1) {
	if(isset($_GET['qq']))
		$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE qq='{$_GET['qq']}' order by id desc limit $pageu,$pagesize");
	else
		$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE 1 order by id desc limit $pageu,$pagesize");
	$link.='&super=1';
} else
$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE lx='{$gl}' order by id desc limit $pageu,$pagesize");
while($myrow = $DB->fetch($rs))
{
$i++;
$pagesl = $i + ($page - 1) * $pagesize;
  echo '<tr><td style="width:30%;"><b><a href="index.php?mod=list&m=qq&qq='.$myrow['qq'].$link.'">'.$myrow['qq'].'</a></b><img border="0" src="//wpa.qq.com/pa?p=9:'.$myrow['qq'].':4" alt="QQ状态" title="QQ状态"/><br/>';
  if(isset($_GET['super']) && $isadmin==1)echo '所属用户:<a href="index.php?mod=admin-user&my=user&user='.$myrow['lx'].$link.'">'.$myrow['lx'].'</a>';
  else echo '<a href="index.php?mod=list&m=qq&qq='.$myrow['qq'].$link.'"><span class="label label-success">进入任务管理</span></a>';
  echo '</td><td style="width:40%">';
if ($myrow['status'] == '1')
echo '<font color="green">SID正常！</font>';
else
echo '<font color="red">SID已失效！</font>';
if ($myrow['status2'] == '1')
echo '<br/><font color="green">SKEY正常！</font>';
else
echo '<br/><font color="red">SKEY已失效！</font>';
echo '</td><td style="width:30%"><a class="btn btn-sm btn-info" href="index.php?mod=addqq&my=gxsid&qq='.$myrow['qq'].$link.'">更新SID&SKEY</a><br/><a class="btn btn-sm btn-danger" href="index.php?mod=addqq&my=del&qq='.$myrow['qq'].$link.'" onclick="return confirm(\'你确实要删除此QQ号及此QQ下所有挂机任务吗？\');">删除此QQ</a></td></tr>';}

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
echo '<li><a href="index.php?mod=qqlist&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=qqlist&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=qqlist&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="index.php?mod=qqlist&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="index.php?mod=qqlist&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=qqlist&page='.$last.$link.'">尾页</a></li>';
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