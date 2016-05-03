<?php
if(!defined('IN_CRONLITE'))exit();
$title="注册用户管理";
include_once(TEMPLATE_ROOT."head.php");

$my=isset($_GET['my'])?$_GET['my']:null;

if ($isadmin!=1)
{
header("location:index.php");
}
else
{

if($my==null){

if(isset($_GET['kw'])) {
	$sql=" `user` LIKE '%{$_GET['kw']}%'";
	$link='&kw='.$_GET['kw'];
	$rownum='包含'.$_GET['kw'].'的共有';
}
elseif(isset($_GET['id'])) {
	$sql=" `userid`='{$_GET['id']}'";
	$link='';
	$rownum='本站共有';
} else {
	$sql=' 1';
	$link='';
	$rownum='本站共有';
}

$numrows = $DB->count("select count(*) from ".DBQZ."_user where".$sql);

echo '<div class="w h">注册用户管理</div>';
echo '<div class="row">'.$rownum.$numrows.'个用户 [<a href="index.php?mod=reg">添加一个用户</a>][<a href="index.php?mod=admin-upuser">一键刷新用户任务数</a>]</div>';

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

$rs=$DB->query("select * from ".DBQZ."_user where{$sql} order by userid desc limit $offset,$pagesize");
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
echo 'ID:'.$myrow['userid'].'(<a href="index.php?mod=user&user='.$myrow['user'].'">管理</a>|<a href="index.php?mod=admin-user&my=del&user='.$myrow['user'].'">删除</a>)<br>用户名:<a href="index.php?mod=admin-user&my=user&uid='.$myrow['userid'].'">'.$myrow['user'].'</a><br>密码:'.$myrow['pass'].'<br>注册日期:'.$myrow['date'].'<br>最后登录:'.$myrow['last'].'<br>任务:'.$myrow['num'].'(<a href="index.php?mod=admin-user&my=qk&user='.$myrow['user'].'">清空</a>)&nbsp;QQ:'.$myrow['qqnum'].'(<a href="index.php?mod=admin-user&my=qkqq&user='.$myrow['user'].'">清空</a>)</div>';
}

echo'<div class="w">';
echo "共有".$pages."页(".$page."/".$pages.")<br>";
for ($i=1;$i<$page;$i++)
echo "<a href='index.php?mod=admin-user&page=".$i.$link."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='index.php?mod=admin-user&page=".$i.$link."'>[".$i ."]</a> ";
echo '<br>';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo "<a href='index.php?mod=admin-user&page=".$first.$link."'>首页</a>.";
echo "<a href='index.php?mod=admin-user&page=".$prev.$link."'>上一页</a>";
}
if ($page<$pages)
{
echo "<a href='index.php?mod=admin-user&page=".$next.$link."'>下一页</a>.";
echo "<a href='index.php?mod=admin-user&page=".$last.$link."'>尾页</a>";
}
echo'</div>';
##分页
}
}
if($my=='user'){
$user=$_GET['user'];
$userid=$_GET['uid'];
$row=$DB->get_row("select * from ".DBQZ."_user where user='$user' or userid='$userid' limit 1");

if($row['vip']==1)$vipstatus='到期时间:<font color="green">'.$row['vipdate'].'</font>';
elseif($row['vip']==2)$vipstatus='<font color="green">永久 VIP</font>';
else $vipstatus='<font color="red">非 VIP</font>';

echo '<div class="w h">用户资料</div>';
echo '<div class="box"><b>UID：</b>'.$row['userid'];
echo '<br><b>用户名：</b>'.$row['user'].'<br><b>用户组：</b>'.usergroup().'<br><b>密码：</b>'.$row['pass'].'<br><b>邮箱：</b>'.$row['email'].'<br><b>注册日期：</b>'.$row['date'].'<br><b>最后登录：</b>'.$row['last'].'<br><b>注册IP：</b><a href="http://wap.ip138.com/ip_search138.asp?ip='.$row['zcip'].'" target="_blank">'.$row['zcip'].'</a><br><b>登录IP：</b><a href="http://wap.ip138.com/ip_search138.asp?ip='.$row['dlip'].'" target="_blank">'.$row['dlip'].'</a><br><b>任务数量：</b>'.$row['num'].'(<a href="index.php?mod=admin-user&my=qk&user='.$row['user'].'">清空</a>)<br>
<b>ＱＱ数量：</b>'.$row['qqnum'].'(<a href="index.php?mod=set&my=qkqq&user='.$row['user'].'">清空</a>)<br>
<b>'.$conf['coin_name'].'：</b><font color="red">'.$row['coin'].'</font><br>
<b>VIP状态：</b>'.$vipstatus.'</div>';

echo '<div class="w h">信息修改</div>';
echo '<div class="box"><form action="index.php?mod=admin-user&my=edit" method="POST"><input type="hidden" name="userid" value="'.$row['userid'].'">
<b>是否VIP：</b><select name="isvip"><option value="'.$row['vip'].'">'.$row['vip'].'</option><option value="0">0_否</option><option value="1">1_是</option><option value="2">2_永久</option></select>
<br/>
<b>VIP到期时间：</b>
<input type="text" name="vipdate" value="'.$row['vipdate'].'"><br/>
<b>'.$conf['coin_name'].'：</b>
<input type="text" name="coin" value="'.$row['coin'].'">
<b>是否代理：</b><select name="daili"><option value="'.$row['daili'].'">'.$row['daili'].'</option><option value="0">0_否</option><option value="1">1_是</option></select>
<br/>
<b>代理账户余额：</b>
<input type="text" name="daili_rmb" value="'.$row['daili_rmb'].'"><br/>
<b>密码(不重置请留空)：</b>
<input type="text" name="password" value=""><br/>
<br/>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>
</div>';

echo '<div class="w h">用户操作</div>';
echo '<div class="box"><a href="index.php?mod=user&user='.$row['user'].'">管理网络任务</a>|<a href="index.php?mod=qqlist&user='.$row['user'].'">管理QQ账号</a><br><a href="index.php?mod=admin-user&my=qkjf&user='.$row['user'].'">清空所有积分</a>|<a href="index.php?mod=admin-user&my=del&user='.$row['user'].'">删除该用户</a></div></div>';
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
echo '<div class="box"><a href="index.php?mod=admin-user&my=del_ok&user='.$user.'">确定要删除吗？是！</a></div>'; 
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
elseif($my=='daili_add'){
$uid=$_POST['uid'];
$DB->query("UPDATE ".DBQZ."_user SET daili='1' WHERE userid='$uid'");
exit('<script>window.location.href="index.php?mod=admin-user&daili=1";</script>');
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
echo'<br><div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="./">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></body></html>';
?>