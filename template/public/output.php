<?php
//导出任务为TXT文件
if(!defined('IN_CRONLITE'))exit();

if(isset($_GET['sys']))
{$sysid=$_GET['sys'];
$rs=$DB->query("SELECT * FROM ".DBQZ."_job WHERE lx='{$gl}' and sysid='{$sysid}' and type!='3' order by jobid desc");
$file_name='output_sys'.$sysid.'_'.date("YmdHis").'.txt';
} else {
$rs=$DB->query("SELECT * FROM ".DBQZ."_job WHERE lx='{$gl}' and type!='3' order by jobid desc");
$file_name='output_'.date("YmdHis").'.txt';
}
$output='';
while($myrow = $DB->fetch($rs))
{
$output.=$myrow['url']."\r\n";
}
$file_size=strlen($output);
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Accept-Ranges: bytes");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
print($output);
?>