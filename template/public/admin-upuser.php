<?php
 /*
　*　一键更新所有用户任务条数
*/
if(!defined('IN_CRONLITE'))exit();
$title="后台管理";
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';
if ($isadmin==1)
{
$c=0;
$rs=$DB->query("SELECT * FROM ".DBQZ."_user order by userid asc");
while($row = $DB->fetch($rs))
{
$gl=$row['user'];
$num=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='$gl'");
$ad=$DB->query("update `".DBQZ."_user` set `num` ='$num' where `user`='$gl'");
if($ad)$c++;
}
showmsg('一键更新所有用户任务条数成功<br/>SQL共执行'.$c.'句。',3);

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
echo'</div></div></div></body></html>';
?>