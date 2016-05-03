<?php
if(!defined('IN_CRONLITE'))exit();
$title="卡密列表";
include_once(TEMPLATE_ROOT."head.php");

function getkm($len = 12)
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$strlen = strlen($str);
	$randstr = "";
	for ($i = 0; $i < $len; $i++) {
		$randstr .= $str[mt_rand(0, $strlen - 1)];
	}
	return $randstr;
}

$my=isset($_GET['my'])?$_GET['my']:null;
echo '<div class="col-md-9" role="main">';

if ($isadmin==1)
{
$kind=isset($_GET['kind'])?$_GET['kind']:null;

if($my==null){

if(isset($_GET['km'])) {
	$sql=" km='{$_GET['km']}'";
}
elseif($kind==1) {
	$sql=" kind='1'";
	$link='&kind='.$kind;
	$name='充值卡';
	$addsm='每个卡密面额';
} elseif($kind==2) {
	$sql=" kind='2'";
	$link='&kind='.$kind;
	$name='VIP卡';
	$addsm='每个卡密开通月数(0为永久)';
} elseif($kind==3) {
	$sql=" kind='3'";
	$link='&kind='.$kind;
	$name='试用卡';
	$addsm='每个卡密开通天数';
}

$numrows = $DB->count("select count(*) from ".DBQZ."_kms where".$sql);

if($kind==1) {
	echo '<ul class="nav nav-tabs">
	  <li class="active"><a href="#">充值卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=2">VIP卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=3">试用卡卡密</a></li>
</ul>';
} elseif($kind==2) {
	echo '<ul class="nav nav-tabs">
	  <li><a href="index.php?mod=admin-kmlist&kind=1">充值卡卡密</a></li>
	  <li class="active"><a href="#">VIP卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=3">试用卡卡密</a></li>
</ul>';
} elseif($kind==3) {
	echo '<ul class="nav nav-tabs">
	  <li><a href="index.php?mod=admin-kmlist&kind=1">充值卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=2">VIP卡卡密</a></li>
	  <li class="active"><a href="#">试用卡卡密</a></li>
</ul>';
} else {
	echo '<ul class="nav nav-tabs">
	  <li><a href="index.php?mod=admin-kmlist&kind=1">充值卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=2">VIP卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist&kind=3">试用卡卡密</a></li>
</ul>';
}

echo '<h3>'.$name.'卡密列表 ('.$numrows.')</h3>';

if(isset($addsm))
echo '<form action="index.php?mod=admin-kmlist&my=add" method="POST" class="form-inline"><input type="hidden" name="kind" value="'.$kind.'">
  <div class="form-group">
    <label>'.$name.'卡密生成</label>
    <input type="text" class="form-control" name="num" placeholder="生成的个数">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="'.$addsm.'">
  </div>
  <button type="submit" class="btn btn-primary">生成</button>
  <a href="index.php?mod=admin-kmlist&my=qk&kind='.$kind.'" class="btn btn-danger">清空</a>
</form>※<a href="http://www.917ka.com/" target="_blank">自动卡密发卡平台</a><br/>';

$pagesize=$conf['pagesize'];
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

?>
<style>
.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space: pre-wrap;}
</style>
<div class="panel panel-default table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>卡密</th>
			<th>状态</th>
			<th>时间记录</th>
			<th>操作</th>
		</tr>
	</thead>
	<tobdy>
<?php

$rs=$DB->query("select * from ".DBQZ."_kms where{$sql} order by id desc limit $offset,$pagesize");
$i=0;
while ($myrow = $DB->fetch($rs))
{
$i++;
$pagesl=$i+($page-1)*$pagesize;
$kind=$myrow['kind'];
if($kind==1) {
	$value='<font color="blue">'.$myrow['value'].'</font> '.$conf['coin_name'];
	$name='充值卡';
} elseif($kind==2) {
	if($myrow['value']==0)$myrow['value']='无限';
	$value='<font color="blue">'.$myrow['value'].'</font> 个月';
	$name='VIP卡';
} elseif($kind==3) {
	$value='<font color="blue">'.$myrow['value'].'</font> 天';
	$name='试用卡';
}
if($myrow['isuse']==1) {
	$isuse='<font color="red">已使用</font><br/>使用者:'.$myrow['user'];
	$usetime='<br>使用时间:<font color="blue">'.$myrow['usetime'].'</font>';
} elseif($myrow['isuse']==0) {
	$isuse='<font color="green">未使用</font>';
	$usetime=null;
}

echo '<tr><td style="width:30%;"><b>'.$myrow['km'].'</b><br/>'.$name.'：'.$value.'</td><td style="width:20%">'.$isuse.'</td><td style="width:30%">生成时间:<font color="blue">'.$myrow['addtime'].'</font>'.$usetime.'</td><td style="width:20%"><a href="index.php?mod=admin-kmlist&my=del&id='.$myrow['id'].$link.'" class="btn btn-danger btn-sm">删除</a></td></tr>';
}
?>
	</tbody>
</table>
</div>

<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="index.php?mod=admin-kmlist&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=admin-kmlist&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=admin-kmlist&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?mod=admin-kmlist&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?mod=admin-kmlist&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=admin-kmlist&page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}

elseif($my=='add'){
echo '<div class="w h">生成卡密</div><div class="box">';
$kind=intval($_POST['kind']);
$num=intval($_POST['num']);
$value=intval($_POST['value']);
echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
for ($i = 0; $i < $num; $i++) {
	$km=getkm(12);
	$sql=$DB->query("insert into `".DBQZ."_kms` (`kind`,`km`,`value`,`addtime`) values ('".$kind."','".$km."','".$value."','".$date."')");
	if($sql) {
		echo "<li class='list-group-item'>$km</li>";
	}
}

echo '</ul></div><a href="index.php?mod=admin-kmlist&kind='.$kind.'">>>返回卡密列表</a>';
}

elseif($my=='del'){
echo '<div class="w h">删除卡密</div><div class="box">';
$id=$_GET['id'];
$kind=$_GET['kind'];
$sql=$DB->query("DELETE FROM ".DBQZ."_kms WHERE id='$id'");
if($sql){echo '删除成功！';}
else{echo '删除失败！';}
echo '</div><a href="index.php?mod=admin-kmlist&kind='.$kind.'">>>返回卡密列表</a>';
}

elseif($my=='qk'){//清空卡密
$kind=$_GET['kind'];
echo '<div class="box">您确认要清空所有卡密吗？清空后无法恢复！<br><a href="index.php?mod=admin-kmlist&my=qk2&kind='.$kind.'">确认</a> | <a href="javascript:history.back();">返回</a></div>';
}
elseif($my=='qk2'){//清空卡密结果
$kind=$_GET['kind'];
if($DB->query("DELETE FROM ".DBQZ."_kms WHERE kind='$kind'")==true){
echo '<div class="box">清空成功，</div>';
}else{
echo'<div class="box">清空失败，</div>';
}
echo '</div><a href="index.php?mod=admin-kmlist&kind='.$kind.'">>>返回卡密列表</a>';
}

}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<br><div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></body></html>';
?>