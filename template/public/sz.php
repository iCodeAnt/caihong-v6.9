<?php
 /*
　*说说刷赞
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="说说刷赞";
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';
if($theme=='mobile')echo '<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>';

if($islogin==1){
vipfunc_check(10);
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
if(!isset($_SESSION['szcount']))$_SESSION['szcount']=0;
if($_SESSION['szcount']>100 && $isadmin==0) {
	showmsg('你的刷赞次数已超配额，请明天再来！');
	exit();
}
$skey=$row['skey'];

if($conf['mzjc_api']==0 || !$conf['mzjc_api']) {
$gtk = getGTK($skey);
$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";
$url='http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin='.$qq.'&ftype=0&sort=0&pos=0&num=1&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk='.$gtk;
$json = get_curl($url,0,0,$cookie);
}else{
$json = get_curl($allapi.'api/shuo.php?qq='.$qq.'&skey='.$skey.'&authcode='.$authcode);
}
$json=mb_convert_encoding($json, "UTF-8", "UTF-8");
$arr=json_decode($json,true);
//print_r($arr);exit;
if (@array_key_exists('code',$arr) && $arr['code']==0) {
	foreach ($arr['msglist'] as $row ) {
		$cell=$row['tid'];
	}
}else{
	showmsg('获取说说列表失败！');
	exit();
}

$result=$DB->query("SELECT * from ".DBQZ."_qq WHERE status2='1' order by rand() limit 30");
$arr=array();
while($row=$DB->fetch($result)){
	$arr[]=$row;
}

$gls=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");
?>
<script>
$(document).ready(function() {
	$('#startcheck').click(function(){
		$('#load').html('检测中');
		var self=$(this);
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
		var touin,num=0;
		$(".nostart").each(function(){
			var checkself=$(this),
				qid=checkself.attr('qid');
			checkself.html("<img src='images/load.gif' height=25>")
			var url="<?php echo $siteurl ?>qq/api/sz.php";
			xiha.postData(url,'uin=<?php echo $qq ?>&cell=<?php echo $cell ?>&qid='+qid, function(d) {
				if(d.code ==0){
					checkself.removeClass('nostart');
					checkself.html("<font color='green'>已赞</font>");
					$('#load').html(d.msg);
					num = $('#liked').text();
					num=parseInt(num);
					num++;
					$('#liked').text(num);
				}else if(d.code ==-2){
					checkself.html("<font color='yellow'>频繁</font>");
					$('#load').html(d.msg);
				}else if(d.code ==-3){
					checkself.removeClass('nostart');
					checkself.html("<font color='red'>SID过期</font>");
					$('#load').html(d.msg);
				}else{
					checkself.html("<font color='red'>失败</font>");
					alert(d.msg);
				}
			});
			num++;
			//return false;
		});
		if(num<1) $('#load').html('没有待可赞的QQ');
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
				alert('创建连接失败');
			}
		});
	}
}
</script>

<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">说说刷赞</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">使用说明</h3>
	</div>
	<div class="panel-body" align="left">
		<p style="color:red">利用平台内QQ号赞自己的第一条说说！<br>每次随机取出30个QQ，刷新本页面可以更换一批QQ。<br>刷赞前请先将自己的QQ空间权限设为所有人可访问！</p>
	</div>
</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title" align="center"><span class="btn btn-block" id="startcheck">点此开始刷赞</span></h3>
		</div>
		<div class="panel-body" align="left">
			<ul class="list-group" style="list-style:none;">
			
			<li class='list-group-item'>平台总共<span id="hyall"><?php echo $gls;?><span>个QQ,有<span id="liked"></span>个已成功赞！</li>
			<li class='list-group-item' style="color:red;text-align: center;font-weight: bold;" id="load">等待开启</li>
			<?php
			$liked=0;
			if($cell) {
				foreach($arr as $k=>$row){
					if(isset($_SESSION["o_".$cell]["$row[qq]"])){
						if($_SESSION["o_".$cell]["$row[qq]"]==1){
							$liked=$liked+1;
							echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;"><font color="green">已赞</font></span></li>';
						}else{
							echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;" qid="'.$row['id'].'" class="nozan"><font color="red">失败</font></span></li>';
						}
					}else{
						echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;" qid="'.$row['id'].'" class="nostart">未开启</span></li>';
					}
				}
			}
			echo "<script>$('#liked').html('{$liked}');</script>";
			?>
			</ul>
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