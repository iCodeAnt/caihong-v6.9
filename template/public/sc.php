<?php
if(!defined('IN_CRONLITE'))exit();
$title="创建任务";
include_once(TEMPLATE_ROOT."head.php");

$sysid=intval($_GET['sys']);
$jobid=isset($_GET['jobid'])?intval($_GET['jobid']):null;
$ks=isset($_REQUEST['start'])?intval($_REQUEST['start']):null;
$js=isset($_REQUEST['stop'])?intval($_REQUEST['stop']):null;

if($theme=='default')echo '<div class="col-md-9" role="main" style="margin: 0 auto;">';

if($_GET['my']=='gn'){
echo'<div class="panel panel-info">
<div class="panel-heading w h"><h3 class="panel-title">国内代理服务器地址:</h3></div>';
echo'<div class="panel-body box">';
echo"<pre>";
include(ROOT."includes/content/gndl.php");
echo"</pre></div></div>";
}
if($_GET['my']=='gw'){
echo'<div class="panel panel-info">
<div class="panel-heading w h"><h3 class="panel-title">国外代理服务器地址:</h3></div>';
echo'<div class="panel-body box">';
echo"<pre>";
include(ROOT."includes/content/gwdl.php");
echo"</pre></div></div>";
} 
if($_GET['my']=='ua'){
echo'<div class="panel panel-info">
<div class="panel-heading w h"><h3 class="panel-title">常见浏览器UA:</h3></div>';
echo'<div class="panel-body box">';
echo"<pre>";
include(ROOT."includes/content/ua.php");
echo"</pre></div></div>";
}
if($_GET['my']=='sj'){
echo'<div class="panel panel-info">
<div class="panel-heading w h"><h3 class="panel-title">时间公式:</h3></div>';
echo'<div class="panel-body box">';
echo"<pre>";
include(ROOT."includes/content/sj.php");
echo"</pre></div></div>";
}


if($islogin==1){

if($ks>$js)//运行时间判断
{
showmsg('运行时间格式错误:<br><font style=color:red>开始时间大于结束时间!</font>');
exit();
}

if(!$jobid){}else{//获取jobid任务数据
$row1=$DB->get_row("SELECT *FROM ".DBQZ."_job where jobid='{$jobid}' limit 1");
if($isadmin==1)$gl=$row1['lx'];
$sysid=$row1['sysid'];
}

if ($_GET['my'] == 'kq') {//任务开启暂停
	$sds = $DB->query("update `".DBQZ."_job` set `zt` ='0' where `jobid`='{$jobid}'");
	if ($sds) {
		showmsg('开启成功!',1,'rw');
	} else {
		showmsg('开启失败!'.$DB->error(),4,'rw');
	}
}
if ($_GET['my'] == 'zt') {
	$sds = $DB->query("update `".DBQZ."_job` set `zt` ='1' where `jobid`='{$jobid}'");
	if ($sds) {
		showmsg('暂停成功!',1,'rw');
	} else {
		showmsg('暂停失败!'.$DB->error(),4,'rw');
	}
}

if($_GET['my']=='qqjob'){//添加QQ任务
$type=3;
$sysid=daddslashes($_POST['sys']);
$func=daddslashes($_GET['func']);
$qq=daddslashes($_GET['qq']);
$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
if($row['lx']!=$gl && $isadmin!=1)
{showmsg('你只能操作自己的QQ哦！',3);
exit;
}

if($siteurl!=$conf['siteurl'])
	saveSetting('siteurl',$siteurl);

$url=qqjob_encode($func);
if(!defined('SQLITE'))
$url=addslashes($url);
$mc=daddslashes($_POST['mc']);
$start=isset($_POST['start']) ? $_POST['start'] : '0';
$stop=isset($_POST['stop']) ? $_POST['stop'] : '24';
$pl=isset($_POST['pl']) ? $_POST['pl'] : '0';
$proxy=$qq;

if(!$_POST['mc'] || !$_POST['sys'])
{showmsg('参数不能为空！',3);
exit;
}
if($pl && !preg_match('/[0-9]/',$pl)){
showmsg('运行频率只能是数字哦！',3);
exit; 
}

if(in_array($sysid,$vip_sys) && $isvip==0 && $isadmin==0){showmsg('此系统只有VIP才可使用，请重新选择一个系统！[<a href="index.php?mod=shop&kind=2">点此开通VIP</a>]',3);
exit();
}
$rowm1=$DB->get_row("SELECT * FROM ".DBQZ."_job WHERE proxy='{$qq}' and mc='{$mc}' limit 1");
if($jobid=='' && $rowm1['url']==''){
if($sysid==-1){showmsg('添加任务失败！<br>错误原因:<br>该系统任务数量已饱和，请重新选择一个系统！',3);
exit();
}
$servernum2=$DB->count("SELECT count(*) FROM ".DBQZ."_job WHERE sysid='{$sysid}'");
if($servernum2<$conf['max']){}else{showmsg('添加任务失败！<br>错误原因:<br>该系统任务数量已饱和，请重新选择一个系统！',3);
exit();
}
$sql18="insert into `".DBQZ."_job` (`sysid`,`mc`,`type`,`url`,`post`,`lx`,`timea`,`timeb`,`usep`,`proxy`,`start`,`stop`,`pl`) values ('".$sysid."','".$mc."','".$type."','".$url."','0','".$gl."','".$date."','".$date."','0','".$proxy."','".$start."','".$stop."','".$pl."')";
$sds=$DB->query($sql18);
if($sds){
showmsg('任务已成功添加!',1,'addqqrw');
}else{
showmsg('任务添加失败!<br/>'.$DB->error(),4);
}
}else{
$sql18="update `".DBQZ."_job` set `sysid` ='$sysid',`mc` ='$mc',`url` ='$url',`start`='$start',`stop`='$stop',`pl`='$pl' where `jobid`='$jobid'";
$sds=$DB->query($sql18);
if($sds){
showmsg('任务已成功修改!',1,'addqqrw');
}else{
showmsg('任务修改失败!<br/>'.$DB->error(),4);
}
}

}


if($_GET['my']=='add'){//添加任务
include(TEMPLATE_ROOT."addrw.php");
}

if($_GET['my']=='add1'){//添加任务结果
$type=isset($_POST['type']) ? intval($_POST['type']) : 0;
$mc=daddslashes($_POST['mc']);
if(!$mc)$mc='网址挂刷任务';
$url=daddslashes($_POST['url']);
$post=daddslashes($_POST['post']);
$postfields=daddslashes($_POST['postfields']);
$cookie=daddslashes($_POST['cookie']);
$usep=daddslashes($_POST['usep']);
$proxy=daddslashes($_POST['proxy']);
$referer=daddslashes($_POST['referer']);
$useragent=daddslashes($_POST['useragent']);
$start=isset($_POST['start']) ? intval($_POST['start']) : '0';
$stop=isset($_POST['stop']) ? intval($_POST['stop']) : '24';
$pl=isset($_POST['pl']) ? intval($_POST['pl']) : '0';

$urlp='!^http://(.+\.)+.+!i';

if(preg_match($urlp,$url,$r))
{
if($conf['block'] && preg_match('/('.$conf['block'].')/',$url)){//关键词屏蔽
showmsg('添加任务失败！<br>错误原因:<br>网址中包含系统禁止的关键词！');
exit();
}

$rowm1=$DB->get_row("SELECT * FROM ".DBQZ."_job WHERE url='{$url}' limit 1");
if($rowm1['url']==''){}else{showmsg('添加任务失败！<br>错误原因:<br>每个网址只能添加一次，此任务网址已存在于系统中!');
exit();
}

if ($usep=='1' && !preg_match('!.+:\d{2,}!i',$proxy)){
showmsg('如果你想使用代理请设置正确的代理ip及端口哦！',3);
exit; 
}

if ($post=='1' && !preg_match('!.+=.+!i',$postfields)){
showmsg('如果你想POST数据请填写格式正确的POST数据哦，例如:user=***&pass=***',3);
exit; 
}

if($pl && !preg_match('/[0-9]/',$pl)){
showmsg('运行频率只能是数字哦！',3);
exit; 
}

if(in_array($sysid,$vip_sys) && $isvip==0 && $isadmin==0){showmsg('此系统只有VIP才可使用，请重新选择一个系统！[<a href="index.php?mod=shop&kind=2">点此开通VIP</a>]',3);
exit();
}
$servernum2=$DB->count("SELECT count(*) FROM ".DBQZ."_job WHERE sysid='{$sysid}'");
if($servernum2<$conf['max']){}else{showmsg('添加任务失败！<br>错误原因:<br>该系统任务数量已饱和！',3);
exit();
}

$sql18="insert into `".DBQZ."_job` (`sysid`,`mc`,`type`,`url`,`post`,`postfields`,`cookie`,`lx`,`timea`,`timeb`,`usep`,`proxy`,`referer`,`useragent`,`start`,`stop`,`pl`) values ('".$sysid."','".$mc."','".$type."','".$url."','".$post."','".$postfields."','".$cookie."','".$gl."','".$date."','".$date."','".$usep."','".$proxy."','".$referer."','".$useragent."','".$start."','".$stop."','".$pl."')";

$sds=$DB->query($sql18);

if($sds){
if($type==1)showmsg('任务已成功添加!',1,'addqqrw');
elseif($type==2)showmsg('任务已成功添加!',1,'addqdrw');
else showmsg('任务已成功添加!',1,'addrw');
}else{
showmsg('任务添加失败!<br/>'.$DB->error(),4);
}
}
else{
showmsg('网址不合法！必须包含且只能包含一个http://',3);
}}



if($_GET['my']=='bulk'){//批量添加任务
include(TEMPLATE_ROOT."addrw.php");
}

if($_GET['my']=='upload'){//从文件导入任务
include(TEMPLATE_ROOT."addrw.php");
}

if($_GET['my']=='bulk1'){//批量添加任务结果
if(isset($_FILES['file']))
{$url=file_get_contents($_FILES['file']['tmp_name']);
} else {
$mc=daddslashes($_POST['mc']);
$url=daddslashes($_POST['url']);
}
$start=isset($_POST['start']) ? intval($_POST['start']) : '0';
$stop=isset($_POST['stop']) ? intval($_POST['stop']) : '24';
$pl=isset($_POST['pl']) ? intval($_POST['pl']) : '0';

$url = str_replace(array("\r\n", "\r", "\n"), "[br]", $url);
$match=explode("[br]",$url);

if(in_array($sysid,$vip_sys) && $isvip==0 && $isadmin==0){showmsg('此系统只有VIP才可使用，请重新选择一个系统！[<a href="index.php?mod=shop&kind=2">点此开通VIP</a>]',3);
exit();
}
$servernum2=$DB->count("SELECT count(*) FROM ".DBQZ."_job WHERE sysid='{$sysid}'");
if($servernum2<$conf['max']){}else{showmsg('添加任务失败！<br>错误原因:<br>该系统任务数量已饱和！',3);
exit();
}

echo '<div class="w h"><h3 class="form-signin-heading">批量添加任务结果</h3></div><div class="box">';
if(count($match)>$conf['bulk'] && $isadmin==0){
echo '网址数量超过'.$conf['bulk'].'个了！</div>';
exit;
}

$urlp='!^http://(.+\.)+.+!i';
foreach($match as $val)
{
if($val!=''){
if(preg_match($urlp,$val))
{

if($conf['block'] && preg_match('/('.$conf['block'].')/',$url)){//关键词屏蔽
echo $val.'<br/><font color="red">网址中包含系统禁止的关键词！</font><hr/>';continue;
}

if(!$mc)$mc='批量添加的任务';
$rowm1=$DB->get_row("SELECT * FROM ".DBQZ."_job WHERE url='{$val}' limit 1");
if($rowm1['url']==''){
$sql18="insert into `".DBQZ."_job` (`sysid`,`mc`,`url`,`lx`,`timea`,`timeb`,`start`,`stop`,`pl`) values ('".$sysid."','".$mc."','".daddslashes($val)."','".$gl."','".$date."','".$date."','".$start."','".$stop."','".$pl."')";

$sds=$DB->query($sql18);

if($sds)
echo $val.'<br/><font color="green">已成功添加!</font><hr/>';
else
echo $val.'<br/><font color="red">任务添加失败!</font><br/>'.$DB->error().'<hr/>';
}
else
echo $val.'<br/><font color="red">任务添加失败!此网址已存在于系统中!</font><hr/>';
}
else
echo $val.'<br/><font color="red">网址不合法！必须至少包含一个http://</font><hr/>';
}
}
if(isset($_FILES['file']))echo '<hr/><a href="index.php?mod=sc&my=upload&sys='.$sysid.$link.'">>>继续添加</a><br/><a href="index.php?mod=list&sys='.$sysid.$link.'"><< 返回我的任务列表</a></div>';
else echo '<hr/><a href="index.php?mod=sc&my=bulk&sys='.$sysid.$link.'">>>继续添加</a><br/><a href="index.php?mod=list&sys='.$sysid.$link.'"><< 返回我的任务列表</a></div>';
}


if($_GET['my']=='del'){//删除任务
if($row1['lx']==$gl){

$sql18="DELETE FROM ".DBQZ."_job WHERE jobid='$jobid'";
$sds=$DB->query($sql18);
if($sds){
showmsg('任务删除成功!',1,'rw');
}else{
showmsg('任务删除失败!<br/>'.$DB->error(),4,'rw');
}
}
else{
showmsg('你只能删除自己的任务哦！',3,'rw');
}
}



if($_GET['my']=='edit'){//编辑任务
if($row1['lx']==$gl){
include(TEMPLATE_ROOT."addrw.php");
}
else{
showmsg('你只能编辑自己的任务哦！',3,'rw');
}
}

if($_GET['my']=='edit1'){//编辑任务结果
if($row1['lx']==$gl){
$mc=daddslashes($_POST['mc']);
if(!$mc)$mc='网址挂刷任务';
$url=daddslashes($_POST['url']);
$post=daddslashes($_POST['post']);
$postfields=daddslashes($_POST['postfields']);
$cookie=daddslashes($_POST['cookie']);
$usep=daddslashes($_POST['usep']);
$proxy=daddslashes($_POST['proxy']);
$referer=daddslashes($_POST['referer']);
$useragent=daddslashes($_POST['useragent']);
$start=isset($_POST['start']) ? intval($_POST['start']) : '0';
$stop=isset($_POST['stop']) ? intval($_POST['stop']) : '24';
$pl=isset($_POST['pl']) ? intval($_POST['pl']) : '0';

$urlp='!^http://(.+\.)+.+!i';
if(preg_match($urlp,$url,$r))
{
if($conf['block'] && preg_match('/('.$conf['block'].')/',$url)){//关键词屏蔽
showmsg('添加任务失败！<br>错误原因:<br>网址中包含系统禁止的关键词！');
exit();
}

$url2=$DB->count("SELECT count(*) FROM ".DBQZ."_job WHERE url='{$url}' ");
if($url2<='1'){}else{showmsg('添加任务失败！<br>错误原因:<br>每个网址只能添加一次，此任务网址已存在于系统中!');
exit();
}

if ($usep=='1' && !preg_match('!.+:\d{2,}!i',$proxy)){
showmsg('如果你想使用代理请设置正确的代理ip及端口哦！',3);
exit; 
}

if ($post=='1' && !preg_match('!.+=.+!i',$postfields)){
showmsg('如果你想POST数据请填写格式正确的POST数据哦，例如:user=***&pass=***',3);
exit; 
}

if($pl && !preg_match('/[0-9]/',$pl)){
showmsg('运行频率只能是数字哦！',3);
exit; 
}

if($isadmin==1)
$sql18="update `".DBQZ."_job` set `mc` ='$mc',`url` ='$url',`post` ='$post',`postfields` ='$postfields',`cookie` ='$cookie',`usep` ='$usep',`proxy` ='$proxy',`referer` ='$referer',`useragent` ='$useragent',`start`='$start',`stop`='$stop',`pl`='$pl' where `jobid`='$jobid'";
else
$sql18="update `".DBQZ."_job` set `mc` ='$mc',`url` ='$url',`post` ='$post',`postfields` ='$postfields',`cookie` ='$cookie',`usep` ='$usep',`proxy` ='$proxy',`referer` ='$referer',`useragent` ='$useragent',`start`='$start',`stop`='$stop',`pl`='$pl',`timea`='$date' where `jobid`='$jobid'";
$sds=$DB->query($sql18);
if($sds){
	if($row1['type']==2)
	showmsg('任务已成功修改!',1,'addqdrw');
	else
	showmsg('任务已成功修改!',1,'addrw');
}else{
	showmsg('任务修改失败!<br/>'.$DB->error());
}
}
else{
showmsg('网址不合法！必须包含且只能包含一个http://');
}
}
else{
showmsg('你只能编辑自己的任务哦！');
}
}



}

else{
showmsg('你还没登录哦，请先<a href="index.php?mod=login">登录</a>！',2);
}


echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
include(ROOT.'includes/foot.php');
echo'</div></div></div></div></body></html>';
?>