<?php
 /*
　*空间刷人气
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="空间刷人气";
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
if(!isset($_SESSION['rqcount']))$_SESSION['rqcount']=0;
if($_SESSION['rqcount']>100 && $isadmin==0) {
	showmsg('你的刷人气次数已超配额，请明天再来！');
	exit();
}
$result=$DB->query("SELECT * from ".DBQZ."_qq WHERE status='1' order by rand() limit 50");
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
			var url="<?php echo $siteurl ?>qq/api/rq.php";
			xiha.postData(url,'uin=<?php echo $qq ?>&cell=<?php echo $cell ?>&qid='+qid, function(d) {
				if(d.code ==0){
					checkself.removeClass('nostart');
					checkself.html("<font color='green'>已刷人气</font>");
					$('#load').html(d.msg);
					num = $('#liked').text();
					num=parseInt(num);
					num++;
					$('#liked').text(num);
				}else{
					checkself.html("<font color='red'>失败</font>");
				}
			});
			num++;
			//return false;
		});
		if(num<1) $('#load').html('没有待刷人气的QQ');
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
//				alert('创建连接失败');
			}
		});
	}
}
</script>

<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">空间刷人气</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">使用说明</h3>
	</div>
	<div class="panel-body" align="left">
		<p style="color:red">利用平台内QQ号刷自己空间人气！<br>每次随机取出50个QQ，刷新本页面可以更换一批QQ。<br>刷人气前请先将自己的QQ空间权限设为所有人可访问！</p>
	</div>
</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title" align="center"><span class="btn btn-block" id="startcheck">点此开始刷人气</span></h3>
		</div>
		<div class="panel-body" align="left">
			<ul class="list-group" style="list-style:none;">
			
			<li class='list-group-item'>平台总共<span id="hyall"><?php echo $gls;?><span>个QQ,有<span id="liked"></span>个已成功刷人气！</li>
			<li class='list-group-item' style="color:red;text-align: center;font-weight: bold;" id="load">等待开启</li>
			<?php
			$liked=0;
				foreach($arr as $k=>$row){
					if(isset($_SESSION["r_".$cell]["$row[qq]"])){
						if($_SESSION["r_".$cell]["$row[qq]"]==1){
							$liked=$liked+1;
							echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;"><font color="green">已刷人气</font></span></li>';
						}else{
							echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;" qid="'.$row['id'].'" class="nozan"><font color="red">失败</font></span></li>';
						}
					}else{
						echo '<li class="list-group-item">'.$row['qq'].'<span style="float:right;" qid="'.$row['id'].'" class="nostart">未开启</span></li>';
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