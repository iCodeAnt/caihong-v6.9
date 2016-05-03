<?php
//注册文件
if(!defined('IN_CRONLITE'))exit();
$title="注册";
include_once(TEMPLATE_ROOT."head.php");
navi();

echo'<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">免费注册</h3></div>';

if($verifyswich==1)
$displyver='<div class="form-group"><label>验证码: </label><img title="点击刷新" src="verifycode.php" onclick="this.src=\'verifycode.php?\'+Math.random();"><br>
<input type="text" class="form-control" name="verify" value="" autocomplete="off" required></div>';
else $displyver='';
echo'<div class="panel-body"><form action="index.php?mod=reg" method="post"><input type="hidden" name="my" value="reg">
<div class="form-group">
<label>用户名:</label><br><input type="text" class="form-control" name="user" value="" required></div>
<div class="form-group">
<label>密码:</label><br><input type="password" class="form-control" name="pass" value="" required></div>
<div class="form-group">
<label>邮箱:</label><br><input type="email" class="form-control" name="email" value="" required></div>
'.$displyver.'
<input type="submit" class="btn btn-primary btn-block" value="确认注册"></form></div>';
echo'<div class="panel-heading"><h3 class="panel-title">最新注册用户:</h3></div>';
echo "<div class='panel-body'>";
$rsz = $DB->query("select * from ".DBQZ."_user order by userid desc limit 0,5");
while ($rowz = $DB->fetch($rsz)) {
$len = strlen($rowz["user"])/2;
$len = ceil($len);
$str=substr_replace($rowz["user"],str_repeat('*',$len),$len);
echo $str . "<br>";
}
echo'</div></div>';


echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo $txt[0];}
echo'</div></div></div></body></html>';
?>