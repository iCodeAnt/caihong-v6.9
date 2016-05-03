<?php
if(!defined('IN_CRONLITE'))exit();
include ROOT.'includes/SEO.php';

$zongs=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE 1");
$users=$DB->count("SELECT count(*) from ".DBQZ."_user WHERE 1");
$qqs=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");

$sid=isset($_COOKIE["sid"])?$_COOKIE["sid"]:null;
$css=$conf['css'];
$sitename=$conf['sitename'];

if($title=='首页' || empty($title))
$titlename=$sitename.' '.$conf['sitetitle'];
else
$titlename=$title.'|'.$sitename.' '.$conf['sitetitle'];

echo '<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="keywords" content="'.$SEO_keywords.'" />
<meta name="description" content="'.$SEO_description.'" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;  minimum-scale=1.0; maximum-scale=2.0"/>
<link rel="stylesheet" type="text/css" href="style/css'.$css.'.css">
<link rel="shortcut icon" href="images/favicon.ico">
<title>'.$titlename.'</title>
</head>
';
if($mod=='list' || $mod=='admin-job'|| $css==2)
echo '<body style="width:100%;">';
else
echo '<body style="width:320px;">';

if($conf['switch']=='0')
exit('系统正在升级中，请稍后访问。');


?>