<?php
 /*
　*秒赞检测与好友分组
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="秒赞检测";
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';
if($theme=='mobile')echo '<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>';

if($islogin==1){
vipfunc_check(8);
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

if($conf['mzjc_api']==0 || !$conf['mzjc_api']) {
$gtk = getGTK($skey);
$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";
$url = 'http://m.qzone.com/friend/mfriend_list?res_uin='.$qq.'&res_type=normal&format=json&count_per_page=10&page_index=0&page_type=0&mayknowuin=&qqmailstat=&sid='.$sid;
//$url='http://rc.qzone.qq.com/p/r/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin='.$qq.'&follow_flag=0&groupface_flag=0&fupdate=1&format=json&g_tk='.$gtk;
$json = get_curl($url);
$json=mb_convert_encoding($json, "UTF-8", "UTF-8");
$arr = json_decode($json, true);
//print_r($arr);exit;
if (!$arr) {
	showmsg('好友列表获取失败！');
	exit;
}elseif ($arr["code"] == -3000) {
	showmsg('SID已过期！');
	exit;
}
$friend=$arr["data"]["list"];
$gpnames=$arr["data"]["gpnames"];

foreach($gpnames as $gprow){
	$gpid=$gprow['gpid'];
	$gpname[$gpid]=$gprow['gpname'];
}

$url='http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin='.$qq.'&ftype=0&sort=0&pos=0&num=4&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk='.$gtk;
$data = get_curl($url,0,0,$cookie);
$arr=json_decode($data,true);
//print_r($arr);exit;
$qqrow=array();
$qquins=array();
if (@array_key_exists('code',$arr) && $arr['code']==0) {
	foreach ($arr['msglist'] as $k => $row ) {
		$url='http://users.cnc.qzone.qq.com/cgi-bin/likes/get_like_list_app?uin='.$qq.'&unikey='.urlencode($row['key1']).'&begin_uin=0&query_count=200&if_first_page=1&g_tk='.$gtk;
		$data2 = get_curl($url,0,0,$cookie);
		if(!$data2){showmsg('SKEY已失效，请更新SKEY！');exit;}
		preg_match('/_Callback\((.*?)\)\;/is',$data2,$json);
		$arr2=json_decode($json[1],true);
		$data2=$arr2['data']['like_uin_info'];
		foreach ($data2 as $row2 ) {
			$fuin=$row2['fuin'];
			if(isset($qqrow[$fuin])){$qqrow[$fuin]++;}
			else {$qqrow[$fuin]=1;$qquins[]=$fuin;}
		}
	}
}else{
	showmsg('获取失败！');exit;
}

$mzcount=count($qqrow);
$uins=base64_encode(json_encode($qquins));
foreach ($friend as $row3 ) {
	$fuin=$row3['uin'];
	if(isset($qqrow[$fuin]))$list['mz']=$qqrow[$fuin];
	else $list['mz']=0;
	$list['uin']=$row3['uin'];
	$list['name']=$row3['nick'];
	if($row3['remark'])$list['remark']=$row3['remark'];
	else $list['remark']=$row3['nick'];
	$list['groupid']=$row3['groupid'];
	$result['friend'][]=$list;
	unset($list);
}
rsort($result['friend']);
$friend=$result['friend'];
}else{
$data = get_curl($allapi.'api/mzjc.php?qq='.$qq.'&sid='.$sid.'&skey='.$skey.'&authcode='.$authcode);
$arr=json_decode($data,true);
if(@array_key_exists('code',$arr) && $arr['code']==0) {
	$uins=base64_encode(json_encode($arr['uins']));
	$gpnames=$arr["gpnames"];
	$friend=$arr['friend'];
	$mzcount=$arr['mzcount'];
	$gpname=$arr["gpname"];
} elseif(@array_key_exists('code',$arr)) {
	showmsg($arr['msg']);exit;
} else {
	showmsg('从官方API获取数据失败！');exit;
}
}
?>
<script>

$(document).ready(function() {
	$('.fenzu').click(function(){
		var self=$(this);
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
		self.html('移动中<img src="images/load.gif" height=25>');
		var fenzu=$("#gpname").val();
		var num=0;
		$(".ismove").each(function(){
			var checkself=$(this),
				touin=checkself.attr('uin');
			var url="<?php echo $siteurl ?>qq/api/fenzu.php";
			checkself.html("<img src='images/load.gif' height=25>");
			xiha.postData(url,'uin=<?php echo $qq ?>&skey=<?php echo $skey ?>&touin='+touin+'&gpid='+fenzu, function(d) {
				if(d.code==0){
					num++;
					checkself.html('<font color="green">成功</font>');
					checkself.removeClass('ismove');
					self.html('QQ：'+touin+'移动完成');
				}else if(d.code==-1){
					checkself.html('<font color="red">失败</font>');
					alert('SKEY已过期，请更新SKEY！');
					return false;
				}else{
					checkself.html('<font color="red">失败</font>');
				}
			});
		});
		if(num<1) self.html('没有待移动的QQ！');
		else self.html('移动成功！');
		self.attr("data-lock", "false");
	});
});
var xiha={
	postData: function(url, parameter, callback, dataType, ajaxType) {
		if(!dataType) dataType='json';
		$.ajax({
			type: "POST",
			url: url,
			async: true,
			dataType: dataType,
			json: "callback",
			data: parameter,
			success: function(data) {
				if (callback == null) {
					return;
				} 
				callback(data);
			},
			error: function(error) {
				//alert('未检测到移动结果，请自己查看好友分组');
			}
		});
	}
}
</script>
<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">秒赞好友检测</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">使用说明</h3>
	</div>
	<div class="panel-body" align="left">
		<p style="color:red">秒赞好友检测，不一定完全正确！只做参考<br>移动好友分组，如果有失败的请再次点击按钮重试！</p>
	</div>
</div>
<div class="panel panel-primary checkbtn">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">秒赞好友列表</h3>
	</div>
</div>
<div class="panel panel-primary">
	<table class="table table-bordered">
		<tbody>
            <tr>
              <td align="center"><span style="color:silver;"><b>QQ</b></span></td>
              <td align="center"><span style="color:silver;"><b>昵称</b></span></td>
			  <td align="center"><span style="color:silver;"><b>分组</b></span></td>
              <td align="center"><span style="color:silver;"><b>结果</b></span></td>
            </tr>
			<tr><td colspan="3" align="center"><button class="btn btn-large btn-block fenzu">移动所有秒赞好友到</button></td><td align="center">分组<br><select id="gpname">
			<?php
			foreach($gpnames as $row) {
			echo '<option value="'.$row['gpid'].'">'.$row['gpname'].'</option>';
			}
			?>
			</select></td></tr>
			<?php
			echo '<tr><td colspan="4" align="center">总共<span id="hyall">'.count($friend).'<span>个好友,其中'.$mzcount.'个可能秒赞好友！</td></tr>';
			foreach($friend as $row) {
			echo '<tr><td>'.$row['uin'].'</td><td>'.$row['remark'].'</td><td>'.$gpname[$row['groupid']].'</td><td '.(($row['mz']!=0)?'class="ismove" ':null).'uin="'.$row['uin'].'" align="center" style="background: rgba(205, 133, 0, '.($row['mz']/5).');">'.round(($row['mz']/4)*100).'%</td></tr>';
			}
			?>
		</tbody>
	</table>
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