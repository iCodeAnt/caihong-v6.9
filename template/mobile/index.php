<?php
if(!defined('IN_CRONLITE'))exit();
$title="首页";
include_once(TEMPLATE_ROOT."head.php");

navi();

$gg=$conf['gg'];
if(!empty($gg))
echo'<div class="w h">公告栏</div><div class="box">'.$gg.'</div>';
showlogin();
echo '<div class="w h">运行日志:&nbsp&nbsp<a href="index.php?mod=all">详细>></a></div><div class="box">系统共有<font color="#ff0000">'.$zongs.'</font>条任务<br>共有<font color="#ff0000">'.$qqs.'</font>个QQ正在挂机<br>系统累计运行了<font color="#ff0000">'.$info['times'].'</font>次<br>上次运行:<font color="#ff0000">'.$info['last'].'</font><br>当前时间:<font color="#ff0000">'.$date.'</font></div>';
//echo'<div class="w h">数据统计:</div>';
//echo'<div class="box">';
//include(ROOT.'includes/content/tongji.php');
//echo'</div>';

##交流社区start
$row12=$DB->get_row("select * from ".DBQZ."_chat order by id desc limit 1");
echo'<div class="w h"><a href="index.php?mod=chat">交流社区</a></div>';
echo '<div class= "box">'; 
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
echo'</div>';
##交流社区end

$guang=$conf['guang'];
if(!empty($guang))
echo'<div class="w h">强力推荐</div><div class="box">'.$guang.'</div>';
echo'<div class="copy">';
echo $conf['bottom'];
echo '<hr>界面切换:<a href="index.php?v=2">触屏/电脑版</a>.手机炫彩版<br>';
$week=array("天","一","二","三","四","五","六");
echo date("Y年m月d日 H:i:s").' 星期'.$week[date("w")]; 
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</body></html>';
?>