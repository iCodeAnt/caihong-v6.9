<?php
/*监控文件*/
include_once("../includes/cron.inc.php");
$sysid=2;
$seconds=$seconds[$sysid-1];
$loop=$loop[$sysid-1];
$multi=$multi[$sysid-1];


if($multi==1) {
	if(isset($_GET['multi']))
		$rs=$DB->query("select * from `".DBQZ."_job` where sysid='$sysid' and zt='0' and start<=$t and stop>=$t limit {$_GET['start']},{$_GET['num']}");
	else {
		$szie=$szie==0 ? 30 :$szie;
		$nump=$DB->count("select count(*) from ".DBQZ."_job WHERE sysid='$sysid'");
		$xz=ceil($nump/$szie);
		for($i=0;$i<=$xz;$i++){
			$start=$i*$szie;
			curl_run(($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?key='.$_GET['key'].'&multi=on&start='.$start.'&num='.$szie);
		}
		exit("Successful opening of {$xz} threads!");
	}
} else {
if($szie!=0) {
	$nump=$DB->count("select count(*) from ".DBQZ."_job WHERE sysid='$sysid'");
	$xz=ceil($nump/$szie);
	$shu=$DB->get_row("SELECT * FROM ".DBQZ."_info WHERE sysid='$sysid' limit 1");
	$shu=$shu['times'];
	$up_shu=$shu+1;
	if($shu>=$xz)
	{
		$DB->query("update ".DBQZ."_info set times='1' where sysid='$sysid'");
		$shu=0;
	}
	else
	{
		$DB->query("update ".DBQZ."_info set times='".$up_shu."' where sysid='$sysid'");
	}
	$shu=$shu*$szie;
	$rs=$DB->query("select * from `".DBQZ."_job` where sysid='$sysid' and zt='0' and start<=$t and stop>=$t limit $shu,$szie");
}
else{
	$rs=$DB->query("select * from `".DBQZ."_job` where sysid='$sysid' and zt='0' and start<=$t and stop>=$t");
}
}

if($seconds>0){
run_fast($sysid);
}
else
{
run_basic($sysid);
}
$DB->query("update `".DBQZ."_info` set `last`='$date' where sysid='$sysid'");
$DB->query("update `".DBQZ."_info` set `times`=`times`+1,`last`='$date' where sysid='0'");

echo '<head><title>success in run</title></head>';
echo $date;


if($loop==1 && $seconds==0 && !isset($_GET['multi']))
dojob();
?>