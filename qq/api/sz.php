<?php
session_start();
header("content-Type: text/html; charset=utf-8");

require_once '../cron.inc.php';

if($islogin!=1)exit('{"code":-1,"msg":"未登录"}');
if(in_array('10',$vip_func) && $isvip==0 && $isadmin==0)exit('{"code":-1,"msg":"您不是VIP，无法使用"}');

$cell=$_POST['cell'];
$uin=$_POST['uin'];

$myrow=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$uin}' limit 1");
if($myrow['lx']!=$gl && $isadmin==0)exit('{"code":-1,"msg":"你只能操作自己的QQ哦！"}');

$qid=is_numeric($_POST['qid'])?$_POST['qid']:exit('{"code":-1,"msg":"QID不能为空"}');
$row = $DB->get_row("SELECT * FROM ".DBQZ."_qq where id='{$qid}' limit 1");
if(!$row){
	exit('{"code":-1,"msg":"QID'.$qid.'不存在"}');
}
	include_once "../qzone.class.php";
	$appid='311';
	$typeid='0';
	$curkey=urlencode('http://user.qzone.qq.com/'.$uin.'/mood/'.$cell);
	$uinkey=urlencode('http://user.qzone.qq.com/'.$uin.'/mood/'.$cell);
	$from='1';
	$abstime=time();
	$cellid=$cell;

	$qzone=new qzone($row['qq'],$row['sid'],$row['skey']);
	$qzone->cplike($uin,$appid,$uinkey,$curkey);
	if($qzone->sidzt){
		$_SESSION['o_'.$cell][$row['qq']]=0;
		$DB->query("UPDATE ".DBQZ."_qq SET status='0' WHERE qq='".$row['qq']."'");
		exit('{"code":-3,"msg":"'.$row[qq].'的SID已过期！"}');
	}
		if(strpos($qzone->msg[0],'频繁')){
			if($row['status2']==1){
				$qzone->msg=array();
				$qzone->pclike($uin,$curkey,$uinkey,$from,$appid,$typeid,$abstime,$cellid);
				if($qzone->skeyzt){$DB->query("UPDATE ".DBQZ."_qq SET status2='0' WHERE qq='".$row['qq']."'");
					exit('{"code":-4,"msg":"'.$qzone->msg[0].'"}');
				}else{
					$_SESSION['o_'.$cell][$row['qq']]=1;
					++$_SESSION['szcount'];
					exit('{"code":0,"msg":"'.$row[qq].'赞你成功！"}');
				}
			}
			exit('{"code":-2,"msg":"'.$row[qq].'操作频繁"}');
		}
		$_SESSION['o_'.$cell][$row['qq']]=1;
		++$_SESSION['szcount'];
		exit('{"code":0,"msg":"'.$row[qq].'赞你成功！"}');
