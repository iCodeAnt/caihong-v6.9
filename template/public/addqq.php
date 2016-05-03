<?php
 /*
　*添加QQ挂机
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="添加QQ挂机";
include_once(TEMPLATE_ROOT."head.php");

if($islogin==1){


if($theme=='default')echo '<div class="col-md-9" role="main">';
if($theme=='mobile')echo '<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>';

if($_GET['my']=='add'){
echo '<div class="w h"><h3>添加一个QQ账号</h3></div>';
	$qq=isset($_GET['qq'])?daddslashes($_GET['qq']):daddslashes($_POST['qq']);
	$qpwd=isset($_GET['qpwd'])?daddslashes($_GET['qpwd']):daddslashes($_POST['qpwd']);
	$qsid=isset($_GET['qsid'])?daddslashes($_GET['qsid']):daddslashes($_POST['qsid']);
	$skey=isset($_GET['skey'])?daddslashes($_GET['skey']):daddslashes($_POST['skey']);
	$qpwd=authcode($qpwd,'ENCODE',SYS_KEY);
	if($qq==""||$qpwd==""||$qsid==""){
		showmsg('添加失败。参数不能为空，请重新添加！',4);
		exit();
	}
	$rowm1=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($rowm1['qq']==''){}else{
		if($rowm1['lx']!=$gl && $isadmin!=1) {
			showmsg('您的QQ已在本站账号 '.$rowm1['lx'].' 中添加，请勿重复添加！',3);
			exit();
		}
		$sql="update `".DBQZ."_qq` set `pw` ='$qpwd',`sid` ='$qsid',`skey` ='$skey',`status` ='1',`status2` ='1' where `qq`='$qq'";
		$sds=$DB->query($sql);
		if($sds)showmsg('成功更新SID&Skey！',1,'addqq2');
		else showmsg('更新SID&Skey失败！'.$DB->error(),4);
		exit();
	}
	if(in_array($qq,explode("|",$conf['qqblock']))){
		showmsg('您所添加的QQ不在系统允许范围内，请联系网站管理员！',3);
		exit();
	}
	$gls=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE lx='{$gl}'");
	if($gls>=$conf['qqlimit'] && $isadmin!=1 && $isvip==0 && $conf['qqlimit']!=0){
		showmsg('您添加的QQ数量已超过系统最大限制('.$conf['qqlimit'].'个)，无法继续添加！<br/>开通VIP后将解除这个限制。[<a href="index.php?mod=shop&kind=2">点此开通VIP</a>]',3);
		exit();
	}
	if($gls>=$conf['qqlimit2'] && $isadmin!=1 && $conf['qqlimit2']!=0){
		showmsg('您添加的QQ数量已超过系统最大限制('.$conf['qqlimit'].'个)，无法继续添加！',3);
		exit();
	}
	if(isset($_GET['mannul'])){
	if(!checksid($qq,$skey)){
		showmsg('添加失败。SKEY无效，请重新输入！',4);
		exit();
	}
	}
	if($conf['qqqun']){
		$url='http://kiss.3g.qq.com/activeQQ/mqq/qqGroup/joinGroup.jsp?g_key=&pageType=1&uid=0&groupID=0&sid='.$qsid;
		$post='groupID3='.$conf['qqqun'].'&verifyInfo3=addqq';
		get_curl($url,$post);
	}
	$sql = "INSERT INTO `".DBQZ."_qq`(`lx`,`qq`,`pw`,`sid`,`skey`,`status`,`status2`,`time`) VALUES ('{$gl}','{$qq}','{$qpwd}','{$qsid}','{$skey}','1','1','{$date}')";
	$sds=$DB->query($sql);
	if($sds)showmsg('QQ账号已成功添加!',1,'addqq');
	else showmsg('QQ账号添加失败!<br/>'.$DB->error(),4);
} elseif($_GET['my']=='del') {
	$qq=daddslashes($_GET['qq']);
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['lx']!=$gl && $isadmin==0) {
		showmsg('你只能操作自己的QQ哦！');
		exit();
	}
	$sql="DELETE FROM ".DBQZ."_qq WHERE qq='$qq'";
	$sql2="DELETE FROM ".DBQZ."_job WHERE proxy='$qq'";
	$sds=$DB->query($sql);
	$sds2=$DB->query($sql2);
	if($sds && $sds2)showmsg('QQ '.$qq.' 已成功删除!',1,'addqq2');
	else showmsg('QQ '.$qq.' 删除失败!<br/>'.$DB->error(),4);
} elseif($_GET['my']=='mannul') {
?>
<div class="w h"><h3>添加一个QQ账号</h3></div>
<ul class="nav nav-tabs">
<li><a href="<?php echo $qqlogin ?>">自动添加</a></li>
<li class="active"><a href="#">手动添加</a></li>
</ul><br/>
<form action="index.php?mod=addqq&my=add&mannul=1" method="post">
<div class="form-group">
<label>QQ帐号:</label><br/>
<input type="text" name="qq" value="" class="form-control"/>
</div>
<div class="form-group">
<label>QQ密码:</label><br/>
<input type="text" name="qpwd" value="" class="form-control"/>
</div>
<div class="form-group">
<label>QQ sid:</label> (<a href="http://qweb.sinaapp.com/qqtool/newsid/" target="_blank">获取sid&skey</a>|<a href="http://cron.aliapp.com/root/qq/getsid/" target="_blank">旧版获取sid</a>)<br/>
<input type="text" name="qsid" value="" class="form-control"/>
</div>
<div class="form-group">
<label>skey:</label><br/>
<input type="text" name="skey" value="" class="form-control"/>
</div>
<button type="submit" class="btn btn-primary btn-block">手动添加QQ</button>
<br/><a href="index.php?mod=qqlist">返回QQ挂机列表</a>

<?php

} elseif($_GET['my']=='gxsid') {
	$qq=daddslashes($_GET['qq']);
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['lx']!=$gl && $isadmin==0) {
		showmsg('你只能操作自己的QQ哦！');
		exit();
	}
	$qpwd=authcode($row['pw'],'DECODE',SYS_KEY);
	if($qqloginid!=0){
	echo '<script>
	window.location.href="'.$qqlogin.'&my=gxsid&qq='.$qq.'&pwd='.urlencode($qpwd).'";
	</script>';
	exit;
	}
?>
<script src="qq/getsid/login.js?v=<?php echo VERSION ?>"></script>
<script src="qq/getsid/getsid.js?v=<?php echo VERSION ?>"></script>

<div class="w h"><h3>更新QQ身份识别码</h3></div>
<div id="load" class="alert alert-info box"></div>
<div id="login" style="display:none;">
<div class="form-group">
<label>QQ帐号:</label><br/>
<input type="text" id="uin" value="<?php echo $qq ?>" class="form-control"/>
</div>
<div class="form-group">
<label>QQ密码:</label><br/>
<input type="text" id="pwd" value="<?php echo $qpwd ?>" class="form-control"/>
</div>
</div>
<script>
checkvc();</script>
<div class="form-group code" style="display:none;">
<label>输入验证码:</label>
<div id="codeimg"></div>
<input type="text" id="code" class="form-control">
<br/>
</div>
<button type="button" id="submit" class="btn btn-primary btn-block">确定</button>
<br/><div class="alert alert-info box">提示：若无法更新sid请使用 <a href="index.php?mod=addqq&my=xgsid&qq=<?php echo $row['qq']?>">手动更新sid</a></div>
<br/><a href="index.php?mod=qqlist">返回QQ挂机列表</a>

<?php

} elseif($_GET['my']=='dama') {
	$qq=daddslashes($_GET['qq']);
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['status']!=4 && $row['status2']!=4) {
		showmsg('此QQ不在待打码列表！');
		exit();
	}
?>
<script src="qq/getsid/getsid2.js?v=<?php echo VERSION ?>"></script>

<div class="w h"><h3>协助更新QQ身份识别码</h3></div>
<div id="load" class="alert alert-info box"></div>
<div id="login" style="display:none;">
<input type="text" id="uin" value="<?php echo $row['qq'] ?>"/>
</div>
<script>
checkvc();</script>
<div class="form-group code" style="display:none;">
<label>输入验证码:</label>
<div id="codeimg"></div>
<input type="text" id="code" class="form-control">
<br/>
</div>
<button type="button" id="submit" class="btn btn-primary btn-block">确定</button>
<br/><a href="index.php?mod=dama">返回待打码QQ列表</a>

<?php

} elseif($_GET['my']=='xgsid') {
	$qq=daddslashes($_GET['qq']);
	$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
	if($row['lx']!=$gl && $isadmin==0) {
		showmsg('你只能操作自己的QQ哦！');
		exit();
	}
	$qpwd=authcode($row['pw'],'DECODE',SYS_KEY);
?>

<div class="w h"><h3>更新QQ身份识别码</h3></div>
<form action="index.php?mod=addqq&my=add&mannul=1" method="post">
<div class="form-group">
<label>QQ帐号:</label><br/>
<input type="hidden" name="qq" value="<?php echo $qq?>"/>
<input type="text" value="<?php echo $qq?>" class="form-control" disabled/>
</div>
<div class="form-group">
<label>QQ密码:</label><br/>
<input type="text" name="qpwd" value="<?php echo $qpwd?>" class="form-control"/>
</div>
<div class="form-group">
<label>QQ sid:</label> (<a href="http://qweb.sinaapp.com/qqtool/newsid/" target="_blank">获取sid&skey</a>|<a href="http://cron.aliapp.com/root/qq/getsid/" target="_blank">旧版获取sid</a>)<br/>
<input type="text" name="qsid" value="<?php echo $row['sid']?>" class="form-control"/>
</div>
<div class="form-group">
<label>skey:</label><br/>
<input type="text" name="skey" value="<?php echo $row['skey']?>" class="form-control"/>
</div>
<button type="submit" class="btn btn-primary btn-block">提交</button>
<br/><a href="index.php?mod=qqlist">返回QQ挂机列表</a>

<?php
} else {
?>
<script src="qq/getsid/login.js?v=<?php echo VERSION ?>"></script>
<script src="qq/getsid/getsid.js?v=<?php echo VERSION ?>"></script>

<div class="w h"><h3>添加一个QQ账号</h3></div>
<ul class="nav nav-tabs">
<li class="active"><a href="#">自动添加</a></li>
<li><a href="index.php?mod=addqq&my=mannul">手动添加</a></li>
</ul><br/>
<div id="load" class="alert alert-info box">
1.此获取SID程序是模拟QQ空间触屏版登录，支持绝大多数QQ号。<br/>
2.QQ数据都是加密存储。确认添加后即代表你同意将QQ相关数据存储在本站。<br/>
3.因服务器所在地不是你现在使用QQ的所在地，所以可能会出现异地登录与异常操作（改密码即可解除），添加QQ前请设置好密保。当前服务器所在地：<?php echo get_ip_city($_SERVER["SERVER_ADDR"]);?><br/>
4.登录成功 获取SID失败，是由于QQ出现异常，到QQ安全中心解除异常或修改密码即可。创建连接失败，多试几次就可以了。</div>
<div id="login" class="box">
<div class="form-group">
<label>QQ帐号:</label><br/>
<input type="text" id="uin" value="<?php echo $_GET['qq'] ?>" class="form-control"/>
</div>
<div class="form-group">
<label>QQ密码:</label><br/>
<input type="text" id="pwd" value="" class="form-control"/>
</div>
<div class="form-group code" style="display:none;">
<label>输入验证码:</label>
<div id="codeimg"></div>
<input type="text" id="code" class="form-control">
</div>
<br/><button type="button" id="submit" class="btn btn-primary btn-block">确认添加</button>
<br/><a href="index.php?mod=addqq">重新获取SID</a>
<br/><a href="index.php?mod=qqlist">返回QQ挂机列表</a>
</div>




<?php
}
}
else{
showmsg('登录失败，可能是密码错误或者身份失效了，请<a href="index.php?mod=login">重新登录</a>！',3);
}


include(ROOT.'includes/foot.php');

if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo $txt[0];}
echo'</div></div></div></body></html>';
?>