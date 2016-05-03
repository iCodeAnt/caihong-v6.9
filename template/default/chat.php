<?php 
/*
　*　聊天室文件
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="聊天室";
include_once(TEMPLATE_ROOT."head.php");

/****发言限制设定****/
$timelimit = 600; //时间周期(秒)
$iplimit = 3; //相同IP在1个时间周期内限制发言的次数
if($islogin==1)$verifyswich=0; //验证码开关
else $verifyswich=1;

$user=$gl?$gl:null;
$id=isset($_GET['id'])?intval($_GET['id']):null;
$idp=isset($_GET['idp'])?intval($_GET['idp']):null;
$nr=isset($_POST['sql'])?daddslashes(strip_tags($_POST['sql'])):null;
$to=isset($_GET['to'])?daddslashes(strip_tags($_GET['to'])):daddslashes(strip_tags($_POST['to']));
$verifycode=daddslashes($_POST['verify']);

navi();

echo'<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">
<a href="index.php?mod=chat">交流社区</a></h3></div><div class="panel-body">
'; 
?>
<script>

</script>
<?php
if ($isadmin==1) { 
if($id!=''){$DB->query("DELETE FROM ".DBQZ."_chat WHERE id='$id'"); 
echo '<div class="alert alert-success" role="alert"><strong>删除成功!</strong></div>';
}
if($idp!=''){$DB->query("update `".DBQZ."_chat` set `nr` ='此楼层已被和谐。。' where `id`='$idp'"); 
echo '<div class="alert alert-success" role="alert"><strong>屏蔽成功!</strong></div>';
}
} 
if($user==''){
$usera="游客";
}else{
$usera=$user;
}
if($nr==''){}else{ 
	if($verifyswich==1 &&(!$verifycode || $verifycode!=$_SESSION['verifycode'])){
		showmsg('验证码不正确！');
		exit();
	}
	$row22 = $DB->get_row("SELECT * FROM ".DBQZ."_chat WHERE user='$user' and nr='$nr' LIMIT 1");
	if ($row22!='') {
		showmsg('不要发重复内容哦！');
		exit();
	}

	$timelimits=date("Y-m-j H:i:s ",TIMESTAMP+$timelimit);
	$ipcount=$DB->count("SELECT count(*) FROM ".DBQZ."_chat WHERE `sj`<'$timelimits' and `ip`='$clientip' limit ".$iplimit);
	if($ipcount>=$iplimit) {	
		echo'<div class="box">你的发言速度太快了，请休息一下稍后重试。<br>3秒后自动返回... <meta http-equiv="refresh" content="3;url=index.php?mod=chat"> <a href="index.php?mod=chat">点此手动返回</a></div>';
		foot();
		exit();
	}

$sql="insert into `".DBQZ."_chat` (`user`,`nr`,`sj`,`to`,`ip`) values ('".$usera."','".$nr."','".$date."','".$to."','".$clientip."')";
if($DB->query($sql)){
unset($_SESSION['verifycode']);
 echo '<div class="alert alert-success" role="alert"><strong>发送成功!</strong></div>';
}else{ echo '<div class="alert alert-danger" role="alert"><strong>发送失败!</strong></div>';
} 
}

echo'亲爱的';
if($user==''){
$usera="游客";
}else{
$usera=$user;
}
echo''.$usera.'，请和谐交流哦！<hr/>';
if($user==$to){ 	
if ($user!='') {
			echo '你想自言自语:<br>';
		}
$to='';
}else{
if($to==''){}else{
echo '你对'.$to.'说:<br>';
}
}
?>
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">UBB使用说明</h4>
      </div>
      <div class="modal-body">
<br>横线:[hr]  换行:[br]
<br>呲牙:[cy]  愤怒:[fn]  尴尬:[gg]  坏笑:[hx]
<br>可爱:[ka]  可怜:[kl]  流泪:[ll]  色:[se]
<br>委屈:[wq] 微笑:[wx]  吓:[xia]  晕:[yun]
<br>链接:[url=http://链接地址]名称[/url]
<br>图片:[img]http://图片地址[/img]
<br>移动文字:[move]内容[/move]
<br>彩色文字:[color=颜色名]文字[/color]
<br><hr>颜色代码如:<br><font color=green>green</font>,<font color=red>red</font>,<font color=brown> brown</font>,<font color=#CCC00> #CCC00</font>,<font color=#66CCCC>#66CCCC</font> <a href=http://tool.c7sky.com/webcolor>更多</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
if($verifyswich==1)
$displyver='验证码: <img title="点击刷新" src="verifycode.php" onclick="this.src=\'verifycode.php?\'+Math.random();"><br>
<input type="text" name="verify" value="" autocomplete="off">（<a href="index.php?mod=login">登录</a>后可免除验证码）<br>';
else $displyver='';
echo'<form action="index.php?mod=chat" method="post"><input type="hidden" name="to" value="'.$to.'"><textarea name="sql" class="form-control" rows="3"></textarea><br>'.$displyver.'<button type="submit" class="btn btn-primary btn-block">发送</button><br/>';
echo'<p><a data-toggle="modal" data-target="#myModal" class="btn btn-info btn-block">查看UBB使用说明</a></p>
';
$pagesize=15;
$numrows = $DB->count("select count(*) from ".DBQZ."_chat where 1");
$pages=ceil($numrows/$pagesize);
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

$chatcont=$DB->query("select * from ".DBQZ."_chat order by id desc limit $offset,$pagesize");
$i=0;
while($myrow=$DB->fetch($chatcont)){ 
$i++;

echo '<div class="well">';

if($myrow['user']==$user){
echo'<a href="index.php?mod=chat&to='.$myrow['user'].'">我</a>';
}else{
echo'<a href="index.php?mod=chat&to='.htmlspecialchars($myrow['user'], ENT_QUOTES).'">'.htmlspecialchars($myrow['user'], ENT_QUOTES).'</a>';
}
if($myrow['to']==''){
}else{
echo' 对';
if($myrow['to']==$user){
echo'<a href="index.php?mod=chat&to='.$myrow['to'].'">【我】</a>';
}else{
echo'<a href="index.php?mod=chat&to='.htmlspecialchars($myrow['to'], ENT_QUOTES).'">【'.htmlspecialchars($myrow['to'], ENT_QUOTES).'】</a>';
}
}

echo'说:';
$n=$myrow['nr']; 
$n = htmlspecialchars($n, ENT_QUOTES); 
if(preg_match('[img]',$n))
{
$n = preg_replace("/\[img\](.+?)\[\/img\]/is","<img src='\\1'>",$n); 
}
if(preg_match('[cy]',$n))
{
$n = @ereg_replace("\[cy\]","<img src='./images/face/ciya.gif'>",$n);
} 
if(preg_match('[fn]',$n))
{
$n = @ereg_replace("\[fn\]","<img src='./images/face/fennu.gif'>",$n);
}
 if(preg_match('[gg]',$n))
{
$n = @ereg_replace("\[gg\]","<img src='./images/face/ganga.gif'>",$n);
} 
if(preg_match('[hx]',$n))
{
$n = @ereg_replace("\[hx\]","<img src='./images/face/huaixiao.gif'>",$n);
} 
if(preg_match('[ka]',$n))
{
$n = @ereg_replace("\[ka\]","<img src='./images/face/keai.gif'>",$n);
}
 if(preg_match('[kl]',$n))
{
$n = @ereg_replace("\[kl\]","<img src='./images/face/kelian.gif'>",$n);
}
 if(preg_match('[ll]',$n))
{
$n = @ereg_replace("\[ll\]","<img src='./images/face/liulei.gif'>",$n);
}
 if(preg_match('[se]',$n))
{
$n = @ereg_replace("\[se\]","<img src='./images/face/se.gif'>",$n);
} 
 if(preg_match('[wq]',$n))
{
$n = @ereg_replace("\[wq\]","<img src='./images/face/weiqu.gif'>",$n);
}
 if(preg_match('[wx]',$n))
{
$n = @ereg_replace("\[wx\]","<img src='./images/face/weixiao.gif'>",$n);
}
 if(preg_match('[xia]',$n))
{
$n = @ereg_replace("\[xia\]","<img src='./images/face/xia.gif'>",$n);
}
 if(preg_match('[yun]',$n))
{
$n = @ereg_replace("\[yun\]","<img src='./images/face/yun.gif'>",$n);
}
if(preg_match('[br]',$n))
{
$n = @ereg_replace("\[br\]","<br />",$n);
}
if(preg_match('[hr]',$n))
{
$n = @ereg_replace("\[hr\]","<hr />",$n);
}
if(preg_match('[color]',$n))
{
$n = preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\"\\1\">\\2</font>",$n);
}
if(preg_match('[url]',$n))
{
$n=preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is","<u><a href='\\1' target='_blank'>\\2</a></u>",$n);
$n=preg_replace("/\[url\](http:\/\/.+?)\[\/url\]/is","<u><a href='\\1' target='_blank'>\\1</a></u>",$n); 
}
if(preg_match('[move]',$n))
{
$n=preg_replace("/\[move\](.+?)\[\/move\]/is","<marquee width=\"98%\" scrollamount=\"3\">\\1</marquee>",$n);
} 
echo $n;
echo'('.$myrow['sj'].')';
if($isadmin==1){
echo '['.$myrow['ip'].'|<a href="index.php?mod=chat&id='.$myrow['id'].'">删除</a>|<a href="index.php?mod=chat&idp='.$myrow['id'].'">屏蔽</a>]';
}
echo'</div>';
}

echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="index.php?mod=chat&page='.$first.'">首页</a></li>';
echo '<li><a href="index.php?mod=chat&page='.$prev.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=chat&page='.$i.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
if($pages>=10)$pages=10;
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?mod=chat&page='.$i.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?mod=chat&page='.$next.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=chat&page='.$last.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页

include(ROOT.'includes/foot.php');
echo'</div></div></div>';
echo'</body></html>';
?>