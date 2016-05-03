<?php
if(!defined('IN_CRONLITE'))exit();
include ROOT.'includes/SEO.php';

$zongs=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE 1");
$users=$DB->count("SELECT count(*) from ".DBQZ."_user WHERE 1");
$qqs=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");

$sid=isset($_COOKIE["sid"])?$_COOKIE["sid"]:null;
$css=$conf['css2'];
$sitename=$conf['sitename'];

if($title=='首页' || empty($title))
$titlename=$sitename;
else
$titlename=$title.'|'.$sitename;

if($css==1)
$cssfile=null;
elseif($css==2)
$cssfile='<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">';
elseif($css==3)
$cssfile='<link rel="stylesheet" href="http://cronsite.aliapp.com/static/flat/flat-ui.min.css">';
elseif($css==4)
$cssfile='<link rel="stylesheet" href="http://cronsite.aliapp.com/static/todc/todc-bootstrap.min.css">';
elseif($css==5)
$cssfile='<link rel="stylesheet" href="http://cronsite.aliapp.com/static/metro/metro-bootstrap.min.css"><link rel="stylesheet" href="http://cronsite.aliapp.com/static/metro/metro-bootstrap-responsive.min.css">';

echo <<<HTML
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="charset" content="utf-8">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$titlename} {$conf['sitetitle']}</title>
<meta name="keywords" content="{$SEO_keywords}" />
<meta name="description" content="{$SEO_description}" />
<link rel="shortcut icon" href="images/favicon.ico">
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
{$cssfile}
<link href="style/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body background="images/fzbeijing.png">
<STYLE type="text/css">
body{cursor:url('images/XSSB-1.cur'), auto;}
a{cursor:url('images/XSSB-2.cur'), auto;}
</STYLE>
HTML;

if($conf['switch']=='0')
exit('系统正在升级中，请稍后访问。');

$m=$_GET['m'];
include_once(TEMPLATE_ROOT."nav.php");
?>