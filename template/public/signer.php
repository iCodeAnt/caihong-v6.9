<?php
if(!defined('IN_CRONLITE'))exit();
$title="添加签到任务";
include_once(TEMPLATE_ROOT."head.php");

$jobid=isset($_GET['jobid'])?$_GET['jobid']:null;
$baseUrl = urlencode($siteurl);

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1){

$sysselect='<p><label>请选择任务系统:</label><br><select class="form-control" name="sys">';
$show=explode('|',$conf['show']);
for($i=0;$i<$conf['sysnum'];$i++){
	$sysid=$i+1;
	$addstr='';
	$addstr2='';
	$all_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$sysid'");
	if($all_sys>=$conf['max']){$sysnum=-1;$addstr='爆满';}
	else $sysnum=$sysid;
	if(in_array($sysid,$vip_sys))$addstr2='[VIP]';
	$sysselect.='<option value="'.$sysnum.'">系统'.$sysname[$sysid].'-'.$show[$i].'一次'.$addstr2.' ('.$all_sys.'/'.$conf['max'].')'.$addstr.'</option>';
}
$sysselect.='</select></p>';

$getcookie = ($apiserverid!=0) ? $apiserver.'sign/getcookie/' : '../sign/getcookie/';
$dzlogin = <<<HTML
<br/><font color="green">(密码模式仅适用于登录不需要验证码的论坛，cookie模式适用于所有论坛)</font><hr/>
<p><label>网站域名:（不要加“http://”）</label><br/>
<input type="text" class="form-control" name="u" value=""/></p>
<p><label>用户名:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
<p><label>密码提示问题ID（没有的话默认为0）:</label><br/>
<input type="text" class="form-control" name="quest" value="0"/></p>
<p><label>密码提示问题答案（没有的话默认为空）:</label><br/>
<input type="text" class="form-control" name="answ"/></p>
HTML;
$dzlogin2 = <<<HTML
<br/><font color="green">(密码模式仅适用于登录不需要验证码的论坛，cookie模式适用于所有论坛)</font><hr/>
<p><label>Cookie-ID:(<a href="{$getcookie}" target="_blank">点击获取Cookie-ID</a>)</label><br/>
<input type="text" class="form-control" name="id"/></p>
HTML;
coin_display(3);
if($_GET['my']=='default'){
echo '<div class="w h">添加签到任务</div>';
echo '<div class="box">';
echo '<a href="index.php?mod=signer&my=klqd&sys='.$sysid.$link.'"><img src="images/icon/kelink.ico" class="logo">添加柯林自动签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=dzsign&sys='.$sysid.$link.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz自动签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=dzdk&sys='.$sysid.$link.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz自动打卡任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=dztask&sys='.$sysid.$link.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz任务助手任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=dzol&sys='.$sysid.$link.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz挂积时任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=115&sys='.$sysid.$link.'"><img src="images/icon/115.ico" class="logo">添加115网盘签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=360yunpan&sys='.$sysid.$link.'"><img src="images/icon/360yunpan.ico" class="logo">添加360云盘签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=vdisk&sys='.$sysid.$link.'"><img src="images/icon/vdisk.ico" class="logo">添加新浪微盘签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=xiami&sys='.$sysid.$link.'"><img src="images/icon/xiami.ico" class="logo">添加虾米音乐签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=fuliba&sys='.$sysid.$link.'"><img src="images/icon/fuliba.ico" class="logo">添加福利论坛签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=3gwen&sys='.$sysid.$link.'"><img src="images/icon/3gwen.ico" class="logo">添加文网自动签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=dyml&sys='.$sysid.$link.'"><img src="images/icon/dyml.ico" class="logo">添加刀云论坛签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=cmgq&sys='.$sysid.$link.'"><img src="images/icon/caomei.ico" class="logo">添加草莓挂Q签到任务</a><hr/>';
echo '<a href="index.php?mod=signer&my=chenqd&sys='.$sysid.$link.'"><img src="images/icon/chen.ico" class="logo">添加CHEN挂Q签到任务</a><hr/>';
echo '</div>';
}

elseif($_GET['my']=='klqd'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'kelink/qdnew/qd.php?backurl='.$baseUrl : '../sign/klqd.php';
echo <<<HTML
<div class="w h"><h3>添加柯林网站自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>网站域名:（不要加“http://”）</label><br/>
<input type="text" class="form-control" name="ym"/></p>
<p><label>用户名/手机/id:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码: [<a href="index.php?mod=signer&my=klqd2&sys={$sysid}{$link}">切换SID模式</a>]</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
<p><label>签到内容:</label><br/>
<input type="text" class="form-control" name="txt"/></p>
<p><label>siteid:</label><br/>
<input type="text" class="form-control" name="siteid" value="1000"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.mrpyx.cn</font>
</div>
HTML;
}

elseif($_GET['my']=='klqd2'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'kelink/klqd/qd.php?backurl='.$baseUrl : '../sign/klqd2.php';
echo <<<HTML
<div class="w h"><h3>添加柯林网站自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>网站域名:（不要加“http://”）</label><br/>
<input type="text" class="form-control" name="u"/></p>
<p><label>签到内容(5字内):</label><br/>
<input type="text" class="form-control" name="content"/></p>
<p><div><label>siteid:</label></div>
<input type="text" class="form-control" name="siteid" value="1000"/></p>
<p><div><label>SID: (<a href="{$apiserver}kelink/kelinksid/">提取SID</a>)[<a href="index.php?mod=signer&my=klqd&sys={$sysid}{$link}">切换密码模式</a>]</label></div>
<input type="text" class="form-control" name="sid"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.mrpyx.cn<br/>
建议使用<a href="index.php?mod=signer&my=klqd&sys={$sysid}{$link}">密码模式</a>，sid模式可能会因为sid失效而无法签到。</font>
</div>
HTML;
}

elseif($_GET['my']=='dzsign'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuz/do.php?backurl='.$baseUrl : '../sign/dzsign.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[密码模式][<a href="index.php?mod=signer&my=dzsign2&sys={$sysid}{$link}">cookie模式</a>]
{$dzlogin}
<p>签到内容：论坛签到天天好心情</p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
本签到机只针对Discuz!的DSU每日打卡插件。本身没有开放签到功能的论坛无法签到。如果点击签到后提示“签到失败”也不一定是真的失败，有可能是程序未检测到签到成功的页面，实际能否签到成功以论坛显示为准。</font>
</div>
HTML;
}

elseif($_GET['my']=='dzsign2'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuz/do2.php?backurl='.$baseUrl : '../sign/dzsign2.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[<a href="index.php?mod=signer&my=dzsign&sys={$sysid}{$link}">密码模式</a>][cookie模式]
{$dzlogin2}
<p>签到内容：论坛签到天天好心情</p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
本签到机只针对Discuz!的DSU每日打卡插件。本身没有开放签到功能的论坛无法签到。如果点击签到后提示“签到失败”也不一定是真的失败，有可能是程序未检测到签到成功的页面，实际能否签到成功以论坛显示为准。</font>
</div>
HTML;
}

elseif($_GET['my']=='dzdk'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuzdk/do.php?backurl='.$baseUrl : '../sign/dzdk.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz自动打卡任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[密码模式][<a href="index.php?mod=signer&my=dzdk2&sys={$sysid}{$link}">cookie模式</a>]
{$dzlogin}
<p><label>打卡插件类型:</label><br/>
<select class="form-control" name="method">
<option value="amupper">DSU Amupper</option>
<option value="ljdaka">亮剑打卡</option>
</select></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
本签到机支持Discuz!的dsu_amupper打卡插件和亮剑打卡插件。本身没有此类插件的论坛无法签到。如果点击签到后提示“签到失败”也不一定是真的失败，有可能是程序未检测到签到成功的页面，实际能否签到成功以论坛显示为准。</font>
</div>
HTML;
}

elseif($_GET['my']=='dzdk2'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuzdk/do2.php?backurl='.$baseUrl : '../sign/dzdk2.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz自动打卡任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[<a href="index.php?mod=signer&my=dzdk&sys={$sysid}{$link}">密码模式</a>][cookie模式]
{$dzlogin2}
<p><label>打卡插件类型:</label><br/>
<select class="form-control" name="method">
<option value="amupper">DSU Amupper</option>
<option value="ljdaka">亮剑打卡</option>
</select></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
本签到机支持Discuz!的dsu_amupper打卡插件和亮剑打卡插件。本身没有此类插件的论坛无法签到。如果点击签到后提示“签到失败”也不一定是真的失败，有可能是程序未检测到签到成功的页面，实际能否签到成功以论坛显示为准。</font>
</div>
HTML;
}

elseif($_GET['my']=='dztask'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuztask/do.php?backurl='.$baseUrl : '../sign/dztask.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz任务助手任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[密码模式][<a href="index.php?mod=signer&my=dztask2&sys={$sysid}{$link}">cookie模式</a>]
{$dzlogin}
<p><label>任务ID:</label><br/>
<input type="text" class="form-control" name="task"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
利用本系统可以自动完成一些Discuz论坛的每日性领币任务，任务ID就是“申请任务”链接中“task=”后面的数字。</font>
</div>
HTML;
}

elseif($_GET['my']=='dztask2'){
vipfunc_check(0);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuztask/do2.php?backurl='.$baseUrl : '../sign/dztask2.php';
echo <<<HTML
<div class="w h"><h3>添加Discuz任务助手任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[<a href="index.php?mod=signer&my=dztask&sys={$sysid}{$link}">密码模式</a>][cookie模式]
{$dzlogin2}
<p><label>任务ID:</label><br/>
<input type="text" class="form-control" name="task"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi<br/>
利用本系统可以自动完成一些Discuz论坛的每日性领币任务，任务ID就是“申请任务”链接中“task=”后面的数字。</font>
</div>
HTML;
}

elseif($_GET['my']=='dzol'){
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuzol/do.php?backurl='.$baseUrl : '../sign/dzol.php'.'&backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加Discuz挂积时任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[密码模式][<a href="index.php?mod=signer&my=dzol2&sys={$sysid}{$link}">cookie模式</a>]
{$dzlogin}
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi</font>
</div>
HTML;
}

elseif($_GET['my']=='dzol2'){
$signurl = ($apiserverid!=0) ? $apiserver.'sign/discuzol/do2.php?backurl='.$baseUrl : '../sign/dzol2.php'.'&backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加Discuz挂积时任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
[<a href="index.php?mod=signer&my=dzol&sys={$sysid}{$link}">密码模式</a>][cookie模式]
{$dzlogin2}
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网站域名格式如www.fuliba.mobi</font>
</div>
HTML;
}

elseif($_GET['my']=='115'){
vipfunc_check(1);
$signurl = $apiserver.'sign/115/do.php?backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加115网盘自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
登录你的115网盘账户：<br/>
<p><label>用户名:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网络任务执行频率一天1～2次即可，过于频繁地登录会导致115网盘登录出现验证码而签到失败。</font>
</div>
HTML;
}

elseif($_GET['my']=='360yunpan'){
vipfunc_check(1);
$signurl = $apiserver.'sign/360yunpan/do.php?backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加360云盘自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
登陆你的360账户：<br/>
<p><label>用户名:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网络任务执行频率一天1～2次即可，过于频繁地登录会导致360登录出现验证码而签到失败。</font>
</div>
HTML;
}

elseif($_GET['my']=='vdisk'){
vipfunc_check(1);
$signurl = $apiserver2.'sign/vdisk/do.php?backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加新浪微盘自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
登陆你的新浪微博账户：<br/>
<p><label>用户名(邮箱):</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
<p><label>是否转发微博:(签到后发微博会获得更多空间)</label><br/>
<select class="form-control" name="weibo" ivalue="false"><option value="false">否</option><option value="true">是</option></select></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网络任务执行频率一天1～2次即可，过于频繁地登录会出现提示“登录次数过于频繁”而签到失败，严重的会被冻结账号。<br/><br/>如果出现“<b>需要输入验证码</b>”，请在新浪微博的[账号设置]—[账号安全]—[登录保护]里将本服务器所在地“<b>北京</b>”加入不需要输入验证码，如下图。</font><img src="http://51tool.jd-app.com/sign/vdisk/screenshot.jpg">
</div>
HTML;
}

elseif($_GET['my']=='xiami'){
vipfunc_check(1);
$signurl = $apiserver.'sign/xiami/do.php?backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加虾米音乐自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
登录你的虾米账户：<br/>
<p><label>用户名:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>网络任务执行频率一天1～2次即可，过于频繁地登录会导致虾米登录出现验证码而签到失败。</font>
</div>
HTML;
}

elseif($_GET['my']=='fuliba'){
vipfunc_check(1);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/fuliba/do.php?backurl='.$baseUrl : '../sign/fuliba.php'.'&backurl='.$baseUrl;
echo <<<HTML
<div class="w h"><h3>添加福利论坛签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>Cookie-ID:(<a href="{$getcookie}" target="_blank">点击获取Cookie-ID</a>)</label><br/>
<input type="text" class="form-control" name="id"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>本签到工具仅适用于福利吧论坛（http://bbs.fuli.ba）签到。<br/>
请先点击获取Cookie-ID，获取过程中如果提示获取失败请多试几次。</font>
</div>
HTML;
}

elseif($_GET['my']=='3gwen'){
vipfunc_check(1);
$signurl = ($apiserverid!=0) ? $apiserver.'sign/3gwen/do.php?backurl='.$baseUrl : '../sign/3gwen.php';
echo <<<HTML
<div class="w h"><h3>添加文网自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>Myid:(<a href="http://51tool.aliapp.com/sign/3gwen/myid.php">提取Myid</a>)</label><br/>
<input type="text" class="form-control" name="myid"/></p>
<p><label>签到内容:</label><br/>
<input type="text" class="form-control" name="txt"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>Myid可在文网登录后的网址中直接获得。</font>
</div>
HTML;
}

elseif($_GET['my']=='dyml'){
vipfunc_check(1);
$signurl = ($apiserverid!=0) ? $apiserver3.'sign/dyml/qd.php?backurl='.$baseUrl : '../sign/dyml.php';
echo <<<HTML
<div class="w h"><h3>添加刀云论坛自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>用户名:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
HTML;
}

elseif($_GET['my']=='cmgq'){
vipfunc_check(1);
$signurl = '../sign/cmgq.php';
echo <<<HTML
<div class="w h"><h3>添加草莓挂Q自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>帐号:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
<p><label>签到内容:（可留空）</label><br/>
<input type="text" class="form-control" name="txt"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
<a href="javascript:history.back();">返回上一页</a>
</div>
<div class="row well">
<font color="blue">提示：
帐号名称含中文可能无法签到！<br>签到内容留空则默认为‘签个到～’！</font>
</div>
HTML;
}

elseif($_GET['my']=='chenqd'){
vipfunc_check(1);
$signurl = '../sign/chenqd.php';
echo <<<HTML
<div class="w h"><h3>添加CHEN挂Q自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<p><label>网站域名:</label><br/>
<input type="text" class="form-control" name="url"/></p>
<p><label>帐号:</label><br/>
<input type="text" class="form-control" name="user"/></p>
<p><label>密码:</label><br/>
<input type="text" class="form-control" name="pwd"/></p>
<p><label>签到内容:（可留空）</label><br/>
<input type="text" class="form-control" name="txt"/></p>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="签到" onclick="this.value='请稍后...';form.submit();"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
<a href="javascript:history.back();">返回上一页</a>
</div>
<div class="row well">
<font color="blue">提示：
网站域名例如 http://chgq.aliapp.com
签到内容留空则默认为‘签个到～’！</font>
</div>
HTML;
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