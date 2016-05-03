<?php
 /*
　*　后台管理文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="后台管理";
include_once(TEMPLATE_ROOT."head.php");

$my=isset($_POST['my'])?$_POST['my']:$_GET['my'];
if($theme=='default')echo '<div class="col-md-9" role="main">';

if ($isadmin==1)
{
if($my=='set_config')
{
echo '<div class="w h"><h3>网站信息配置</h3></div><div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_config">
<div class="form-group">
<label>*站点名称:</label><br>
<input type="text" class="form-control" name="sitename" value="'.$conf['sitename'].'">
</div>
<div class="form-group">
<label>*网站标题栏后缀:</label><br>
<input type="text" class="form-control" name="sitetitle" value="'.$conf['sitetitle'].'">
</div>
<div class="form-group">
<label>*网站客服QQ:</label><br>
<input type="text" class="form-control" name="kfqq" value="'.$conf['kfqq'].'">
</div>
<div class="form-group">
<label>*开启系统个数(>=1&<=8):</label><br>
<input type="text" class="form-control" name="sysnum" value="'.$conf['sysnum'].'" maxlength="10">
</div>
<div class="form-group">
<label>*单系统任务数上限:</label><br>
<input type="text" class="form-control" name="max" value="'.$conf['max'].'" maxlength="10">
</div>
<div class="form-group">
<label>*列表每页显示个数:</label><br>
<input type="text" class="form-control" name="pagesize" value="'.$conf['pagesize'].'" maxlength="10">
</div>
<div class="form-group">
<label>*批量添加任务上限:</label><br>
<input type="text" class="form-control" name="bulk" value="'.$conf['bulk'].'" maxlength="10">
</div>
<div class="form-group">
<label>普通用户QQ添加数量上限(0为不限制):</label><br>
<input type="text" class="form-control" name="qqlimit" value="'.$conf['qqlimit'].'" maxlength="10">
</div>
<div class="form-group">
<label>VIP会员QQ添加数量上限(0为不限制):</label><br>
<input type="text" class="form-control" name="qqlimit2" value="'.$conf['qqlimit2'].'" maxlength="10">
</div>
<div class="form-group">
<label>用户添加QQ加群号:</label><br>
<input type="text" class="form-control" name="qqqun" value="'.$conf['qqqun'].'" maxlength="10">
<font color="green">填写群号后，用户添加QQ将自动加该群。留空则不加群</font>
</div>
<div class="form-group">
<label>*管理员UID:</label><br>
<input type="text" class="form-control" name="adminid" value="'.$conf['adminid'].'" maxlength="10">
<font color="green">千万不要把1删掉！填写管理员的UID，用“|”隔开。设置的管理员将拥有和你完全相同的权限。</font>
</div>
<div class="form-group">
<label>注册开关:</label><br><select class="form-control" name="zc""><option value="'.$conf['zc'].'">'.$conf['zc'].'</option><option value="2">2_开放注册(防刷模式)</option><option value="1">1_开放注册</option><option value="0">0_关闭注册</option></select>
<font color="green">建议开启防刷模式，以免被恶意刷注册。主机屋空间请不要开启防刷模式。</font>
</div>
<div class="form-group">
<label>底部随机语录:</label><br><select class="form-control" name="sjyl""><option value="'.$conf['sjyl'].'">'.$conf['sjyl'].'</option><option value="1">1_显示</option><option value="0">0_隐藏</option></select>
<font color="green">开启随机语录可能会影响页面加载效率</font>
</div>
<div class="form-group">
<label>*系统执行频率显示设定:</label><br/><input type="text" class="form-control" name="frequency" value="'.$conf['show'].'"><font color="green">分别对应1～8系统，中间用“|”隔开。此处可以修改用户中心所显示的每个系统的运行频率，实际运行频率由监控频率决定。</font>
</div>
<div class="form-group">
<label>网址关键词屏蔽设定:</label><br/><input type="text" class="form-control" name="block" value="'.$conf['block'].'"><font color="green">每个关键词中间用“|”隔开</font>
</div>
<div class="form-group">
<label>添加QQ屏蔽设定:</label><br/><input type="text" class="form-control" name="qqblock" value="'.$conf['qqblock'].'"><font color="green">每个QQ号中间用“|”隔开</font>
</div>
<div class="form-group">
<label>IP地址屏蔽设定:</label><br/><input type="text" class="form-control" name="banned" value="'.$conf['banned'].'"><font color="green">每个IP地址中间用“|”隔开</font><br/>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_config'){
$sitename=$_POST['sitename'];
$sitetitle=$_POST['sitetitle'];
$kfqq=$_POST['kfqq'];
$sysnum=$_POST['sysnum'];
$max=$_POST['max'];
$pagesize=$_POST['pagesize'];
$zc=$_POST['zc'];
$sjyl=$_POST['sjyl'];
$bulk=$_POST['bulk'];
$qqlimit=$_POST['qqlimit'];
$qqlimit2=$_POST['qqlimit2'];
$show=$_POST['frequency'];
$block=$_POST['block'];
$banned=$_POST['banned'];
$adminid=$_POST['adminid'];
$qqblock=$_POST['qqblock'];
$qqqun=$_POST['qqqun'];
if($sitename==NULL or $sysnum==NULL or $max==NULL or $pagesize==NULL or $show==NULL or $bulk==NULL or $adminid==NULL){
showmsg('保存错误,请确保加*项都不为空!',3);
} else {
saveSetting('sitename', $sitename);
saveSetting('sitetitle', $sitetitle);
saveSetting('kfqq', $kfqq);
saveSetting('sysnum', $sysnum);
saveSetting('max', $max);
saveSetting('pagesize', $pagesize);
saveSetting('zc', $zc);
saveSetting('sjyl', $sjyl);
saveSetting('bulk', $bulk);
saveSetting('qqlimit', $qqlimit);
saveSetting('qqlimit2', $qqlimit2);
saveSetting('show', $show);
saveSetting('block', $block);
saveSetting('banned', $banned);
saveSetting('adminid', $adminid);
saveSetting('qqblock', $qqblock);
saveSetting('qqqun', $qqqun);
$ad=$CACHE->clear();
if($sysnum>16)$DB->query("INSERT INTO `wjob_info`(`sysid`, `times`) VALUES
('17', '0'),
('18', '0'),
('19', '0'),
('20', '0');");
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_rw')
{
echo '<div class="w h"><h3>任务运行配置</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_rw">
<div class="form-group">
<label>监控文件运行密钥:</label><br><input type="text" class="form-control" name="cronkey" value="'.$conf['cronkey'].'">
<font color="green">默认为空。设置密钥后，你需要在所有监控文件后面加上 <u>?key=你的密钥</u> ，例如系统一的监控地址就是 <u>'.$siteurl.'cron/job1.php?key=你的密钥</u> 。这样可以防止监控文件被恶意执行</font>
</div>
<div class="form-group">
<label>多线程运行开关:</label><br><input type="text" class="form-control" name="multi" value="'.$conf['multi'].'">
<font color="green">多线程运行开关。分别对应1～8系统，中间用“-”相连。1为开启，0为关闭。多线程在任务总数过多的情况下建议开启，开启多线程可能会加重服务器负担。</font>
</div>
<div class="form-group">
<label>每次/每线程运行个数:</label><br/><input type="text" class="form-control" name="interval" value="'.$conf['interval'].'" maxlength="10">
<font color="green">每次运行任务数是指在单个系统内，每运行一次监控文件(jobx.php)所能够执行的任务数。如果开启多线程后则为每个线程的任务数。为0则默认全部执行。可以根据自己空间的负载情况进行设置。</font>
</div>
<div class="form-group">
<label><font color="red">[优先级1]</font>有限循环秒刷配置:</label><br/><input type="text" class="form-control" name="seconds" value="'.$conf['seconds'].'">
<font color="green">分别对应1～8系统，中间用“-”相连。0为关闭该系统秒刷功能，大于0的数则为每运行一次监控文件(jobx.php)所连续循环执行的次数。使用秒刷功能可能会导致空间超负载。</font>
</div>
<div class="form-group">
<label><font color="red">[优先级2]</font>无限循环秒刷配置:</label><br/><input type="text" class="form-control" name="loop" value="'.$conf['loop'].'">
<font color="green">分别对应1～8系统，中间用“-”相连。0为关闭该系统秒刷功能，1为开启。开启后，每运行一次监控文件(jobx.php)可不间断地自动循环运行，最大循环次数因空间而异。</font><br/>
<font color="red">优先级说明：开启优先级1的秒刷配置后优先级2的配置将失效</font>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_rw'){
$cronkey=$_POST['cronkey'];
$multi=$_POST['multi'];
$interval=$_POST['interval'];
$seconds=$_POST['seconds'];
$loop=$_POST['loop'];
if($interval==NULL or $seconds==NULL or $loop==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('cronkey', $cronkey);
saveSetting('multi', $multi);
saveSetting('interval', $interval);
saveSetting('seconds', $seconds);
saveSetting('loop', $loop);

$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_mail')
{
echo '<div class="w h"><h3>发信邮箱配置</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_mail">
<div class="form-group">
<label>邮箱账号:</label><br><input type="text" class="form-control" name="mail_name" value="'.$conf['mail_name'].'">
</div>
<div class="form-group">
<label>邮箱密码:</label><br><input type="text" class="form-control" name="mail_pwd" value="'.$conf['mail_pwd'].'">
</div>
<div class="form-group">
<label>邮箱STMP服务器:</label><br><input type="text" class="form-control" name="mail_stmp" value="'.$conf['mail_stmp'].'">
</div>
<div class="form-group">
<label>邮箱STMP端口:</label><br><input type="text" class="form-control" name="mail_port" value="'.$conf['mail_port'].'">
</div>
<font color="green">如果为QQ邮箱需先开通STMP，且要填写QQ邮箱独立密码。邮箱STMP服务器可以百度一下，例如163邮箱的即为 smtp.163.com。邮箱STMP端口默认为25</font><br/><br/>

<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_mail'){
$mail_name=$_POST['mail_name'];
$mail_pwd=$_POST['mail_pwd'];
$mail_stmp=$_POST['mail_stmp'];
$mail_port=$_POST['mail_port'];
if($mail_name==NULL or $mail_pwd==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('mail_name', $mail_name);
saveSetting('mail_pwd', $mail_pwd);
saveSetting('mail_stmp', $mail_stmp);
saveSetting('mail_port', $mail_port);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_client')
{
echo '<div class="w h"><h3>安卓客户端配置</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_client">
<div class="form-group">
<label>APP最新版本号:</label><br><input type="text" class="form-control" name="app_version" value="'.$conf['app_version'].'">
</div>
<div class="form-group">
<label>APP更新说明:</label><br><textarea class="form-control" name="app_log" rows="4">'.$conf['app_log'].'</textarea>
</div>
<div class="form-group">
<label>是否APP启动时弹出内容:</label><br><select class="form-control" name="app_start_is""><option value="'.$conf['app_start_is'].'">'.$conf['app_start_is'].'</option><option value="1">1_是</option><option value="0">0_否</option></select>
</div>
<div class="form-group">
<label>APP启动时弹出的内容:</label><br><textarea class="form-control" name="app_start" rows="4">'.$conf['app_start'].'</textarea>
</div>
<font color="green">（仅限已定制安卓客户端的站点）在这里可以配置安卓客户端的相关信息，以便本站会员即时更新APP到最新版本。需要更新的APK文件请放置于：网站根目录/myapp.apk 文件名一点要正确！</font><br/><br/>

<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_client'){
$app_version=$_POST['app_version'];
$app_log=$_POST['app_log'];
$app_start_is=$_POST['app_start_is'];
$app_start=$_POST['app_start'];
if($app_version==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('app_version', $app_version);
saveSetting('app_log', $app_log);
saveSetting('app_start_is', $app_start_is);
saveSetting('app_start', $app_start);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}


elseif($my=='set_coin')
{
echo '<div class="w h"><h3>币种及消费规则设定</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_coin">
<div class="form-group">
<label>虚拟币名称:</label><br><input type="text" class="form-control" name="coin_name" value="'.$conf['coin_name'].'">
</div>
<div class="form-group">
<label>一元(RMB)等于多少虚拟币:</label><br><input type="text" class="form-control" name="rules_0" value="'.$rules[0].'" maxlength="9">
</div>
<div class="form-group">
<label>注册初始送币:</label><br><input type="text" class="form-control" name="rules_1" value="'.$rules[1].'" maxlength="9">
</div>
<div class="form-group">
<label>多少虚拟币兑换1月VIP:（0为关闭兑换）</label><br><input type="text" class="form-control" name="coin_tovip" value="'.$conf['coin_tovip'].'" maxlength="9">
</div>
<div class="form-group">
<label>虚拟币总开关:</label><br><select class="form-control" name="jifen""><option value="'.$conf['jifen'].'">'.$conf['jifen'].'</option><option value="1">1_开启</option><option value="0">0_关闭</option></select>
<font color="green">只有开启状态以下设置才会生效</font>
</div>
<div class="form-group">
<label>普通网址任务扣币(条/天):</label><br><input type="text" class="form-control" name="rules_2" value="'.$rules[2].'" maxlength="9">
</div>
<div class="form-group">
<label>网站签到任务扣币(条/天):</label><br><input type="text" class="form-control" name="rules_3" value="'.$rules[3].'" maxlength="9">
</div>
<div class="form-group">
<label>QQ挂机任务扣币(条/天):</label><br><input type="text" class="form-control" name="rules_4" value="'.$rules[4].'" maxlength="9">
</div>
<div class="form-group">
<label>成功协助打码送币:</label><br><input type="text" class="form-control" name="rules_5" value="'.$rules[5].'" maxlength="9">
</div>
<div class="form-group">
<label>被协助打码扣币:</label><br><input type="text" class="form-control" name="rules_6" value="'.$rules[6].'" maxlength="9">
</div>
<br/><br/>

<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_coin'){
$coin_name=$_POST['coin_name'];
$coin_tovip=$_POST['coin_tovip'];
$jifen=$_POST['jifen'];
$rules=array(intval($_POST['rules_0']),intval($_POST['rules_1']),intval($_POST['rules_2']),intval($_POST['rules_3']),intval($_POST['rules_4']),intval($_POST['rules_5']),intval($_POST['rules_6']));

if($coin_name==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('coin_name', $coin_name);
saveSetting('coin_tovip', $coin_tovip);
saveSetting('rules', implode("|",$rules));
saveSetting('jifen', $jifen);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_vip')
{

echo '<div class="w h"><h3>网站VIP规则设定</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_vip">
<div class="form-group">
<label>签到领VIP:(几积分换一天VIP，0为关闭此功能)</label><br><input type="text" class="form-control" name="qd_jifen" value="'.$conf['qd_jifen'].'" maxlength="9">
<font color="green">签到领VIP页面,请自行添加地址到首页合适位置,地址为：/index.php?mod=qd</font>
</div>
<div class="form-group">
<label>VIP会员免扣虚拟币:</label><br><select class="form-control" name="vip_coin""><option value="'.$conf['vip_coin'].'">'.$conf['vip_coin'].'</option><option value="1">1_是</option><option value="0">0_否</option></select>
</div>
<div class="form-group">
<label>只有VIP才能使用的功能:</label><br><select name="vip_func[]" multiple="multiple" class="form-control" style="height:100px;"><option value="0" '.vipfunc_select(0).'>柯林/DZ自动签到</option><option value="1" '.vipfunc_select(1).'>其它自动签到</option><option value="2" '.vipfunc_select(2).'>QQ双协议秒赞</option><option value="3" '.vipfunc_select(3).'>QQ双协议秒评</option><option value="4" '.vipfunc_select(4).'>QQ发图片说说</option><option value="5" '.vipfunc_select(5).'>QQ删除/转发说说</option><option value="6" '.vipfunc_select(6).'>QQ花藤/空间签到</option><option value="7" '.vipfunc_select(7).'>QQVIP/绿钻签到</option><option value="8" '.vipfunc_select(8).'>单向检测,秒赞检测</option><option value="9" '.vipfunc_select(9).'>互赞主页,留言等</option><option value="10" '.vipfunc_select(10).'>刷赞,人气,圈圈赞</option></select>
<font color="green">按住Ctrl键可多选</font>
</div>
<div class="form-group">
<label>只有VIP才能使用的系统:</label><br><input type="text" class="form-control" name="vip_sys" value="'.$conf['vip_sys'].'">
<font color="green">填写系统序号，每个序号之间用“|”隔开。</font>
</div>
<br/><br/>

<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_vip'){
$vip_coin=$_POST['vip_coin'];
$qd_jifen=$_POST['qd_jifen'];
$vip_sys=$_POST['vip_sys'];
$vip_func=implode("|",$_POST['vip_func']);
if($vip_coin==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('vip_coin', $vip_coin);
saveSetting('qd_jifen', $qd_jifen);
saveSetting('vip_func', $vip_func);
saveSetting('vip_sys', $vip_sys);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_daili')
{
echo '<div class="w h"><h3>代理拿卡价格配置</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_daili">
<div class="form-group">
<label>一元(RMB)等于多少虚拟币:</label><br><input type="text" class="form-control" name="rules_0" value="'.$daili_rules[0].'" maxlength="9">
</div>
<div class="form-group">
<label>VIP月卡(RMB/个):</label><br><input type="text" class="form-control" name="rules_1" value="'.$daili_rules[1].'" maxlength="9">
</div>
<div class="form-group">
<label>VIP季度卡(RMB/个):</label><br><input type="text" class="form-control" name="rules_2" value="'.$daili_rules[2].'" maxlength="9">
</div>
<div class="form-group">
<label>VIP半年卡(RMB/个):</label><br><input type="text" class="form-control" name="rules_3" value="'.$daili_rules[3].'" maxlength="9">
</div>
<div class="form-group">
<label>VIP年卡(RMB/个):</label><br><input type="text" class="form-control" name="rules_4" value="'.$daili_rules[4].'" maxlength="9">
</div>
<div class="form-group">
<label>VIP永久卡(RMB/个):</label><br><input type="text" class="form-control" name="rules_5" value="'.$daili_rules[5].'" maxlength="9">
</div>
<br/><br/>

<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}
elseif($my=='ad_daili'){
$daili_rules=array(intval($_POST['rules_0']),intval($_POST['rules_1']),intval($_POST['rules_2']),intval($_POST['rules_3']),intval($_POST['rules_4']),intval($_POST['rules_5']));

if($daili_rules==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('daili_rules', implode("|",$daili_rules));
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='set_api')
{
echo '<div class="w h"><h3>签到/QQ挂机模块API配置</h3></div>
<div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_api">
<div class="form-group">
<label>签到API服务器:</label><br/><select class="form-control" name="apiserver""><option value="'.$conf['apiserver'].'">'.$conf['apiserver'].'</option><option value="1">1_彩虹官方API一号</option><option value="2">2_彩虹官方API二号</option><option value="3">3_彩虹官方API三号</option><!--option value="4">4_彩虹官方API四号</option--><option value="0">0_本地API</option></select>
<font color="green">彩虹官方API由国内各大应用引擎搭建，速度快，稳定性好。如果你当前使用的签到API无法访问可以在此更换。如果你的空间性能足够好也可以选择使用本地API。</font></div>
<div class="form-group">
<label>QQ挂机类API服务器:</label><br/><select class="form-control" name="qqapiid""><option value="'.$conf['qqapiid'].'">'.$conf['qqapiid'].'</option><option value="0">0_本地API</option><option value="1">1_官方API一号(阿里云)需授权</option><option value="2">2_官方API二号(腾讯云)需授权</option><option value="4">4_官方API三号(腾讯云)需授权</option><option value="3">3_自定义API</option></select>
<div id="frame_set" style="display:none;">
<div class="form-group">
<label>自定义QQ挂机API服务器地址:</label><br><input type="text" class="form-control" name="myqqapi" value="'.$conf['myqqapi'].'" placeholder="http://cloud.sgwap.net/">
</div>
</div>
<font color="green">建议选本地API，因为QQ的数量和空间稳定性是呈负相关的。如果你的空间实在无法运行QQ挂机类任务可以尝试使用官方API。官方API是需要域名授权的，授权请联系QQ1277180438</font></div>
<div class="form-group">
<label>QQ登录API服务器:</label><br/><select class="form-control" name="qqloginid""><option value="'.$conf['qqloginid'].'">'.$conf['qqloginid'].'</option><option value="1">1_官方API一号(ACE)</option><option value="2">2_官方API二号(SAE)</option><option value="3">3_官方API三号(ECS)</option><option value="0">0_本地API</option></select>
<font color="green">QQ登录即为添加QQ与更新sid。如果在添加QQ时出现登录成功但获取sid失败，请在此处更换QQ登录API</font></div>
<div class="form-group">
<label>秒赞检测与获取好友列表API:</label><br/><select class="form-control" name="mzjc_api""><option value="'.$conf['mzjc_api'].'">'.$conf['mzjc_api'].'</option><option value="0">0_本地API</option><option value="1">1_官方API(ACE)</option></select>
<font color="green">当本地获取好友列表或秒赞检测无法使用时可以尝试更换官方API</font></div>
<div class="form-group">
<label>发信API服务器:</label><br/><select class="form-control" name="mail_api""><option value="'.$conf['mail_api'].'">'.$conf['mail_api'].'</option><option value="0">0_本地发信</option><option value="1">1_官方API一号</option><option value="2">2_官方API二号</option><option value="3">3_快乐是福API</option></select>
<font color="green">使用此API后，网站将通过官方发信API发送邮件。建议在当前空间不支持发送邮件时使用。</font></div>
<div class="form-group">
<label>圈圈赞接口:</label><br/>
<input type="text" class="form-control" name="qqz_api" value="'.$conf['qqz_api'].'" placeholder="http://61.135.169.125:3331/">
<font color="green">需精确到端口号，填写样例：http://61.135.169.125:3331/ 留空则使用官方API</font></div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>
<script>
          $("select[name=\'qqapiid\']").change(function(){
              if($(this).val() == 3){
                $("#frame_set").css("display","inherit");
              }else{
                $("#frame_set").css("display","none");
              }
          });
</script>
';
}
elseif($my=='ad_api'){
$apiserver=$_POST['apiserver'];
$qqapiid=$_POST['qqapiid'];
$qqloginid=$_POST['qqloginid'];
$mail_api=$_POST['mail_api'];
$mzjc_api=$_POST['mzjc_api'];
$myqqapi=$_POST['myqqapi'];
$qqz_api=$_POST['qqz_api'];
if($apiserver==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} else {
saveSetting('apiserver', $apiserver);
saveSetting('qqapiid', $qqapiid);
saveSetting('qqloginid', $qqloginid);
saveSetting('mail_api', $mail_api);
saveSetting('mzjc_api', $mzjc_api);
saveSetting('myqqapi', $myqqapi);
saveSetting('qqz_api', $qqz_api);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}}

elseif($my=='sqladmin')
{
echo '<div class="w h"><h3>管理系统SQL</h3></div>
<div class="box">
<form action="./mysql/" method="GET">
<input type="hidden" name="dbs" value="'.$host.'">
<input type="hidden" name="dbu" value="'.$user.'">
<input type="hidden" name="dbn" value="'.$dbname.'">
<input type="hidden" name="vmi" value="%E6%89%A7%E8%A1%8C%E6%95%B0%E6%8D%AE%E5%BA%93">
<div class="form-group">
<label>请输入数据库密码:</label><br>
<input type="password" class="form-control" name="dbp" value="">
</div>
<input type="submit" class="btn btn-primary btn-block" value="进入"></form></div>';
}

elseif($my=='syskey')
{
echo '<div class="w h"><h3>SYS_KEY查看</h3></div><div class="box">
<input type="text" value="'.SYS_KEY.'" class="form-control" disabled><br/>
<font color="green">SYS_KEY是安装时随机生成的，网站用于加密数据的密钥，请不要随意泄露。</font>
</div><br/>';
}
elseif($my=='set_mm')
{
$user=$DB->get_row("select * from ".DBQZ."_user where userid='{$conf['adminid']}' limit 1");
echo '<div class="w h"><h3>修改后台密码</h3></div><div class="box"><form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_mm"><input type="hidden" name="password" value="'.$conf['adminpass'].'">管理员账号:<br><input type="text" class="form-control" name="account" value="'.$user['user'].'"><br>管理员密码:<br><input type="text" class="form-control" name="pass" value="'.$user['pass'].'"><br/><input type="submit"
class="btn btn-primary btn-block" value="确定修改"></form></div>';
}

elseif($my=='ad_mm')
{
$account=$_POST['account'];
$pass=$_POST['pass'];
if($account=='' || $pass==''){
showmsg('用户名密码不能为空!',3);
} else {
saveSetting('account', $account);
saveSetting('pass', $pass);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}
}

elseif($my=='set_gg'){
echo '<div class="w h"><h3>广告与公告配置</h3></div><div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_gg">
<div class="form-group">
<label>首页公告栏:</label><br><textarea class="form-control" name="gg" rows="5">'.$conf['gg'].'</textarea>
</div>
<div class="form-group">
<label>首页强力推荐:</label><br><textarea class="form-control" name="guang" rows="4">'.$conf['guang'].'</textarea>
</div>
<div class="form-group">
<label>首页底部:</label><br><textarea class="form-control" name="bottom" rows="4">'.$conf['bottom'].'</textarea>
</div>
<div class="form-group">
<label>自助购买页公告:</label><br><textarea class="form-control" name="shop" rows="4">'.$conf['shop'].'</textarea>
</div>
<div class="form-group">
<label>全局底部排版:</label><br><textarea class="form-control" name="footer" rows="4">'.$conf['footer'].'</textarea>
</div>
<input type="submit" class="btn btn-primary btn-block"
value="确定修改"></form></div>';
}

elseif($my=='ad_gg'){
$gg=$_POST['gg'];
$guang=$_POST['guang'];
$bottom=$_POST['bottom'];
$shop=$_POST['shop'];
$footer=$_POST['footer'];
saveSetting('gg', $gg);
saveSetting('guang', $guang);
saveSetting('bottom', $bottom);
saveSetting('shop', $shop);
saveSetting('footer', $footer);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}

elseif($my=='set_css'){
echo '<div class="w h"><h3>更改系统皮肤样式</h3></div><div class="box">
<form action="index.php?mod=admin-set" method="POST"><input type="hidden" name="my" value="ad_css">
<div class="form-group">
<label>触屏/电脑版皮肤样式:</label><br>
<select class="form-control" name="css2"><option value="'.$conf['css2'].'">'.$conf['css2'].'</option><option value="1">1_Bootstrap原版</option><option value="2">2_Skeumorphism UI</option><option value="3">3_Metro风格Flat UI</option><option value="4">4_高仿谷歌扁平样式</option><option value="5">5_Windows8 Metro UI</option><option value="0">0_禁用触屏版</option></select></div>
<div class="form-group">
<label>手机炫彩版皮肤样式:</label><br>
<select class="form-control" name="css"><option value="'.$conf['css'].'">'.$conf['css'].'</option><option value="1">1_立体炫彩</option><option value="2">2_简洁经典</option><option value="3">3_碧海蓝天</option><option value="4">4_金色年华</option><option value="5">5_高仿chen4.6</option><option value="6">6_七彩阳光</option></select></div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form></div>';
}

elseif($my=='ad_css'){
$css=$_POST['css'];
$css2=$_POST['css2'];
saveSetting('css', $css);
saveSetting('css2', $css2);
$ad=$CACHE->clear();
if($ad){showmsg('修改成功!',1);
}else{showmsg('修改失败!'.$DB->error(),4);}
}

elseif($my == 'qunfa'){
echo'<div class="w h"><h3>邮件群发</h3></div>';
echo'<div class="box">';
if(isset($_POST['title'])){
	include_once(ROOT."includes/mail.class.php");
	$title = daddslashes($_POST['title']);
	$content = daddslashes($_POST['content']);
	$content = nl2br(htmlspecialchars($content));
	$content .= "<p style=\"padding: 1.5em 1em 0; color: #999; font-size: 12px;\">—— 本邮件由 {$conf['sitename']} (<a href=\"{$siteurl}\">{$siteurl}</a>) 管理员发送</p>";

	$mail = new MySendMail();
	$mail->setServer($conf['mail_stmp'], $conf['mail_name'], $conf['mail_pwd'], $conf['mail_port']);
	$mail->setFrom($conf['mail_name']);
	$query = $DB->query("SELECT email FROM ".DBQZ."_user where email!=''");
	while($result = $DB->fetch($query)){
		$mail->setReceiver($result['email']);
	}
	$mail->setMailInfo($title, $content);
	$mail->sendMail();
	echo '<p>发送结果：'.$mail->result.'</p>';
}
echo'<p>此功能用于向本站已经注册的所有用户发送邮件公告</p>
<p>为避免用户反感，建议您不要经常发送邮件</p>';
echo'
<form method="post" action="index.php?mod=admin-set&my=qunfa">
<input type="hidden" name="do" value="1">
<div class="form-group">
<label>邮件标题：</label><br>
<input class="form-control" type="text" name="title"/>
<div class="form-group">
<label>邮件内容：</label><br>
<textarea class="form-control" name="content" rows="10"></textarea>
<input type="submit" class="btn btn-primary btn-block" value="确认发送"></form>
</form>';
echo'</div>';
}

elseif($my=='logo'){
echo '<div class="w h"><h3>更改系统LOGO</h3></div><div class="box">';
if($_POST['s']==1){
copy($_FILES['file']['tmp_name'], ROOT.'images/logo.png');
echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
}
echo '<br><form action="index.php?mod=admin-set&my=logo" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认更改LOGO" /></form><br>现在的LOGO：<br><img src="images/logo.png">';
echo '</div>';
}

elseif($my == 'bj'){
echo '<div class="w h"><h3>更改背景图片</h3></div><div class="box">';
if($_POST['s']==1){
copy($_FILES['file']['tmp_name'], ROOT.'images/b.gif');
echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
}
echo '<br><form action="index.php?mod=admin-set&my=bj" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认更改背景" /></form><br>现在的背景：<br><img src="images/b.gif"><br>';
echo '</div>';
}

elseif($my == 'bj2'){
echo '<div class="w h"><h3>更改触屏版背景图片</h3></div><div class="box">';
if($_POST['s']==1){
copy($_FILES['file']['tmp_name'], ROOT.'images/fzbeijing.png');
echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
}
echo '<br><form action="index.php?mod=admin-set&my=bj2" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认更改背景" /></form><br>现在的背景：<br><img src="images/fzbeijing.png"><br>';
echo '</div>';
}

elseif($my == 'info'){
echo'<div class="w h"><h3>程序信息</h3></div>';
echo'<div class="box">';
echo'版权所有：消失的彩虹海<br/>ＱＱ：1277180438<br/>当前版本：V6.8 (Build '.VERSION.')<br/>官方网站：<a href="http://blog.cccyun.cn">blog.cccyun.cn</a><br/><a href="http://cron.sgwap.net">cron.aliapp.com</a>
';
echo'</div>';
}

elseif($my == 'help'){
echo'<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">任务监控说明</h3></div>';
echo'<div class="panel-body box">';
if(!empty($conf['cronkey']))$ext='&key='.$conf['cronkey'];
echo '你可以根据需要监控以下文件：<br/><font color=brown>';
for($i=1;$i<=$conf['sysnum'];$i++) {
	echo "<li class=\"list-group-item\">{$siteurl}cron/job.php?sys={$i}{$ext}</li>";
}
echo "<li class=\"list-group-item\">{$siteurl}cron/newsid.php?{$ext}</font></li>";
echo'
监控完监控网址后即可执行任务！<br/>
<font color="red">如果你的空间开启了防CC攻击，或者使用了加速乐等CDN，请将本站服务器IP:'.$_SERVER["SERVER_ADDR"].' 加入到白名单中，否则任务将无法执行。</font><br/>
推荐监控系统:<br/>
<a target="_blank" href="http://cron.sgwap.net/">http://cron.sgwap.net/</a>（正版用户可联系彩虹免费获得永久VIP）<br/>
<a target="_blank" href="http://console.aliyun.com/jiankong/">http://console.aliyun.com/jiankong/</a>（需实名认证）<br/>
<a target="_blank" href="http://jk.cloud.360.cn/">http://jk.cloud.360.cn/</a><br/>
<a target="_blank" href="http://bce.baidu.com/product/bcm.html">http://bce.baidu.com/product/bcm.html</a><br/>
';
echo'</div></div>';
}

}
else
{
showmsg('后台管理登录失败。请以管理员身份 <a href="index.php?mod=login">重新登录</a>！',3);
}
echo'<div class="copy">';
echo date("Y年m月d日 H:i:s");
echo'<br>';
echo'<a href="index.php?mod=admin">返回后台管理</a>-<a href="index.php">返回首页</a>';
include(ROOT.'includes/foot.php');
echo'</div></div></div></div></body></html>';
?>