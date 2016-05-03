<?php
 /*
　*　用户资料文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="用户资料";
include_once(TEMPLATE_ROOT."head.php");

navi();

echo'<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">用户资料</h3></div>';
echo'<div class="panel-body box">';
if($isvip==1)$vipstatus='到期时间:<font color="green">'.$row['vipdate'].'</font>';
elseif($isvip==2)$vipstatus='<font color="green">永久 VIP</font>';
else $vipstatus='<font color="red">非 VIP</font>';
echo'<li class="list-group-item"><b>UID：</b>'.$row['userid'].'</li>
<li class="list-group-item"><b>用户名：</b>'.$row['user'].'</li>
<li class="list-group-item"><b>用户组：</b>'.usergroup().'</li>
<li class="list-group-item"><b>注册日期：</b>'.$row['date'].'</li>
<li class="list-group-item"><b>'.$conf['coin_name'].'：</b><font color="red">'.$row['coin'].'</font> [<a href="index.php?mod=shop&kind=1">在线充值</a>]</li>
<li class="list-group-item"><b>VIP状态：</b>'.$vipstatus.' [<a href="index.php?mod=shop&kind=2">开通/续费VIP</a>]</li>
<li class="list-group-item"><b>邮箱：</b><font color="blue">'.$row['email'].'</font> [<a href="index.php?mod=set&my=mail">修改邮箱</a>]</li>
<li class="list-group-item"><b>密码：</b>********* [<a href="index.php?mod=set&my=mm">修改密码</a>]</li>
</div>';
echo'</div>';

echo'<div class="panel panel-primary"><div class="panel-body box" style="text-align: center;">';
echo date("Y年m月d日 H:i:s");
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</div>
</div>
</div></body></html>';
?>