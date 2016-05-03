<?php
 /*
　*　代理后台
*/
if(!defined('IN_CRONLITE'))exit();
$title="代理后台";
include_once(TEMPLATE_ROOT."head.php");

navi();

if ($isdaili==1)
{
echo '<div class="panel panel-primary">';
echo '<div class="panel-heading"><h3 class="panel-title" align="center">我的代理信息</h3></div><div class="panel-body">';
echo '<li class="list-group-item">代理等级：<font color="orange">金牌代理</font></li>';
echo '<li class="list-group-item">账户剩余可用余额：<font color="red">'.$row['daili_rmb'].'</font> RMB</li>';
echo '<li class="list-group-item">客服QQ号：<font color="red">'.$row['daili_qq'].'</font> <a href="index.php?mod=set&my=qq">[修改]</a></li>';
echo '</div></div>';

echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title" align="center">卡密管理</h3></div><div class="panel-body">';
echo '<a href="index.php?mod=admin-kmlist2&kind=1" class="btn btn-default btn-block">充值卡卡密管理</a>
<a href="index.php?mod=admin-kmlist2&kind=2" class="btn btn-default btn-block">VIP卡 卡密管理</a>';
echo '</div></div>';

echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">运行日志:&nbsp&nbsp<a href="index.php?mod=all">详细>></a></h3></div><div class="panel-body">系统共有<font color="#ff0000">'.$zongs.'</font>条任务<br>共有<font color="#ff0000">'.$qqs.'</font>个QQ正在挂机<br>系统累计运行了<font color="#ff0000">'.$info['times'].'</font>次<br>上次运行:<font color="#ff0000">'.$info['last'].'</font><br>当前时间:<font color="#ff0000">'.$date.'</font></div>';
if(function_exists("sys_getloadavg")){
echo'<div class="panel-heading"><h3 class="panel-title">系统负载:</h3></div>';
$f=sys_getloadavg();
echo'<div class="panel-body">';
echo"1min:{$f[0]}";
echo"|5min:{$f[1]}";
echo"|15min:{$f[2]}";
echo'</div>';}
echo'<div class="panel-heading"><h3 class="panel-title">数据统计:</h3></div>';
echo'<div class="panel-body">';
echo'系统共有'.$users.'个用户<br/>';
include(ROOT.'includes/content/tongji.php');
echo'</div></div>';
}
else
{
showmsg('代理后台登录失败。请以代理身份 <a href="index.php?mod=login&daili=1">重新登录</a>，或联系站长购买代理身份！',3);
}
echo'<div class="panel panel-primary"><div class="panel-body" style="text-align: center;">';
echo date("Y年m月d日 H:i:s");
include(ROOT.'includes/foot.php');
echo'</div></div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</div></div></div></body></html>';
?>