<?php
if(!defined('IN_CRONLITE'))exit();
$title="注册用户管理";
include_once(TEMPLATE_ROOT."head.php");

$my=isset($_GET['my'])?$_GET['my']:null;
echo '<div class="col-md-9" role="main">';

if ($isadmin!=1)
{
header("location:index.php");
}
else
{

if(isset($_GET['daili'])) $link='&daili=1';

if($my==null){

if(isset($_GET['kw'])) {
	$sql=" `user` LIKE '%{$_GET['kw']}%'";
	$link='&kw='.$_GET['kw'];
	$rownum='包含'.$_GET['kw'].'的共有';
} elseif(isset($_GET['id'])) {
	$sql=" `userid`='{$_GET['id']}'";
	$link='';
	$rownum='本站共有';
} elseif(isset($_GET['daili'])) {
	$sql=" `daili`='1'";
	$rownum='本站共有代理用户';
} else {
	$sql=' 1';
	$link='';
	$rownum='本站共有';
}

$numrows = $DB->count("select count(*) from ".DBQZ."_user where".$sql);

echo '<h3>注册用户管理</h3>';
echo '<div class="alert alert-info">'.$rownum.$numrows.'个用户 [<a href="index.php?mod=reg">添加一个用户</a>][<a href="index.php?mod=admin-upuser">一键刷新用户任务数</a>]</div>';

if(isset($_GET['daili'])){
	echo '<form action="index.php?mod=admin-user&my=daili_add" class="form-inline" method="post">
		<div class="form-group">设置UID为<input type="text" style="width:50px;" name="uid">的会员为代理.<input type="submit" class="btn btn-primary" name="submit" value="确定"></div>
		</form>
';
}
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
<style>
.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space: pre-wrap;}
</style>
<div class="panel panel-default table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>UID</th>
			<th>用户名</th>
			<th>注册及登录信息</th>
			<th>数量</th>
		</tr>
	</thead>
	<tobdy>
<?php

$rs=$DB->query("select * from ".DBQZ."_user where{$sql} order by userid desc limit $offset,$pagesize");
$i=0;
while ($myrow = $DB->fetch($rs))
{
$i++;
$pagesl=$i+($page-1)*$pagesize;
if($myrow['daili']==1)$dailicon='<font color="red">[代理]</font>RMB：'.$myrow['daili_rmb'];
echo '<tr><td style="width:20%;"><b>'.$myrow['userid'].'</b><br/><a href="index.php?mod=admin-user&my=del&user='.$myrow['user'].$link.'">删除</a>.<a href="index.php?mod=user&user='.$myrow['user'].'">任务</a></td><td style="width:30%">用户名:<a href="index.php?mod=admin-user&my=user&uid='.$myrow['userid'].$link.'">'.$myrow['user'].'</a><br/>'.$dailicon.'</td><td style="width:30%">注册日期:<font color="blue">'.$myrow['date'].'</font><br>最后登录:<font color="blue">'.$myrow['last'].'</font></td><td style="width:20%">任务:'.$myrow['num'].'(<a href="index.php?mod=admin-user&my=qk&user='.$myrow['user'].$link.'">清空</a>)<br/>ＱＱ:'.$myrow['qqnum'].'(<a href="index.php?mod=admin-user&my=qkqq&user='.$myrow['user'].$link.'">清空</a>)</td></tr>';
unset($dailicon);
}
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
echo '<li><a href="index.php?mod=admin-user&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=admin-user&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=admin-user&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?mod=admin-user&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?mod=admin-user&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=admin-user&page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}

elseif($my=='user'){
$user=$_GET['user'];
$userid=$_GET['uid'];
$row=$DB->get_row("select * from ".DBQZ."_user where user='$user' or userid='$userid' limit 1");

if($row['vip']==1)$vipstatus='到期时间:<font color="green">'.$row['vipdate'].'</font>';
elseif($row['vip']==2)$vipstatus='<font color="green">永久 VIP</font>';
else $vipstatus='<font color="red">非 VIP</font>';

echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">用户资料</h3></div>';
echo '<div class="panel-body box"><b>UID：</b>'.$row['userid'];
$isadmin=0;$isdaili=0;
echo '<br><b>用户名：</b>'.$row['user'].'<br><b>用户组：</b>'.usergroup().'<br><b>密码：</b>'.$row['pass'].'<br><b>邮箱：</b>'.$row['email'].'<br><b>注册日期：</b>'.$row['date'].'<br><b>最后登录：</b>'.$row['last'].'<br><b>注册IP：</b><a href="http://wap.ip138.com/ip_search138.asp?ip='.$row['zcip'].'" target="_blank">'.$row['zcip'].'</a><br><b>登录IP：</b><a href="http://wap.ip138.com/ip_search138.asp?ip='.$row['dlip'].'" target="_blank">'.$row['dlip'].'</a><br><b>任务数量：</b>'.$row['num'].'(<a href="index.php?mod=admin-user&my=qk&user='.$row['user'].'">清空</a>)<br>
<b>ＱＱ数量：</b>'.$row['qqnum'].'(<a href="index.php?mod=set&my=qkqq&user='.$row['user'].'">清空</a>)<br>
<b>'.$conf['coin_name'].'：</b><font color="red">'.$row['coin'].'</font><br>
<b>VIP状态：</b>'.$vipstatus.'</div>';

echo '<div class="panel-heading w h"><h3 class="panel-title">信息修改</h3></div>';
echo '<div class="panel-body box"><form action="index.php?mod=admin-user&my=edit'.$link.'" method="POST"><input type="hidden" name="userid" value="'.$row['userid'].'">
<b>是否VIP：</b><select name="isvip"><option value="'.$row['vip'].'">'.$row['vip'].'</option><option value="0">0_否</option><option value="1">1_是</option><option value="2">2_永久</option></select>
<br/>
<b>VIP到期时间：</b>
<input type="text" name="vipdate" value="'.$row['vipdate'].'"><br/>
<b>'.$conf['coin_name'].'：</b>
<input type="text" name="coin" value="'.$row['coin'].'"><br/>
<b>是否代理：</b><select name="daili"><option value="'.$row['daili'].'">'.$row['daili'].'</option><option value="0">0_否</option><option value="1">1_是</option></select>
<br/>
<b>代理账户余额：</b>
<input type="text" name="daili_rmb" value="'.$row['daili_rmb'].'"><br/>
<b>密码(不重置请留空)：</b>
<input type="text" name="password" value=""><br/>
<br/>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>
</div>';

echo '<div class="panel-heading w h"><h3 class="panel-title">用户操作</h3></div>';
echo '<div class="panel-body box"><a href="index.php?mod=user&user='.$row['user'].'">管理网络任务</a>|<a href="index.php?mod=qqlist&user='.$row['user'].'">管理QQ账号</a><br><a href="index.php?mod=admin-user&my=qkjf&user='.$row['user'].'">清空所有积分</a>|<a href="index.php?mod=admin-user&my=del&user='.$row['user'].'">删除该用户</a></div></div>';
echo '<a href="index.php?mod=admin-user'.$link.'">>>返回用户管理</a>';
}

elseif($my=='edit'){
echo '<div class="w h">信息修改</div><div class="box">';
$userid=$_POST['userid'];
$vip=$_POST['isvip'];
$vipdate=$_POST['vipdate'];
$coin=$_POST['coin'];
$password=$_POST['password'];
$daili=$_POST['daili'];
$daili_rmb=$_POST['daili_rmb'];
$sql=$DB->query("update `".DBQZ."_user` set `vip` ='$vip',`vipdate` ='$vipdate',`coin` ='$coin',`daili` ='$daili',`daili_rmb` ='$daili_rmb' where `userid`='$userid'");
if(!empty($password))$DB->query("update `".DBQZ."_user` set `pass` ='$password' where `userid`='$userid'");
if($sql){echo '修改成功！';}
else{echo '修改失败！';}
echo '</div><a href="index.php?mod=admin-user'.$link.'">>>返回用户管理</a>';
}

elseif($my=='del'){
echo '<div class="w h">删除用户</div>';
$user=$_GET['user'];
$row2=$DB->get_row("select * from ".DBQZ."_user where user='$user' limit 1");
if($row2['userid']==1)exit('你不能删除管理员！');
echo '<div class="box"><a href="index.php?mod=admin-user&my=del_ok&user='.$user.$link.'">确定要删除吗？是！</a></div>'; 
echo '<a href="index.php?mod=admin-user'.$link.'">>>返回用户管理</a>';
}

elseif($my=='del_ok'){
echo '<div class="w h">删除用户</div><div class="box">';
$user=$_GET['user'];
$row2=$DB->get_row("select * from ".DBQZ."_user where user='$user' limit 1");
if($row2['userid']==1)exit('你不能删除管理员！');
$sql=$DB->query("DELETE FROM ".DBQZ."_user WHERE user='$user'");
$DB->query("DELETE FROM ".DBQZ."_job WHERE lx='$user'");
$DB->query("DELETE FROM ".DBQZ."_qq WHERE lx='$user'");
if($sql){echo '删除成功！';}
else{echo '删除失败！';}
echo '</div><a href="index.php?mod=admin-user'.$link.'">>>返回用户管理</a>';
}

elseif($my=='qk'){//清空任务
$user=$_GET['user'];
echo '<div class="box">您确认要清空用户 '.$user.' 的所有任务吗？清空后无法恢复！<br><a href="index.php?mod=admin-user&my=qk2&user='.$user.'">确认</a> | <a href="javascript:history.back();">返回</a></div>';
}
elseif($my=='qk2'){//清空任务结果
$user=$_GET['user'];
if($DB->query("DELETE FROM ".DBQZ."_job WHERE lx='$user'")==true){
$DB->query("UPDATE ".DBQZ."_user SET num= '0' WHERE user = '$user'");
echo '<div class="box">清空成功，</div>';
}else{
echo'<div class="box">清空失败，</div>';
}
}
elseif($my=='daili_add'){
$uid=$_POST['uid'];
$DB->query("UPDATE ".DBQZ."_user SET daili='1' WHERE userid='$uid'");
exit('<script>window.location.href="index.php?mod=admin-user&daili=1";</script>');
}
}
echo'<br><div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></body></html>';
?>