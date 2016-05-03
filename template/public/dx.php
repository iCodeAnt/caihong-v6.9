<?php
 /*
　*单向好友检测
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="单向好友检测";

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

$gtk = getGTK($skey);
$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";
if($conf['mzjc_api']==0 || !$conf['mzjc_api']) {
$url = 'http://m.qzone.com/friend/mfriend_list?res_uin='.$qq.'&res_type=normal&format=json&count_per_page=10&page_index=0&page_type=0&mayknowuin=&qqmailstat=&sid='.$sid;
//$url='http://rc.qzone.qq.com/p/r/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin='.$qq.'&follow_flag=0&groupface_flag=0&fupdate=1&format=json&g_tk='.$gtk;
$json = get_curl($url);
} else {
$json = get_curl($allapi.'api/friend.php?qq='.$qq.'&sid='.$sid.'&skey='.$skey.'&authcode='.$authcode);
}
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
$arr=$arr["data"]["list"];
?>
<script>
$(document).ready(function() {
	$('#startcheck').click(function(){
		$('#load').html('检测中');
		var self=$(this);
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
		var touin,num=0;
		$(".nocheck").each(function(){
			var checkself=$(this),
				touin=checkself.attr('uin');
			checkself.html("<img src='images/load.gif' height=25>")
			var url="<?php echo $siteurl ?>qq/api/dx.php";
			xiha.postData(url,'uin=<?php echo $qq ?>&sid=<?php echo $row['sid'] ?>&touin='+touin, function(d) {
				if(d.code==0){
					if(d.is==0){
						num = $('#hydx').text();
						num=parseInt(num);
						num++;
						checkself.html('<span class="btn btn-large btn-block"><font color="red">单向</font></span>');
						checkself.removeClass('nocheck');
						$(".qqdel[uin="+touin+"]").addClass('isdx');
						$('#hydx').text(num);
					}else{
						checkself.html('<span class="btn btn-large btn-block"><font color="green">正常</font></span>');
						checkself.removeClass('nocheck');
					}
					$('#load').html('QQ：'+touin+'检测完成');
				}else if(d.code==-1){
					checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
					alert('SKEY已过期，请更新SKEY！');
					return false;
				}else{
					checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
				}
			});
			num++;
			//if(num>10) return false;
		});
		if(num<1) $('#load').html('没有待检测的');
		self.attr("data-lock", "false");
	});
	$('#startdelete').click(function(){
		$('#load').html('删除中');
		var self=$(this);
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
		var touin,num=0;
		$(".isdx").each(function(){
			var checkself=$(this),
				touin=checkself.attr('uin');
			checkself.html("<img src='images/load.gif' height=25>")
			var url="<?php echo $siteurl ?>qq/api/del.php";
			xiha.postData(url,'uin=<?php echo $qq ?>&skey=<?php echo $row['skey'] ?>&touin='+touin, function(d) {
				if(d.code==0){
					num = $('#hydel').text();
					num=parseInt(num);
					num++;
					checkself.html('<span class="btn btn-large btn-block"><font color="green">成功</font></span>');
					$('#hydel').text(num);
					$('#load').html('QQ：'+touin+'删除单向好友完成');
				}else if(d.code==-1){
					checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
					alert('SKEY已过期，请更新SKEY！');
					return false;
				}else{
					checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
				}
			});
		});
		if(num<1) $('#load').html('没有待删除的');
		self.attr("data-lock", "false");
	});
	$('.recheck').click(function(){
		var self=$(this),
			touin=self.attr('uin');
		var checkself=$("#to"+touin);
		checkself.html("<img src='images/load.gif' height=25>")
		var url="<?php echo $siteurl ?>qq/api/dx.php";
		xiha.postData(url,'uin=<?php echo $qq ?>&sid=<?php echo $row['sid'] ?>&touin='+touin, function(d) {
			if(d.code==0){
				if(d.is==0){
					num = $('#hydx').text();
					num=parseInt(num);
					num++;
					checkself.html('<span class="btn btn-large btn-block"><font color="red">单向</font></span>');
					checkself.removeClass('nocheck');
					$('#hydx').text(num);
				}else{
					checkself.html('<span class="btn btn-large btn-block"><font color="green">正常</font></span>');
					checkself.removeClass('nocheck');
				}
			}else if(d.code==-1){
				checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
				alert('SKEY已过期，请更新SKEY！');
				return false;
			}else{
				checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
			}
		});
	});
	$('.qqdel').click(function(){
		var self=$(this),
			touin=self.attr('uin');
		var checkself=$(this);
		checkself.html("<img src='images/load.gif' height=25>")
		var url="<?php echo $siteurl ?>qq/api/del.php";
		xiha.postData(url,'uin=<?php echo $qq ?>&skey=<?php echo $row['skey'] ?>&touin='+touin, function(d) {
			if(d.code==0){
				num = $('#hydel').text();
				num=parseInt(num);
				num++;
				checkself.html('<span class="btn btn-large btn-block"><font color="green">成功</font></span>');
				$('#hydel').text(num);
			}else if(d.code==-1){
				checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
				alert('SKEY已过期，请更新SKEY！');
				return false;
			}else{
				checkself.html('<span class="btn btn-large btn-block"><font color="red">失败</font></span>');
			}
		});
	});
	/*$('.qqdel').click(function(){
		var checkself=$(this),
			touin=checkself.attr('uin');
		checkself.html('<Iframe src="<?php echo $siteurl ?>qq/api/del.php?uin=<?php echo $qq ?>&skey=<?php echo $row['skey'] ?>&touin='+touin+'" width="50px" height="30px" scrolling="no" frameborder="0"></iframe>');
	});*/
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
				//alert('创建连接失败');
			}
		});
	}
}
</script>
<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">单向好友检测</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">使用说明</h3>
	</div>
	<div class="panel-body" align="left">
		<p style="color:red">单项检测，不一定完全正确！只做参考<br>
		如果检测出现失败，则点击下失败的QQ，即可重新检测！
		</p>
	</div>
</div>
<div class="panel panel-primary checkbtn">
	<div class="panel-body">
		<center><span class="btn btn-large btn-success" id="startcheck">点此开始单项检测</span>&nbsp;
		<span class="btn btn-large btn-danger" id="startdelete">一键删除单向好友</span><br/>
		<p id="load"></p></center>
	</div>
</div>
<div class="panel panel-primary">
	<table class="table table-bordered">
		<tbody>
			<tr>
			<td align="center"><span style="color:silver;"><b>QQ</b></span></td>
			<td align="center"><span style="color:silver;"><b>昵称</b></span></td>
			<td align="center"><span style="color:silver;"><b>结果</b></span></td>
			<td align="center"><span style="color:silver;"><b>删除</b></span></td>
			</tr>
			<?php
			echo '<tr><td colspan="4" align="center">总共<span id="hyall">'.count($arr).'<span>个好友,其中<span id="hydx">0</span>个单项，已删除<span id="hydel">0</span>个！</td></tr>';
			foreach($arr as $row) {
			echo '<tr><td uin="'.$row['uin'].'">'.$row['uin'].'</td><td>'.$row['nick'].'</td><td id="to'.$row['uin'].'" uin="'.$row['uin'].'" class="nocheck recheck" align="center"><span class="btn btn-large btn-block btn-primary">检测</span></td><td uin="'.$row['uin'].'" class="qqdel" align="center"><span class="btn btn-large btn-block btn-danger">删除</span></td></tr>';
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