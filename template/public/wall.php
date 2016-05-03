<?php
if(!defined('IN_CRONLITE'))exit();
$title="ＱＱ墙";
include_once(TEMPLATE_ROOT."head.php");

//判断上下线
function qqpanduan($p)
{
if(!strpos($p,':')){
    $p.=':';
}
$url='http://webpresence.qq.com/getonline?Type=1&'.$p;
$get=get_curl($url);
preg_match_all('/online\[(\d+)\]=(\d+)\;/ix',$get,$arr);
if($arr[2][0]==1){   
	return '在线';
}else{
	return '离线';
}
}
//获取QQ昵称雨峰博客
function getname($uin){
	if($data=file_get_contents("http://users.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins=".$uin)){
		$data=str_replace(array('portraitCallBack(',')'),array('',''),$data);
		$data=mb_convert_encoding($data, "UTF-8", "GBK");
		$row=json_decode($data,true);;
		return $row[$uin][6];
	}
}

if($theme=='default')echo '<div class="col-md-9" role="main">';

if(empty($_GET['q'])){
echo '
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">ＱＱ墙</h3>
	</div>
	<ul align="center" class="list-group" style="list-style:none;">
		<li class="list-group-item"><div class="wrapper2">
			<div id="menubar" class="fix-menu">
				<div class="menu-list">
';
$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE 1 order by id desc limit 24");
while($row = $DB->fetch($rs))
{
	$qq=$row['qq'];
	echo '<a href="index.php?mod=wall&q='.$qq.'" target="_blank"><img class="qqlogo" src="//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$qq.'&src_uin='.$qq.'&fid='.$qq.'&spec=100&url_enc=0&referer=bu_interface&term_type=PC" width="80px" height="80px" alt="'.$qq.'" title="'.$qq.'|添加时间:'.$row['time'].' ★点击查看详情★"></a>';
}
echo '</div></div>
			</div></li></ul>
</div>

<div class="panel panel-primary">
	<div class="panel-heading" style="background: #56892E;">
		<h3 class="panel-title" align="center">ＱＱ查询</h3>
	</div>
	<ul align="center" class="list-group" style="list-style:none;">
		<li class="list-group-item">
    <form action="index.php" method="get"><input type="hidden" name="mod" value="wall">
    请输入要查询的QQ号码:<input type="text" class="form-control" name="q"><br><input type="submit" class="btn btn-primary btn-block" value="查询">
    </form>
<br>
	</ul>
</div>
';
}
else
{
$q=$_GET['q'];
$name=getname($q);
//$panduan=qqpanduan($q);
echo '
<div class="panel panel-primary" style="max-width:680px;">
	<div class="panel-heading" style="background: #56892E;">
		<h3 class="panel-title" align="center">'.$name.'的信息</h3>
	</div>
	<ul align="center" class="list-group" style="list-style:none;">
		<li class="list-group-item"><b><font color=green>QQ：'.$q.'</b></font><li>
		<li class="list-group-item"><b><font color=bule>昵称：'.$name.'</b></font><li>
		<li class="list-group-item"><b><font color=red>状态：<img src="http://wpa.qq.com/pa?p=9:'.$q.':5"></b></font><li>
		<li class="list-group-item"><img src="http://q1.qlogo.cn/g?b=qq&nk='.$q.'&s=100&t='.time().'"><li>
		<li class="list-group-item"><img src="http://qqshow-user.tencent.com/'.$q.'/22/00/1.gif?fr=mobileqq"><li>
		<li class="list-group-item"><a class="btn btn-s-md btn-info btn-rounded btn-block" value="发送消息" target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin='.$q.'&amp;site=qq&amp;menu=yes">发送消息</a><li>
		<li class="list-group-item"><a class="btn btn-s-md btn-info btn-rounded btn-block" value="加为好友" target="_blank" href="tencent://AddContact/?fromId=45&fromSubId=1&subcmd=all&uin='.$q.'&website">加为好友</a><li>
		<li class="list-group-item"><a class="btn btn-s-md btn-primary btn-rounded btn-block" value="浏览QQ空间" target="_blank" href="http://user.qzone.qq.com/'.$q.'">浏览QQ空间</a><li>
<li class="list-group-item"><a class="btn btn-s-md btn-info btn-rounded btn-block" value="★返回主页★" href="index.php?mod=wall">★返回ＱＱ墙★</a><li>
	</ul>
</div>';
}
?>
</div>