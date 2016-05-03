<?php
if(!defined('IN_CRONLITE'))exit();
$title="首页";
include_once(TEMPLATE_ROOT."head.php");

navi();

$gg=$conf['gg'];
if(!empty($gg))
echo'<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title" align="center">公告栏</h3></div><div class="panel-body">'.$gg.'</font></div>
</div>';
showlogin();
?>

<?php
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title" align="center"><a href="index.php?mod=wall">ＱＱ展示</a></h3></div>
<ul align="center" class="list-group" style="list-style:none;">
	<li class="list-group-item"><div class="wrapper2">
		<div id="menubar" class="fix-menu">
			<div class="menu-list">
';
$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE 1 order by id desc limit 15");
while($row = $DB->fetch($rs))
{
	$qq=$row['qq'];
	echo '<a href="search.php?q='.$qq.'" target="_blank"><img class="qqlogo" src="//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$qq.'&src_uin='.$qq.'&fid='.$qq.'&spec=100&url_enc=0&referer=bu_interface&term_type=PC" width="80px" height="80px" alt="'.$qq.'" title="'.$qq.'|添加时间:'.$row['time'].' ★点击查看详情★"></a>';
}
echo '</div></div>
			</div></li></ul>
</div>';

##交流社区start
$row12=$DB->get_row("select * from ".DBQZ."_chat order by id desc limit 1");
echo'<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title" align="center"><a href="index.php?mod=chat">交流社区</a></h3></div>';
echo '<div class="panel-body">'; 
if($row12['nr']==''){
echo '还没有友友说话哦 <a href="index.php?mod=chat">聊天</a>';
}else{
if($row12['user']==$gl){ 
echo'<a href="index.php?mod=chat&to='.$row12['user'].'">我</a>';
}else{
echo'<a href="index.php?mod=chat&to='.$row12['user'].'">'.$row12['user'].'</a>';
}
$n=$row12['nr'];
$n = htmlspecialchars($n, ENT_QUOTES);
echo' 说:'.$n.'('.$row12['sj'].') <a href="index.php?mod=chat">聊天</a>';
}
echo'</div></div>';
##交流社区end

echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">运行日志:&nbsp&nbsp<a href="index.php?mod=all">详细>></a></h3></div><div class="panel-body">系统共有<font color="#ff0000">'.$zongs.'</font>条任务<br>共有<font color="#ff0000">'.$qqs.'</font>个QQ正在挂机<br>系统累计运行了<font color="#ff0000">'.$info['times'].'</font>次<br>上次运行:<font color="#ff0000">'.$info['last'].'</font><br>当前时间:<font color="#ff0000">'.$date.'</font></div></div>';
//echo'<div class="panel panel-primary">
//<div class="panel-heading"><h3 class="panel-title">数据统计:</h3></div>';
//echo'<div class="panel-body">';
//include(ROOT.'includes/content/tongji.php');
//echo'</div></div>';

$guang=$conf['guang'];
if(!empty($guang))
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">强力推荐</h3></div><div class="panel-body">'.$guang.'</div></div>';
echo '<div class="panel panel-primary"><div class="panel-body" style="text-align: center;">';
echo '界面切换:触屏/电脑版.<a href="index.php?v=1">手机炫彩版</a><br>';
echo $conf['bottom'];
echo '<br>';
$week=array("天","一","二","三","四","五","六");
echo date("Y年m月d日 H:i:s").' 星期'.$week[date("w")]; 
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo $txt[0];}
echo'</div>
</div>
</div>
</div></body></html>
';
?>