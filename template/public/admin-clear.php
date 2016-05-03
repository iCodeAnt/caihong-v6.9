<?php
 /*
　*　后台清空数据文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="后台管理";
include_once(TEMPLATE_ROOT."head.php");

$my=isset($_POST['my'])?$_POST['my']:$_GET['my'];
if($theme=='default')echo '<div class="col-md-9" role="main">';

if ($isadmin==1)
{
if($my == 'sysall'){
echo '<div class="w h"><h3>清空系统所有数据</h3></div><div class="box">';
echo '<font color="#FF0033">警告：此项操作将清空本站所有数据，包括用户数据和任务数据，且不可恢复！</font><br><br>
如果你确定要继续执行清空操作，请在下方输入“ok”并点击确定：<form action="index.php" method="GET"><input type="hidden" name="mod" value="admin-clear"><input type="hidden" name="my" value="sysall_ok">
<input type="text" class="form-control" name="key" value="" maxlength="10"><br/><input type="submit" class="btn btn-primary btn-block"
value="确定清空！"></form>';
echo '</div>';
}

elseif($my == 'sysall_ok' && $_GET['key']=='ok'){
$sql="TRUNCATE TABLE `".DBQZ."_user`;
TRUNCATE TABLE `".DBQZ."_job`;
TRUNCATE TABLE `".DBQZ."_qq`";
$sql=explode(';',$sql);
$t=0;$e=0;
for($i=0;$i<count($sql);$i++) {
if($DB->query($sql[$i]))
++$t;
else
++$e;
}
$DB->query("insert into `".DBQZ."_user` (`pass`,`user`,`date`,`last`) values ('".$row['pass']."','".$row['user']."','".$date."','".$date."')");
echo '<div class="box"><font color="green">已清空系统所有数据！</font><br/>（SQL成功执行'.$t.'句）<br></div>';
}

elseif($my == 'chat'){
echo '<div class="w h"><h3>清空所有聊天记录</h3></div><div class="box">';
echo '<font color="#FF0033">警告：此项操作将清空本站聊天室所有聊天记录，且不可恢复！</font><br><br>
如果你确定要继续执行清空操作，请在下方输入“ok”并点击确定：<form action="index.php" method="GET"><input type="hidden" name="mod" value="admin-clear"><input type="hidden" name="my" value="chat_ok">
<input type="text" class="form-control" name="key" value="" maxlength="10"><br/><input type="submit" class="btn btn-primary btn-block"
value="确定清空！"></form>';
echo '</div>';
}

elseif($my == 'chat_ok' && $_GET['key']=='ok'){
$sql="TRUNCATE TABLE `".DBQZ."_chat`";
if($DB->query($sql))
echo '<div class="box"><font color="green">已清空所有聊天数据！</font></div>';
else echo '<div class="box"><font color="red">清空失败！</font>'.$DB->error().'</div>';
}

elseif($my == 'jobs'){
echo '<div class="w h"><h3>清空全部挂机任务</h3></div><div class="box">';
echo '<font color="#FF0033">警告：此项操作将清空本站全部挂机任务，且不可恢复！</font><br><br>
如果你确定要继续执行清空操作，请在下方输入“ok”并点击确定：<form action="index.php" method="GET"><input type="hidden" name="mod" value="admin-clear"><input type="hidden" name="my" value="jobs_ok">
<input type="text" class="form-control" name="key" value="" maxlength="10"><br/><input type="submit" class="btn btn-primary btn-block"
value="确定清空！"></form>';
echo '</div>';
}

elseif($my == 'jobs_ok' && $_GET['key']=='ok'){
$sql="TRUNCATE TABLE `".DBQZ."_job`";
if($DB->query($sql))
echo '<div class="box"><font color="green">已清空全部挂机任务！</font></div>';
else echo '<div class="box"><font color="red">清空失败！</font>'.$DB->error().'</div>';
}

elseif($my == 'users'){
echo '<div class="w h"><h3>清空无挂机用户</h3></div><div class="box">';
echo '<font color="#FF0033">警告：此项操作将清空本站没有任何挂机的用户，且不可恢复！</font><br><br>
如果你确定要继续执行清空操作，请在下方输入“ok”并点击确定：<form action="index.php" method="GET"><input type="hidden" name="mod" value="admin-clear"><input type="hidden" name="my" value="users_ok">
<input type="text" class="form-control" name="key" value="" maxlength="10"><br/><input type="submit" class="btn btn-primary btn-block"
value="确定清空！"></form>';
echo '</div>';
}

elseif($my == 'users_ok' && $_GET['key']=='ok'){
$sql="DELETE FROM ".DBQZ."_user WHERE num='0' and userid!='1'";
if($DB->query($sql))
echo '<div class="box"><font color="green">已清空无挂机用户！</font></div>';
else echo '<div class="box"><font color="red">清空失败！</font>'.$DB->error().'</div>';
}

elseif($my == 'qlrw'){
echo '<div class="w h"><h3>删除指定任务</h3></div><div class="box">';
echo'您确认要删除所有包含'.$_GET['kw'].'的任务吗？清空后无法恢复！<br><a href="index.php?mod=admin-clear&my=qlrw_ok&kw='.urlencode($_GET['kw']).'">确认</a> | <a href="javascript:history.back();">返回</a>';
echo '</div>';
}

elseif($my == 'qlrw_ok'){
$sql="delete from `".DBQZ."_job` where `url` LIKE '%{$_GET['kw']}%'";
if($DB->query($sql))
echo '<div class="box"><font color="green">已删除所有包含'.$_GET['kw'].'的任务！</font></div>';
else echo '<div class="box"><font color="red">删除失败！</font>'.$DB->error().'</div>';
}

elseif($my == 'qlqq'){
echo '<div class="w h"><h3>清理SID过期QQ</h3></div><div class="box">';
echo'您确认要清理所有SID过期QQ吗？清空后无法恢复！<br><a href="index.php?mod=admin-clear&my=qlqq_ok">确认</a> | <a href="javascript:history.back();">返回</a>';
echo '</div>';
}

elseif($my == 'qlqq_ok'){
$sql="delete from `".DBQZ."_qq` where `status`='5' or `status`='4'";
if($DB->query($sql))
echo '<div class="box"><font color="green">已清理所有SID过期QQ！</font></div>';
else echo '<div class="box"><font color="red">删除失败！</font>'.$DB->error().'</div>';
}

elseif($my == 'qlzt'){
echo '<div class="w h"><h3>清理已暂停的任务</h3></div><div class="box">';
echo'您确认要清理所有已暂停的任务吗？清空后无法恢复！<br><a href="index.php?mod=admin-clear&my=qlzt_ok">确认</a> | <a href="javascript:history.back();">返回</a>';
echo '</div>';
}

elseif($my == 'qlzt_ok'){
$sql="delete from `".DBQZ."_job` where `zt`='1'";
if($DB->query($sql))
echo '<div class="box"><font color="green">已清理所有已暂停的任务！</font></div>';
else echo '<div class="box"><font color="red">删除失败！</font>'.$DB->error().'</div>';
}

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