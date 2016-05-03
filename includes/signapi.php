<?php
if(!defined('IN_CRONLITE'))exit();

$qqapiid=$conf['qqapiid'];
$qqloginid=$conf['qqloginid'];
$apiserverid=$conf['apiserver'];
$mail_api=isset($conf['mail_api'])?$conf['mail_api']:0;
$allapi='http://api.cccyun.cn/';

if($apiserverid==1)
{
	$apiserver='http://wbrw.aliapp.com/';
}
elseif($apiserverid==2)
{
	$apiserver='http://3600.aliapp.com/';
}
else
{
	$apiserver='http://51tool.aliapp.com/';
}

$apiserver2='http://51tool.aliapp.com/';
$apiserver3='http://51tool.aliapp.com/';

if($qqapiid==1)
{
	$qqapi_server='http://cloud.odata.cc/';
}
elseif($qqapiid==2)
{
	$qqapi_server='http://api.odata.cc/';
}
elseif($qqapiid==3)
{
	$qqapi_server=$conf['myqqapi'];
}
elseif($qqapiid==4)
{
	$qqapi_server='http://reset.kfj.cc/';
}
else
{
	$qqapi_server=$siteurl;
}

if($qqloginid==1)
{
	$qqloginapi='http://clouds.aliapp.com/addqq.php?baseurl='.urlencode($siteurl).'&sitename='.urlencode($conf['sitename']);
}
elseif($qqloginid==2)
{
	$qqloginapi='http://xscron.sinaapp.com/addqq.php?baseurl='.urlencode($siteurl).'&sitename='.urlencode($conf['sitename']);
}
elseif($qqloginid==3)
{
	$qqloginapi='http://cloud.sgwap.net/addqq.php?baseurl='.urlencode($siteurl).'&sitename='.urlencode($conf['sitename']);
}

$qqlogin=($qqloginid!=0)?$qqloginapi:'index.php?mod=addqq';


if($mail_api==1)
{
	$mail_api_url='http://m.cccyun.cn/mail/';
}
elseif($mail_api==2)
{
	$mail_api_url='http://cloud.cccyun.cn/mail/';
}
elseif($mail_api==3)
{
	$mail_api_url='http://api.iwyd.cn/api/mail/';
}
?>