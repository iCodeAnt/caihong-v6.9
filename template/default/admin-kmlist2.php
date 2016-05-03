<?php
if(!defined('IN_CRONLITE'))exit();
$title="代理卡密列表";
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

if ($isdaili==1)
{
$kind=isset($_GET['kind'])?$_GET['kind']:null;

if($my==null){

if($kind==1) {
	$sql=" kind='1' and daili='{$row['userid']}'";
	$link='&kind='.$kind;
	$name='充值卡';
	$addsm='每个卡密面额';
} elseif($kind==2) {
	$sql=" kind='2' and daili='{$row['userid']}'";
	$link='&kind='.$kind;
	$name='VIP卡';
	$addsm='每个卡密开通月数(0为永久)';
}

$numrows = $DB->count("select count(*) from ".DBQZ."_kms where".$sql);

if($kind==1) {
	echo '<ul class="nav nav-tabs">
	<li><a href="index.php?mod=admin-daili">代理后台</a></li>
	  <li class="active"><a href="#">充值卡卡密</a></li>
	  <li><a href="index.php?mod=admin-kmlist2&kind=2">VIP卡卡密</a></li>
</ul>';
echo '<h3>充值卡卡密列表 ('.$numrows.')</h3>';
echo '<form action="index.php?mod=admin-kmlist2&my=add" method="POST" class="form-inline"><input type="hidden" name="kind" value="'.$kind.'">
  <div class="form-group">
    <label>充值卡卡密生成</label>
    <input type="text" class="form-control" name="num" placeholder="生成的个数">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="'.$addsm.'">
  </div>
  <button type="submit" class="btn btn-primary">生成</button>（1RMB＝'.$daili_rules[0].$conf['coin_name'].'）
</form>';
} elseif($kind==2) {
	echo '<ul class="nav nav-tabs">
	<li><a href="index.php?mod=admin-daili">代理后台</a></li>
	  <li><a href="index.php?mod=admin-kmlist2&kind=1">充值卡卡密</a></li>
	  <li class="active"><a href="#">VIP卡卡密</a></li>
</ul>';
echo '<h3>VIP卡卡密列表 ('.$numrows.')</h3>';
echo '<form action="index.php?mod=admin-kmlist2&my=add" method="POST" class="form-inline"><input type="hidden" name="kind" value="'.$kind.'">
  <div class="form-group">
    <label>VIP卡卡密生成</label>
	<input type="text" class="form-control" name="num" placeholder="生成的个数">
  </div>
  <div class="form-group">
    <select name="value" class="form-control">
			<option value="1">VIP月卡('.$daili_rules[1].'元)</option>
			<option value="3">VIP季度卡('.$daili_rules[2].'元)</option>
			<option value="6">VIP半年卡('.$daili_rules[3].'元)</option>
			<option value="12">VIP年卡('.$daili_rules[4].'元)</option>
			<option value="0">VIP永久卡('.$daili_rules[5].'元)</option>
	</select>
  </div>
  <button type="submit" class="btn btn-primary">生成</button>
</form>';
}



echo '※<a href="http://www.917ka.com/" target="_blank">自动卡密发卡平台</a><br/>';

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

if($kind==1) {
	$value='<font color="blue">'.$myrow['value'].'</font> '.$conf['coin_name'];
} elseif($kind==2) {
	if($myrow['value']==0)$myrow['value']='无限';
	$value='<font color="blue">'.$myrow['value'].'</font> 个月';
}
if($myrow['isuse']==1) {
	$isuse='<font color="red">已使用</font><br/>使用者:'.$myrow['user'];
	$usetime='<br>使用时间:<font color="blue">'.$myrow['usetime'].'</font>';
} elseif($myrow['isuse']==0) {
	$isuse='<font color="green">未使用</font>';
	$usetime=null;
}

echo '<tr><td style="width:30%;"><b>'.$myrow['km'].'</b><br/>'.$name.'：'.$value.'</td><td style="width:20%">'.$isuse.'</td><td style="width:30%">生成时间:<font color="blue">'.$myrow['addtime'].'</font>'.$usetime.'</td><td style="width:20%"><a href="index.php?mod=admin-kmlist2&my=del&id='.$myrow['id'].$link.'" class="btn btn-danger btn-sm">删除</a></td></tr>';
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
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=admin-kmlist2&page='.$last.$link.'">尾页</a></li>';
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

if($value<0 || $num<0){
	showmsg('卡密面值不能为负值！',3);exit;
}
if($kind==1)
	$rmb=ceil(($num*$value)/$daili_rules[0]);
else{
	if($value==1)$rmb=$num*$daili_rules[1];
	elseif($value==3)$rmb=$num*$daili_rules[2];
	elseif($value==6)$rmb=$num*$daili_rules[3];
	elseif($value==12)$rmb=$num*$daili_rules[4];
	elseif($value==0)$rmb=$num*$daili_rules[5];
}
if($rmb>$row['daili_rmb']) {
	showmsg('您的账户余额不足，请联系站长充值！',3);exit;
} else {
$DB->query("update ".DBQZ."_user set daili_rmb=daili_rmb-{$rmb} where user='".$gl."'");
}

echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
for ($i = 0; $i < $num; $i++) {
	$km=getkm(12);
	$sql=$DB->query("insert into `".DBQZ."_kms` (`kind`,`daili`,`km`,`value`,`addtime`) values ('".$kind."','".$row['userid']."','".$km."','".$value."','".$date."')");
	if($sql) {
		echo "<li class='list-group-item'>$km</li>";
	}
}
echo '</ul></div><a href="index.php?mod=admin-kmlist2&kind='.$kind.'">>>返回卡密列表</a>';
}

elseif($my=='del'){
echo '<div class="w h">删除卡密</div><div class="box">';
$id=$_GET['id'];
$kind=$_GET['kind'];
$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_kms WHERE id='$id'");
if($myrow){
if($myrow['isuse']==0){
	if($myrow['kind']==1)
		$rmb=ceil($myrow['value']/$daili_rules[0]);
	else{
		if($myrow['value']==1)$rmb=$daili_rules[1];
		elseif($myrow['value']==3)$rmb=$daili_rules[2];
		elseif($myrow['value']==6)$rmb=$daili_rules[3];
		elseif($myrow['value']==12)$rmb=$daili_rules[4];
		elseif($myrow['value']==0)$rmb=$daili_rules[5];
	}
	$msg='由于该卡密没有使用，成功退还'.$rmb.'元到你账户！';
}
$sql=$DB->query("DELETE FROM ".DBQZ."_kms WHERE id='$id'");
if($sql){$DB->query("update ".DBQZ."_user set daili_rmb=daili_rmb+{$rmb} where user='".$gl."'");
echo '删除卡密成功！'.$msg;}
else{echo '删除失败！';}
}else{echo '该卡密不存在！';}
echo '</div><a href="index.php?mod=admin-kmlist2&kind='.$kind.'">>>返回卡密列表</a>';
}

}
else
{
showmsg('代理后台登录失败。请以代理身份 <a href="index.php?mod=login&daili=1">重新登录</a>，或联系站长购买代理身份！',3);
}
echo'<br><div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></body></html>';
?>