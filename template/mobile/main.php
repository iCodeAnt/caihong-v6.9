<?php
if(!defined('IN_CRONLITE'))exit();
function navi()
{
global $css,$isadmin,$islogin,$gl,$sitename;
if($css!=='2')
{
	echo'<div class="w h Header"><img id="lg" title="'.$sitename.'" src="images/logo.png"><br>'.$sitename.'</div>';
}
if($islogin==1)
{
	$ntime=date("G"); //取得现在的时间
	if($ntime>=0 and $ntime<4){echo'<div class="w nav"><em>午夜好！</em>';}
	if($ntime>=4 and $ntime<11){echo'<div class="w nav"><em>早上好！</em>';}
	if($ntime>=11 and $ntime<14){echo'<div class="w nav"><em>中午好！</em>';}
	if($ntime>=14 and $ntime<18){echo'<div class="w nav"><em>下午好！</em>';}
	if($ntime>=18 and $ntime<24){echo'<div class="w nav"><em>晚上好！</em>';}
	echo'<a href="index.php">'.$gl.'</a>&nbsp;&nbsp;<a href="index.php?mod=user">管理</a>&nbsp;&nbsp;<a href="index.php?mod=chat">聊天</a>';
	if($isadmin==1)echo'&nbsp;&nbsp;<a href="index.php?mod=admin">后台</a>';
	echo '</div>';
}
else
{
	echo'<div class="w nav"><a href="index.php">首页</a>&nbsp;&nbsp;<a href="index.php?mod=chat">聊天</a>&nbsp;&nbsp;<a href="index.php?mod=help">介绍</a>&nbsp;&nbsp;<a href="index.php?mod=login">登录</a>&nbsp;&nbsp;<a href="index.php?mod=reg">注册</a></div>';
}
}

function showmsg($content = '未知的异常',$type = 4,$back = false)
{
echo '<div class="w h">提示信息:</div>';
echo '<div class="box">';
echo $content;
if ($back == 'rw') {
	global $sysid,$link;
	echo '<hr/><a href="'.$_SERVER['HTTP_REFERER'].'"><< 返回我的任务列表</a>';
} elseif ($back == 'addrw') {
	global $sysid,$link;
	echo '<hr/><a href="'.$_SERVER['HTTP_REFERER'].'">>> 继续添加</a>';
	echo '<br/><a href="index.php?mod=list&sys='.$sysid.$link.'"><< 返回我的任务列表</a>';
} elseif ($back == 'addqqrw') {
	global $proxy,$link;
	echo '<hr/><a href="index.php?mod=list&qq='.$proxy.$link.'"><< 返回我的任务列表</a>';
} elseif ($back == 'addqdrw') {
	global $link;
	echo '<hr/><a href="index.php?mod=list&sign=1'.$link.'"><< 返回我的任务列表</a>';
} elseif ($back == 'addqq') {
	global $link,$qq;
	echo '<hr/><a href="index.php?mod=list&qq='.$qq.$link.'">>> 进入添加任务</a><br/><a href="index.php?mod=qqlist'.$link.'"><< 返回我的QQ列表</a>';
} elseif ($back == 'addqq2') {
	global $link;
	echo '<hr/><a href="index.php?mod=qqlist'.$link.'"><< 返回我的QQ列表</a>';
}
else
    echo '<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a>';

echo '</div>';
}

function showlogin()
{
global $islogin,$isadmin,$row;
if($islogin==1)
{
echo '<div class="w h">用户中心</div><div class="box">
用户名：'.$row['user'].' [<a href="index.php?mod=userinfo">用户资料</a>]<br>用户ID：'.$row['userid'].'('.usergroup().')';
echo'<br>挂机任务：<font color="#ff0000">'.$row['num'].'</font>条[<a href="index.php?mod=output">导出任务</a>]<br>注册时间：'.$row['date'].'<br/>';
echo '进入：<a href="index.php?mod=user">任务管理</a>|<a href="index.php?mod=qqlist">ＱＱ管理</a><br/>帐号：<a href="index.php?mod=set&my=mm">修改密码</a>|<a href="index.php?my=loginout">退出登陆</a></div>';
}
else
{
echo '<div class="w h">登录你的账号:</div>
<div class="box"><form action="?" method="get"><input type="hidden" name="my" value="login">
用户名:<br><input type="text" name="user" value=""><br>
密码:<br><input type="password" name="pass" value=""><br>
<input type="checkbox" name="ctime" checked="checked" value="2592000" > 下次自动登录<br>
<input type="submit" value="马上登录"></form>
<form action="index.php"><input type="hidden" name="mod" value="reg"><input type="submit" value="注册用户"></form></div>';
}
}