<?php
error_reporting(0);
define('IN_CRONLITE', true);
define('IN_CRONJOB', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
define('CACHE_FILE', 0);

function curl_run($url) {
	$curl=curl_init($url);
 curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
 curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($curl,CURLOPT_TIMEOUT,3);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_exec($curl);
	curl_close($curl);
	return true;
}

function run_fast($sysid) {//秒刷模式运行
global $DB,$t,$seconds,$date,$siteurl,$conf,$rules;
for($i=1;$i<=$seconds;$i++){
$rs=$DB->query("select * from `".DBQZ."_job` where sysid='$sysid' and zt='0' and start<=$t and stop>=$t");
while ($row = $DB->fetch($rs)) {
	$time=time();
	if ($row['time']+$row['pl']<=$time) {
	$id =$row['jobid'];
	$day=date("Ymd");
	if($row['day']!=$day && $conf['jifen']==1){ //扣除虚拟币
		if($row['type']==2) $coin=$rules[3];
		elseif($row['type']==3) $coin=$rules[4];
		else $coin=$rules[2];
		$udata=$DB->get_row("select userid,coin,vip,vipdate from ".DBQZ."_user where user='".$row['lx']."' limit 1");
		if($udata['vip']==1){
			if(strtotime($udata['vipdate'])>time()){
				$isvip=1;
			}else{
				$isvip=0;
			}
		}elseif($udata['vip']==2){
			$isvip=1;
		}else{
			$isvip=0;
		}
		if($udata['userid']==1)$isvip=1;
		if($conf['vip_coin']==1 && $isvip==1){
			$DB->query("update ".DBQZ."_job set day='$day' where jobid='$id'");
		}else{
			if($udata['coin']>=$coin){
				$DB->query("update ".DBQZ."_user set coin=coin-{$coin} where user='".$row['lx']."'");
				$DB->query("update ".DBQZ."_job set day='$day' where jobid='$id'");
			}else{
				$DB->query("update ".DBQZ."_job set zt='1' where jobid='$id'");
			}
		}
	}
	if ($row['type']==3) { //QQ挂机模式
		$qqjob=qqjob_decode($row['url']);
		$row['url']=$qqjob['url'].'&runkey='.md5(RUN_KEY);
		if($row['url']=='no')continue;
	}
	if ($row['type']!=0) {
		$row['post']=1;
		$row['postfields']='backurl='.urlencode($siteurl);
	}
	$curl=curl_init();
	if($row['usep']==1)
	{
	curl_setopt($curl,CURLOPT_PROXY,$row['proxy']);
	}
	curl_setopt($curl,CURLOPT_URL,$row['url']);
	curl_setopt($curl,CURLOPT_TIMEOUT,1);
	curl_setopt($curl,CURLOPT_NOBODY,1);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_AUTOREFERER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
	if ($row['referer']!='')
	{
	curl_setopt($curl,CURLOPT_REFERER,$row['referer']);
	}
	if ($row['useragent']!='')
	{
	curl_setopt($curl,CURLOPT_USERAGENT,$row['useragent']);
	}
	if ($row['cookie']!='')
	{
	curl_setopt($curl,CURLOPT_COOKIE,$row['cookie']);
	}
	if($row['post']==1)
	{
	$postfields=str_replace('[时间]',$date,$row['postfields']);
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$postfields);
	}
	curl_exec($curl);
	curl_close($curl);
	$DB->query("update `".DBQZ."_job` set `times`=`times`+1,`timeb`='$date',`time`='$time' where `jobid`='$id'");
	}
}
}
}

function run_basic($sysid) {//普通模式运行
global $DB,$t,$rs,$date,$siteurl,$conf,$rules;
while ($row = $DB->fetch($rs)) {
	$time=time();
	if ($row['time']+$row['pl']<=$time) {
	$id =$row['jobid'];
	$day=date("Ymd");
	if($row['day']!=$day && $conf['jifen']==1){ //扣除虚拟币
		if($row['type']==2) $coin=$rules[3];
		elseif($row['type']==3) $coin=$rules[4];
		else $coin=$rules[2];
		$udata=$DB->get_row("select userid,coin,vip,vipdate from ".DBQZ."_user where user='".$row['lx']."' limit 1");
		if($udata['vip']==1){
			if(strtotime($udata['vipdate'])>time()){
				$isvip=1;
			}else{
				$isvip=0;
			}
		}elseif($udata['vip']==2){
			$isvip=1;
		}else{
			$isvip=0;
		}
		if($udata['userid']==1)$isvip=1;
		if($conf['vip_coin']==1 && $isvip==1){
			$DB->query("update ".DBQZ."_job set day='$day' where jobid='$id'");
		}else{
			if($udata['coin']>=$coin){
				$DB->query("update ".DBQZ."_user set coin=coin-{$coin} where user='".$row['lx']."'");
				$DB->query("update ".DBQZ."_job set day='$day' where jobid='$id'");
			}else{
				$DB->query("update ".DBQZ."_job set zt='1' where jobid='$id'");
			}
		}
	}

	if ($row['type']==3) { //QQ挂机模式
		$qqjob=qqjob_decode($row['url']);
		$row['url']=$qqjob['url'].'&runkey='.md5(RUN_KEY);
		if($row['url']=='no')continue;
	}
	if ($row['type']!=0) {
		$row['post']=1;
		$row['postfields']='backurl='.urlencode($siteurl);
	}
	$curl=curl_init();
	if($row['usep']==1)
	{
	curl_setopt($curl,CURLOPT_PROXY,$row['proxy']);
	}
	curl_setopt($curl,CURLOPT_URL,$row['url']);
	curl_setopt($curl,CURLOPT_TIMEOUT,1);
	curl_setopt($curl,CURLOPT_NOBODY,1);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_AUTOREFERER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
	if ($row['referer']!='')
	{
	curl_setopt($curl,CURLOPT_REFERER,$row['referer']);
	}
	if ($row['useragent']!='')
	{
	curl_setopt($curl,CURLOPT_USERAGENT,$row['useragent']);
	}
	if ($row['cookie']!='')
	{
	curl_setopt($curl,CURLOPT_COOKIE,$row['cookie']);
	}
	if($row['post']==1)
	{
	$postfields=str_replace('[时间]',$date,$row['postfields']);
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$postfields);
	}
	curl_exec($curl);
	curl_close($curl);
	$DB->query("update `".DBQZ."_job` set `times`=`times`+1,`timeb`='$date',`time`='$time' where `jobid`='$id'");
	}
}
}

function dojob(){
$fp=fsockopen($_SERVER["HTTP_HOST"],$_SERVER['SERVER_PORT']);
$out="GET {$_SERVER['PHP_SELF']}?key={$_GET['key']} HTTP/1.0".PHP_EOL;
$out.="Host: {$_SERVER['HTTP_HOST']}".PHP_EOL;
$out.="Connection: Close".PHP_EOL.PHP_EOL;
fputs($fp,$out);
fclose($fp);
}

if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}

if(defined("SAE_ACCESSKEY"))
include_once ROOT.'includes/sae.php';
else
include_once ROOT.'config.php';

date_default_timezone_set("PRC");
$date=date("Y-m-j H:i:s");
$t=date("H");

define('RUN_KEY',md5($user.md5($pwd)));
if(isset($db_qz))define('DBQZ', $db_qz);
else define('DBQZ', 'wjob');
if(!isset($port))$port='3306';
//连接数据库
include_once(ROOT."includes/db.class.php");
if(defined('SQLITE'))$DB=new DB($db_file);
else $DB=new DB($host,$user,$pwd,$dbname,$port);

include_once(ROOT."includes/cache.class.php");
$CACHE=new CACHE();
$conf=$CACHE->pre_fetch();//获取系统配置

define('SYS_KEY',$conf['syskey']);
$rules=explode("|",$conf['rules']);
$siteurl=$conf['siteurl'];
$szie=$conf['interval'];
$seconds=explode('-',$conf['seconds']);
$loop=explode('-',$conf['loop']);
$multi=explode('-',$conf['multi']);

include_once(ROOT."includes/signapi.php");
include_once(ROOT."includes/function.php");
include_once(ROOT."includes/qq.func.php");

if(!empty($conf['cronkey']) && $conf['cronkey']!=$_GET['key'])
exit("CronKey Access Denied!");

if($conf['apiserver']==0)array_map('unlink',glob(ROOT.'sign/cookie*'));
?>