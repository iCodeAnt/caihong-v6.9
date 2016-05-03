<?php
if(!defined('IN_CRONLITE'))exit();
$title="添加QQ挂机任务";
include_once(TEMPLATE_ROOT."head.php");

$my=isset($_GET['my'])?$_GET['my']:'default';
$qq=isset($_GET['qq'])?daddslashes($_GET['qq']):null;
$jobid=isset($_GET['jobid'])?intval($_GET['jobid']):null;

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1){

if(!$jobid){
$qqrow=array('msg'=>'您好！我在挂Q，暂时无法回复您。','content'=>'[随机]','nr'=>'语录','img'=>'','ua'=>'iPhone 6 Plus');
}else{//获取jobid任务数据
$row1=$DB->get_row("SELECT *FROM ".DBQZ."_job where jobid='{$jobid}' limit 1");
$sysid=$row1['sysid'];
$qqrow=json_decode($row1['url'],true);
$my=$qqrow['func'];
$qq=$qqrow['qq'];
}
$signurl='index.php?mod=sc&my=qqjob&jobid='.$jobid.'&func='.$my.'&qq='.$qq;
$pl=isset($row1['pl'])?$row1['pl']:0;

$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
if($row['lx']!=$gl && $isadmin!=1)
{showmsg('你只能操作自己的QQ哦！',3);
exit;
}

$sysselect='<label>任务运行时段:</label><br/>
<select class="form-control" style="width:40%;display:inline" name="start"">
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
</select>&nbsp;时-&nbsp;<select class="form-control" style="width:40%;display:inline" name="stop">
<option value="24">24</option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
</select>&nbsp;时<br><br>';
if($my=='qqsign'||$my=='scqd'||$my=='vipqd'||$my=='lzqd'||$my=='payqd')$pl='18000';
if($my=='qqss')$pl='1800';
if($my=='3gqq')$pl='600';
if($my=='quantu')$pl='600';
$sysselect.='<label>运行频率(秒/次):</label>(<u><a href="index.php?mod=sc&my=sj">时间公式</a></u>)<br>
<input type="text" class="form-control" name="pl" value="'.$pl.'"><font color="green">默认为0，即当前系统的最快运行频率</font><br/><br/>';
$sysselect.='<label>请选择任务系统:</label><br><select class="form-control" name="sys">';
$show=explode('|',$conf['show']);
if($jobid) {
$i=$sysid-1;
$all_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$sysid'");
$sysselect.='<option value="'.$sysid.'">当前系统'.$sysname[$sysid].'-'.$show[$i].'一次 ('.$all_sys.'/'.$conf['max'].')</option>';
}
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
$sysselect.='</select><br/>';

echo '<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq='.$qq.'">'.$qq.'</a></li>
  <li class="active">添加任务</li>
</ol>';
if($my=='default'){
echo '<div class="w h">添加QQ挂机任务</div>';
echo '<div class="box">';
//echo '<a href="index.php?mod=qqjob&my=guaq&qq='.$qq.$link.'"><img src="images/icon/qq.ico" class="logo">添加手机QQ挂机(机器人)任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=3gqq&qq='.$qq.$link.'"><img src="images/icon/3gqq.ico" class="logo">添加３ＧＱＱ挂机任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=zan&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说秒赞任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=pl&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说秒评任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=qqsign&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间自动签到任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=qqss&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加发表图片说说任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=ht&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间花藤挂机任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=zfss&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加好友说说转发任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=del&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说删除任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=delll&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间留言删除任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=quantu&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说圈图任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=zyzan&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间互赞主页任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=liuyan&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间互刷留言任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=gift&qq='.$qq.$link.'"><img src="images/icon/qzone.ico" class="logo">添加空间互送礼物任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=scqd&qq='.$qq.$link.'"><img src="images/icon/3gqq.ico" class="logo">添加书城自动签到任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=lzqd&qq='.$qq.$link.'"><img src="images/icon/yqq.ico" class="logo">添加绿钻自动签到任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=vipqd&qq='.$qq.$link.'"><img src="images/icon/chen.ico" class="logo">添加VIP 自动签到任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=payqd&qq='.$qq.$link.'"><img src="images/icon/iyouxi.ico" class="logo">添加钱包自动签到任务</a><hr/>';
echo '<a href="index.php?mod=qqjob&my=qqpet&qq='.$qq.$link.'"><img src="images/icon/qqpet.ico" class="logo">添加ＱＱ宠物挂机任务</a><hr/>';
echo '</div>';
}


elseif($my=='guaq'){
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加手机QQ挂机(机器人)任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="手机QQ挂机任务"/><input type="hidden" name="pwd" value="{$row['pw']}"/>
<label>QQ:{$qq}</label><br/>
<br><label>自动回复方式</label><br>
<select class="form-control" name="method">
<option value="diy">自定义回复语</option>
<option value="robot">智能机器人(茉莉API)</option>
<option value="robot2">智能机器人(星空API)</option>
<option value="robot3">智能机器人(小幽API)</option>
<option value="no">不自动回复</option>
</select>
<br/><label>自定义回复语:</label><br/>
<input type="text" class="form-control" name="msg" value="{$qqrow['msg']}"/><br/>
{$sysselect}
添加成功后，请务必点击[手动运行测试]来输入登录验证码<br/>
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此为手机QQ模式挂Q，与3GQQ不同，可以获得更多的活跃天数。<br/>监控频率最好1分钟一次，否则可能会掉线。每天上线时间至少6小时。</font>
</div>
HTML;
}

elseif($my=='3gqq'){
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加3GQQ挂机任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="3GQQ挂机任务"/>
QQ:{$qq}<br/>
<input type="hidden" name="sid" value="{$row['sid']}"/><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>3GQQ运行频率为15分钟一次即可。</font>
</div>
HTML;
}

elseif($my=='zan'){
vipfunc_check(2);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间说说秒赞任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间说说秒赞任务"/>
QQ:{$qq}<br/>
<input type="hidden" name="sid" value="{$row['sid']}"/>
<label>设置秒赞协议：</label><br>
<select class="form-control" name="method">
<option value="2">触屏版协议</option>
<option value="4">PC版协议New</option>
<option value="3">PC版协议</option>
</select>
<font color="green">提示：推荐用PC协议，不会提示操作频繁。但由于PC协议使用的是skey，基本每天失效一次，需要时常来更新。</font><br/><br/>
<label>不秒赞以下QQ（每个QQ号之间用|隔开）:</label><br/>
<input type="text" class="form-control" name="forbid" value="{$qqrow['forbid']}"/><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>运行频率视自己需要而定</font>
</div>
HTML;
}

elseif($my=='pl'){
vipfunc_check(3);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间说说秒评任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间说说秒评任务"/>
QQ:{$qq}<br/>
<input type="hidden" name="sid" value="{$row['sid']}"/>
<label>设置秒评协议：</label><br>
<select class="form-control" name="method">
<option value="2">触屏版协议</option>
<option value="3">PC版协议</option>
</select>
<font color="green">提示：推荐用PC协议，不会提示操作频繁。但由于PC协议使用的是skey，基本每天失效一次，需要时常来更新。</font><br/><br/>
<label>评论内容:</label><br/>
<input type="text" class="form-control" name="content" value="{$qqrow['content']}"/><br/>
<label>不评论以下QQ（每个QQ号之间用|隔开）:</label><br/>
<input type="text" class="form-control" name="forbid" value="{$qqrow['forbid']}"/><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>运行频率视自己需要而定。评论内容处写[随机]可使用随机语录评论。</font><font color="red">发言过于频繁可能会被腾讯禁言！</font>
</div>
HTML;
}

elseif($my=='qqsign'){
vipfunc_check(6);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间自动签到任务"/>
QQ:{$qq}<br/>
<label>设置签到协议：</label><br>
<select class="form-control" name="method">
<option value="2">触屏版协议</option>
<option value="3">PC版协议</option>
</select><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>运行频率设置为12小时一次即可</font>
</div>
HTML;
}

elseif($my=='qqss'){
vipfunc_check(4);
coin_display(4);
echo <<<HTML
<script language="javascript" type="text/javascript">
function Addstr(str) {
  var nr = document.getElementsByName("nr")[0];
  nr.value = str;
}
function Addstr2(str) {
  var nr = document.getElementsByName("img")[0];
  nr.value = str;
}
</script>
<div class="w h"><h3>添加自动发表图片说说任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="自动发图片说说任务"/>
QQ:{$qq}<br/>
<label>设置说说协议：</label><br>
<select class="form-control" name="method">
<option value="2">触屏版协议</option>
<option value="3">PC版协议</option>
</select>
<br/><label>内容:</label><br/>
<input type="text" class="form-control" name="nr" value="{$qqrow['nr']}"/>
<font color="blue">选填内容：<a href="#" onclick="Addstr('语录');return false">语录</a>、<a href="#" onclick="Addstr('笑话');return false">笑话</a>、<a href="#" onclick="Addstr('表情');return false">表情</a>、<a href="#" onclick="Addstr('时间');return false">时间</a>、或自定义</font><br/>
<br/><label>图片地址:</label><br/>
<input type="text" class="form-control" name="img" value="{$qqrow['img']}" placeholder="不需要请留空"/>
<font color="blue">选填内容：<a href="#" onclick="Addstr2('随机');return false">随机</a>、<a href="#" onclick="Addstr2('搞笑');return false">搞笑</a>、或自定义图片URL。</font><br/>
<br/><label>浏览器UA:</label><br/>
<input type="text" class="form-control" name="ua" value="{$qqrow['ua']}"/><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>随机笑话由无道API提供。<br/>搞笑图片由百思不得姐随机图片提供。<br/></font><font color="red">发言过于频繁可能会被腾讯禁言！</font>
</div>
HTML;
}

elseif($my=='del'){
vipfunc_check(5);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间说说删除任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间说说删除任务"/>
QQ:{$qq}<br/>
<label>设置运行协议：</label><br>
<select class="form-control" name="method">
<option value="2">触屏版协议</option>
<option value="3">PC版协议</option>
</select><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/></font><font color="red">此功能将删除你的所有说说，请谨慎添加此任务。</font><br/>
</div>
HTML;
}

elseif($my=='zfss'){
vipfunc_check(5);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加好友说说转发任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="好友说说转发任务"/>
QQ:{$qq}<br/>
<label>好友QQ:（每个QQ号之间用|隔开）</label><br/>
<input type="text" class="form-control" name="uin" value="{$qqrow['uin']}"/><br/>
<label>转发原因:</label><br/>
<input type="text" class="form-control" name="reason" value="{$qqrow['reason']}" placeholder="可留空"/><br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此功能为转发好友的全部说说，不需要频繁运行。</font><br/>
</div>
HTML;
}

elseif($my=='ht'){
vipfunc_check(6);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加QQ空间花藤挂机任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间花藤挂机任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此QQ空间花藤挂机可自动浇花、修剪、日照、施肥。需要事先领养花藤。</font>
</div>
HTML;
}

elseif($my=='scqd'){
vipfunc_check(7);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加书城自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="书城自动签到任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
HTML;
}

elseif($my=='vipqd'){
vipfunc_check(7);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加VIP自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="VIP自动签到任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>QQ会员签到领取成长值</font>
</div>
HTML;
}

elseif($my=='lzqd'){
vipfunc_check(7);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加绿钻自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="绿钻自动签到任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
HTML;
}

elseif($my=='payqd'){
vipfunc_check(7);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加QQ钱包自动签到任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="钱包自动签到任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>QQ钱包签到需要先绑定银行卡</font>
</div>
HTML;
}

elseif($my=='zyzan'){
vipfunc_check(9);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间互赞主页任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间互赞主页任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此功能会调用网站的QQ为你刷赞。</font>
</div>
HTML;
}

elseif($my=='liuyan'){
vipfunc_check(9);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间互刷留言任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间互刷留言任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此功能会调用网站的QQ为你刷留言。</font><font color="red">发言过于频繁可能会被腾讯禁言！</font>
</div>
HTML;
}

elseif($my=='gift'){
vipfunc_check(9);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间互送礼物任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间互送礼物任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此功能会调用网站的QQ为你送空间小礼物。</font><font color="red">发言过于频繁可能会被腾讯禁言！</font>
</div>
HTML;
}

elseif($my=='delll'){
vipfunc_check(5);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加删除空间留言任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="删除空间留言任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
HTML;
}

elseif($my=='quantu'){
vipfunc_check(5);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加空间说说圈图任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="空间说说圈图任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
HTML;
}

elseif($my=='qqpet'){
vipfunc_check(7);
coin_display(4);
echo <<<HTML
<div class="w h"><h3>添加QQ宠物挂机任务</h3></div>
<div class="box">
<form action="{$signurl}" method="POST">
<input type="hidden" name="mc" value="QQ宠物挂机任务"/>
QQ:{$qq}<br/>
{$sysselect}
<p><input type="submit" class="btn btn-primary btn-block" value="提交"/>
<input type="reset" class="btn btn-default btn-block" value="重填" /></p></form>
[ <a href="javascript:history.back();">返回上一页</a> ]
</div>
<div class="row well">
<font color="blue">提示：<br/>此功能包括QQ宠物自动签到、洗澡、喂食、看病、打工、一键收获等。</font>
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