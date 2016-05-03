<?php
if(!defined('IN_CRONLITE'))exit();

$users=$DB->count("SELECT count(*) from ".DBQZ."_user WHERE 1");
$qqs=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");

$strtotime=strtotime($conf['build']);
$yxts=ceil((time()-$strtotime)/86400);

$content=file_get_contents(ROOT."template/index.html");
$content=str_replace("{:C('web_name')}",$conf['sitename'],$content);
$content=str_replace("{:C('web_qq')}",$conf['kfqq'],$content);
$domain=parse_url($siteurl);
$domain=$domain['host'];
$content=str_replace("{:C('web_domain')}",$domain,$content);
$content=str_replace("{:get_count('users')}",$users,$content);
$content=str_replace("{:get_count('qqs')}",$qqs,$content);
$content=str_replace("{:get_count('date')}",$yxts,$content);

echo $content;

?>