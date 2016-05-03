<?php
/*
　*　登录文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="登录";
include_once(TEMPLATE_ROOT."head.php");

navi();

showlogin();

echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
include(ROOT."includes/foot.php");
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo"$txt[0]";}
echo'</body></html>';
?>