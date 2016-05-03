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

echo '<div class="w h">QQ挂机列表</div>';
echo '<div class="row">使用说明:
首先添加一个QQ账号，添加完成后回到本页面，点击你的QQ号码进入任务列表，在任务列表里可以添加秒赞、秒评、自动说说、QQ机器人等QQ挂机服务。以后要经常来此页面更新失效的SID&Skey。</div>';
echo '<div class="box">
★你总共添加了 '.$gls.' 个QQ账号！<br/>
你当前有 <font color="red">'.$gxsid.'</font> 个QQ的SID&Skey等待更新！<br/>
[<a href="index.php?mod=set&my=mail&'.$link.'">点此设置SID&Skey失效提醒邮箱</a>]<br/>';
echo '
<a href="'.$qqlogin.'">添加一个QQ账号</a><br/>
<a role="menuitem" href="index.php?mod=set&my=qkqq'.$link.'">清空所有</a></div>';


$pagesize=$conf['pagesize'];
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}


$i=0;
if(isset($_GET["super"]) && $isadmin==1) {
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
$iij = $i % 2; 
if ($iij == 0) {
	echo '<div class="row">';
} else {
	echo '<div class="box">';
}
  echo $pagesl.'.QQ:<b><a href="index.php?mod=list&qq='.$myrow['qq'].$link.'">'.$myrow['qq'].'</a></b><img border="0" src="//wpa.qq.com/pa?p=9:'.$myrow['qq'].':4" alt="QQ状态" title="QQ状态"/>';
  echo '<br><a href="http://m.qzone.com/infocenter?sid='.$myrow['sid'].'&g_ut=3&g_f=6676" target="_blank">进入空间</a>.<a href="index.php?mod=dx&qq='.$myrow['qq'].'" target="_blank" style="color:red">单向好友检测</a>.<a href="index.php?mod=wall&q='.$myrow['qq'].'" target="_blank" style="color:grey">秒赞认证</a><br>状态:';
if ($myrow['status'] == '1')
echo '<font color="green">SID正常！</font>';
else
echo '<font color="red">SID已失效！</font>';
if ($myrow['status2'] == '1')
echo '<br/><font color="green">Skey正常！</font>';
else
echo '<br/><font color="red">Skey已失效！</font>';
echo '<br/>操作:<a href="index.php?mod=addqq&my=gxsid&qq='.$myrow['qq'].$link.'">更新SID&Skey</a>|<a href="index.php?mod=addqq&my=del&qq='.$myrow['qq'].$link.'">删除此QQ</a></div>';}


echo'<div class="row">';
$s = ceil($gls / $pagesize);
echo "共有" . $s . "页(" . $page . "/" . $s . ")<br>";
$pagea = $page - 1;
$pageb = $page + 1;
if ($page != 1) { 
echo'<a href="index.php?mod=qqlist&page='.$pagea.$link.'">上一页</a> ';
}
if($page!=$s){
echo'<a href="index.php?mod=qqlist&page='.$pageb.$link.'">下一页</a>';
}
if($opuser==1)
	echo '<form action="index.php" method="get"><input type="hidden" name="mod" value="qqlist"><input type="hidden" name="user" value="'.$gl.'"><input type="text" name="page" value="'.$page.'"><br><input type="submit" value="跳转"></form>'; 
else
	echo '<form action="index.php" method="get"><input type="hidden" name="mod" value="qqlist"><input type="text" name="page" value="'.$page.'"><br><input type="submit" value="跳转"></form>'; 
echo'</div>'; 
#分页


}
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);
}


echo'<div class="copy"><a href="index.php">返回首页</a>-<a href="index.php?mod=help">功能介绍</a>';
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</div></div></div></body></html>';
?>