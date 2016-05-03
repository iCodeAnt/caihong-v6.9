<?php
 /*
　*　后台管理文件
*/
if(!defined('IN_CRONLITE'))exit();

if(isset($_GET['type'])){
	if($_GET['type']=='user')
		exit('<script>window.location.href="index.php?mod=admin-user&kw='.$_GET['kw'].'";</script>');
	elseif($_GET['type']=='job')
		exit('<script>window.location.href="index.php?mod=admin-job&kw='.$_GET['kw'].'";</script>');
	elseif($_GET['type']=='qq')
		exit('<script>window.location.href="index.php?mod=qqlist&super=1&qq='.$_GET['kw'].'";</script>');
	elseif($_GET['type']=='km')
		exit('<script>window.location.href="index.php?mod=admin-kmlist&km='.$_GET['kw'].'";</script>');
}

$title="后台管理";
include_once(TEMPLATE_ROOT."head.php");
if($conf['css']!='2')
{
echo'<div class="w h Header">
<img src="images/logo.png"><br>'.$conf['sitename'].'</div>';}
if(defined("SAE_ACCESSKEY"))$host = SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT;

if ($isadmin==1)
{
$ntime=date("G"); //取得现在的时间
if($ntime>=0 and $ntime<4){echo'<div class="w nav"><em>午夜好！</em>';}
if($ntime>=4 and $ntime<11){echo'<div class="w nav"><em>早上好！</em>';}
if($ntime>=11 and $ntime<14){echo'<div class="w nav"><em>中午好！</em>';}
if($ntime>=14 and $ntime<18){echo'<div class="w nav"><em>下午好！</em>';}
if($ntime>=18 and $ntime<24){echo'<div class="w nav"><em>晚上好！</em>';}
echo'<em>管理员</em></div>';
echo '<div class="w h">后台管理</div><div class="box">';
echo '<label>搜索：</label><br/><form action="index.php" medhod="get"><input type="hidden" name="mod" value="admin"><input type="text" name="kw" value="" placeholder= "输入搜索内容"><br/>
<label class="radio-inline"><input type="radio" name="type" value="user" checked="checked"/>用户</label>&nbsp;<label class="radio-inline"><input type="radio" name="type" value="job"/>任务</label>&nbsp;<label class="radio-inline"><input type="radio" name="type" value="qq"/>ＱＱ</label>&nbsp;<label class="radio-inline"><input type="radio" name="type" value="km"/>卡密</label><br/>
<input type="submit" value="搜索管理"></form>';
echo '</div>';
echo '<div class="w h">基本操作</div><div class="box">';
echo '<a style="color:green" href="index.php?mod=admin-job">任务数据管理</a>';
echo '<br>【<a href="index.php?mod=admin-job&sys=1">系统①</a>|<a href="index.php?mod=admin-job&sys=2">系统②</a>|<a href="index.php?mod=admin-job&sys=3">系统③</a>|<a href="index.php?mod=admin-job&sys=4">系统④</a>】<br>【<a href="index.php?mod=admin-job&sys=5">系统⑤</a>|<a href="index.php?mod=admin-job&sys=6">系统⑥</a>|<a href="index.php?mod=admin-job&sys=7">系统⑦</a>|<a href="index.php?mod=admin-job&sys=8">系统⑧</a>】<hr>';
echo '●<a href="index.php?mod=qqlist&super=1">所有ＱＱ账号管理</a><br>
●<a href="index.php?mod=admin-user">网站注册用户管理</a><br>
●<a href="index.php?mod=admin-kmlist&kind=1">充值卡卡密管理</a><br>
●<a href="index.php?mod=admin-kmlist&kind=2">VIP卡 卡密管理</a><br>
●<a href="index.php?mod=admin-set&my=qunfa">全站发送指定邮件</a><br>';
if(!defined('SQLITE'))echo '●<a href="index.php?mod=admin-set&my=sqladmin">管理系统SQL</a>';

echo '</div><div class="w h">系统设置</div><div class="box">';
echo '●<a href="index.php?mod=admin-set&my=set_config">网站信息配置</a><br>
●<a href="index.php?mod=admin-set&my=set_mail">发信邮箱配置</a><br>
●<a href="index.php?mod=admin-set&my=set_rw">任务运行配置</a><br>
●<a href="index.php?mod=admin-set&my=help">任务监控说明</a><br>
●<a href="index.php?mod=admin-set&my=set_api">挂机模块API配置</a><br>
●<a href="index.php?mod=admin-set&my=set_gg">广告与公告配置</a><br>
●<a href="index.php?mod=admin-set&my=set_coin">币种消费规则设定</a><br>
●<a href="index.php?mod=admin-set&my=set_vip">网站VIP规则设定</a></div>';

echo '</div><div class="w h">外观设置</div><div class="box">';
echo '●<a href="index.php?mod=admin-set&my=set_css">更改系统皮肤</a><font color="red">[NEW]</font><br>
●<a href="index.php?mod=admin-set&my=logo">更改系统LOGO</a><br>
●<a href="index.php?mod=admin-set&my=bj">更改背景图片</a></div>';

echo '<div class="w h">清空相关</div><div class="box">';
echo '●<a href="index.php?mod=admin-clear&my=qlzt">清理所有已暂停的任务</a><br>
●<a href="index.php?mod=admin-clear&my=qlqq">清理所有SID过期QQ</a><br>
●<a href="index.php?mod=admin-clear&my=chat">清空所有聊天记录</a><br>
●<a href="index.php?mod=admin-clear&my=users">清空无挂机用户</a><br>
●<a href="index.php?mod=admin-clear&my=jobs">清空全部挂机任务</a><br>
●<a href="index.php?mod=admin-clear&my=sysall">清空全站所有数据</a></div>';

echo '<div class="w h">程序相关</div><div class="box">';
echo '●<a href="index.php?mod=update">检测版本更新</a><br>
●<a href="readme.txt">查看更新日志</a><br>
●<a href="install/update.txt">查看旧更新日志</a><br>
●<a href="index.php?mod=admin-set&my=info">查看程序版本信息</a><br>
●<a href="index.php?mod=admin-set&my=set_client">安卓客户端配置</a><br>
●<a href="http://blog.cccyun.cn/m/?post=144" target="_blank">彩虹云任务FAQ大全</a><br>
●<a href="http://blog.cccyun.cn/m/?post=150" target="_blank">客户端APP定制</a><font color="red">[NEW]</font></div>';

echo '<div class="w h">运行日志:&nbsp&nbsp<a href="index.php?mod=all">详细>></a></div><div class="box">系统共有'.$zongs.'条任务<br>系统累计运行了'.$info['times'].'次<br>上次运行:'.$info['last'].'<br>当前时间:'.$date.'</div>';
if(function_exists("sys_getloadavg")){
echo'<div class="w h">系统负载:</div>';
$f=sys_getloadavg();
echo'<div class="box">';
echo"1min:{$f[0]}";
echo"|5min:{$f[1]}";
echo"|15min:{$f[2]}";
echo'</div>';}
echo'<div class="w h">数据统计:</div>';
echo'<div class="box">';
echo'系统共有'.$users.'个用户<br/>';
include(ROOT."includes/content/tongji.php");
echo'</div>';
}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php">返回首页</a>-<a href="index.php?mod=help">功能介绍</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</body></html>';
?>