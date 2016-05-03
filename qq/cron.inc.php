<?php
error_reporting(0);
define('IN_CRONLITE', true);
define('IN_CRONJOB', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');

if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}
header("content-Type: text/html; charset=utf-8");

if(defined("SAE_ACCESSKEY"))
include_once ROOT.'includes/sae.php';
else
include_once ROOT.'config.php';

date_default_timezone_set("PRC");
$date=date("Y-m-j H:i:s");
$t=date("H");

define('RUN_KEY',md5($user.md5($pwd)));
define('CACHE_FILE', $cache);
if(isset($db_qz))define('DBQZ', $db_qz);
else define('DBQZ', 'wjob');
if(!isset($port))$port='3306';
//连接数据库
include_once(ROOT."includes/db.class.php");
if(defined('SQLITE'))$DB=new DB($db_file);
else $DB=new DB($host,$user,$pwd,$dbname);

include_once(ROOT."includes/cache.class.php");
$CACHE=new CACHE();
$conf=$CACHE->pre_fetch();//获取系统配置

define('SYS_KEY',$conf['syskey']);
$rules=explode("|",$conf['rules']);
$siteurl=$conf['siteurl'];
$szie=$conf['interval'];
$seconds=explode('-',$conf['seconds']);
$loop=explode('-',$conf['loop']);
$vip_func=explode("|",$conf['vip_func']);

include_once(ROOT."includes/signapi.php");
include_once(ROOT."includes/function.php");
include_once(ROOT."includes/qq.func.php");
include_once(ROOT."includes/member.php");

?>