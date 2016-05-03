<?php
$mod=isset($_GET['mod'])?$_GET['mod']:'home';

//界面切换
$uaid=isset($_GET['v'])?$_GET['v']:null;
if($uaid) {
	if($uaid==1)
		setcookie("uachar", "mobile", time()+2592000);
	elseif($uaid==2)
		setcookie("uachar", "default", time()+2592000);
	header("Location:index.php");
}

include("./includes/common.php");
?>