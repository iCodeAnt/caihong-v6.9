<?php
 /*
　*批量添加好友
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="批量添加好友";

include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1){
$qq=daddslashes($_GET['qq']);
if(!$qq) {
	showmsg('参数不能为空！');
	exit();
}
$row=$DB->get_row("SELECT * FROM ".DBQZ."_qq WHERE qq='{$qq}' limit 1");
if($row['lx']!=$gl && $isadmin==0) {
	showmsg('你只能操作自己的QQ哦！');
	exit();
}
if ($row['status2']!=1) {
	showmsg('SKEY已过期！');
	exit;
}
$sid=$row['sid'];
$skey=$row['skey'];
?>
<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">批量添加好友</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">批量添加好友</h3>
	</div>
	<div class="panel-body" align="left">
<?php
if(isset($_POST['uins'])) {
	$groupid=daddslashes($_POST['groupid']);
	echo '<label>添加好友结果:</label><br>';
	$gtk = getGTK($skey);
	$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";
	$ua='Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36';
	$url='http://w.cnc.qzone.qq.com/cgi-bin/tfriend/friend_addfriend.cgi?g_tk='.$gtk;

	$uins = str_replace(array("\r\n", "\r", "\n"), "[br]", $_POST['uins']);
	$match=explode("[br]",$uins);
	foreach($match as $touin) {
		if(!$touin)continue;
		$post='sid=0&ouin='.$touin.'&uin='.$qq.'&fupdate=1&rd=0.017492896'.time().'&fuin='.$touin.'&groupId='.$groupid.'&realname=&flag=&chat=&key=&im=0&g_tk='.$gtk.'&from=9&from_source=11&format=json&qzreferrer=http://user.qzone.qq.com/'.$qq.'/myhome/friends/ofpmd';
		$json=get_curl($url,$post,'http://user.qzone.qq.com/'.$qq.'/myhome/friends/ofpmd',$cookie,0,$ua);
		$json=mb_convert_encoding($json, "UTF-8", "UTF-8");
		$arr = json_decode($json, true);
		if(array_key_exists('code',$arr))
			echo $touin.'&nbsp;'.$arr['message'].'<br/>';
		else
			echo $touin.'&nbsp;获取结果失败！<br/>';
	}
	echo '<br/><a href="index.php?mod=friend&qq='.$qq.'"><< 返回上一页</a>';
}else{
	$gtk = getGTK($skey);
	$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";
	$ua='Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36';
	$url='http://user.qzone.qq.com/p/r/cgi-bin/tfriend/friend_getgroupinfo.cgi?uin='.$qq.'&fuin=&rd=0.808466'.time().'&fupdate=1&format=json&g_tk='.$gtk;
	$json=get_curl($url,$post,'http://user.qzone.qq.com/'.$qq.'/myhome/friends/center',$cookie,0,$ua);
	$json=mb_convert_encoding($json, "UTF-8", "UTF-8");
	$arr = json_decode($json, true);
	if (!$arr) {
		showmsg('分组列表获取失败！');
		exit;
	}elseif ($arr["code"] == -3000) {
		showmsg('SKEY已过期！');
		exit;
	}
?>

		<form action="index.php?mod=friend&qq=<?php echo $qq ?>" method="POST">
		<div class="form-group">
		<label>批量添加好友QQ (每行一个):</label><br>
		<textarea class="form-control" name="uins" rows="10" placeholder="此处填写QQ号，每行一个，不能有空行"></textarea>
		<label>分组:</label><br>
		<select name="groupid" class="form-control">
			<?php
			foreach($arr['data']['items'] as $row) {
			echo '<option value="'.$row['groupId'].'">'.$row['groupId'].'_'.$row['groupname'].'</option>';
			}
			?>
			</select>
		<font color="green">一次性添加过多可能会导致访问超时。</font><br/>
		<input type="submit" class="btn btn-primary btn-block" value="确定添加">
		</div>
		</form>
<?php } ?>
	</div>
</div>

<?php
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