<?php
 /*
　*　自动更新文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="检查更新";
include_once(TEMPLATE_ROOT."head.php");

$checkurl=$appid.'?url='.$_SERVER['HTTP_HOST'].'&authcode='.$authcode.'&ver='.VERSION;

if ($isadmin==1)
{

//函数
function zipExtract ($src, $dest)
{
$zip = new ZipArchive();
if ($zip->open($src)===true)
{
$zip->extractTo($dest);
$zip->close();
return true;
}
return false;
}

navi();

echo '<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">检查更新</h3></div><div class="panel-body box">';

$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
default:

$res=get_curl($checkurl);
$res=json_decode($res,true);

if(!$res['msg'])$res['msg']='啊哦，更新服务器开小差了，请刷新此页面。';


echo '<div class="alert alert-info">'.$res['msg'].'</div>';
echo '<hr/>';

if($res['code']==1) {
if(!class_exists('ZipArchive') || ini_get('acl.app_id') || defined("SAE_ACCESSKEY")) {
echo '您的空间不支持自动更新，请手动下载更新包并覆盖到程序根目录！<br/>
更新包下载：<a href="'.$res['file'].'" class="btn btn-primary">update.zip</a>';
} else {
echo '<a href="index.php?mod=update&act=do" class="btn btn-primary btn-block">立即更新到最新版本</a>';
}

echo '<hr/><div class="well">'.$res['uplog'].'</div>';
}
break;

case 'do':
if(isset($_GET['test']))$checkurl='http://cron.aliapp.com/api/check.php?test=true&ver='.VERSION;
$res=get_curl($checkurl);
$res=json_decode($res,true);
$RemoteFile = $res['file'];
$ZipFile = "Archive.zip";
copy($RemoteFile,$ZipFile) or die("无法下载更新包文件！".'<a href="index.php?mod=update">返回上级</a>');
if (zipExtract($ZipFile,ROOT)) {
echo "程序更新成功！<br>";
echo '<a href="./">返回首页</a>';
unlink($ZipFile);
}
else {
echo "无法解压文件！<br>";
echo '<a href="index.php?mod=update">返回上级</a>';
if (file_exists($ZipFile))
unlink($ZipFile);
}
break;
}
echo '</div></div>';

}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></div></body></html>';
?>