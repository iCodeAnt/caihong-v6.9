<?php
 /*
　*　待打码QQ列表
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="待打码QQ列表";
include_once(TEMPLATE_ROOT."head.php");


if($islogin==1){

$gls=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE status='4' or status2='4'");

echo '<div class="col-md-9" role="main">';
echo '<ul class="nav nav-tabs">
	  <li class="" ><a href="index.php?mod=qqlist">ＱＱ管理</a></li>
	  <li class="active" ><a href="#">协助打码</a></li>
	  <li class="" ><a href="index.php?mod=wall">ＱＱ墙</a></li>
</ul>';
echo '<div class="alert alert-info">★共有 <font color="red">'.$gls.'</font> 个QQ账号等待打码！<br/>
你目前拥有的虚拟币：'.$row['coin'].'</div>';
echo '
<button href="#" class="btn btn-default" data-toggle="modal" data-target="#help">打码说明</button>
';

$pagesize=$conf['pagesize'];
if (!isset($_GET['page'])) {
	$page = 1;
	$pageu = $page - 1;
} else {
	$page = $_GET['page'];
	$pageu = ($page - 1) * $pagesize;
}

?>

<div class="modal fade" align="left" id="help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">打码使用说明</h4>
      </div>
      <div class="modal-body">
在这里，你可以帮助网站里的其它友友更新SID&SKEY，同时，你也会得到一定的虚拟币奖励！<br/>
奖励规则：每成功协助打码一次送 <font color="red"><?php echo $rules[5].'</font> '.$conf['coin_name'] ?>。
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>td,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>thead>tr>th{white-space: pre-wrap;}
</style>
<div class="table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>QQ账号</th>
			<th>状态/操作</th>
		</tr>
	</thead>
	<tobdy>
<?php
$i=0;
$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE status='4' or status2='4' order by id desc limit $pageu,$pagesize");
while($myrow = $DB->fetch($rs))
{
$i++;
$pagesl = $i + ($page - 1) * $pagesize;
  echo '<tr><td style="width:50%;"><b><a href="search.php?qq='.$myrow['qq'].'">'.$myrow['qq'].'</a></b><img border="0" src="http://wpa.qq.com/pa?p=9:'.$myrow['qq'].':4" alt="QQ状态" title="QQ状态"/>';
  echo '</td><td style="width:50%">';
if ($myrow['status'] == '1')
echo '<font color="green">SID正常！</font>';
else
echo '<font color="red">SID已失效！</font>';
if ($myrow['status2'] == '1')
echo '<br/><font color="green">Skey正常！</font>';
else
echo '<br/><font color="red">Skey已失效！</font>';
echo '<br/><a href="index.php?mod=addqq&my=dama&qq='.$myrow['qq'].'" class="btn btn-success btn-sm">协助打码</a></td></tr>';}

?>
	</tbody>
</table>
</div>

<?php

echo'<ul class="pagination">';
$s = ceil($gls / $pagesize);
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$s;
if ($page>1)
{
echo '<li><a href="index.php?mod=dama&page='.$first.$link.'">首页</a></li>';
echo '<li><a href="index.php?mod=dama&page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="index.php?mod=dama&page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="index.php?mod=dama&page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$s)
{
echo '<li><a href="index.php?mod=dama&page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="index.php?mod=dama&page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页


}
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);
}


include(ROOT.'includes/foot.php');

if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</div></div></div></body></html>';
?>