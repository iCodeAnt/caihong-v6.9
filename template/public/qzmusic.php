<?php
/*
 *空间背景音乐查询
 *Original:鱼儿飞
*/
if(!defined('IN_CRONLITE'))exit();
$title="空间背景音乐查询";
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1){

?>
<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li>QQ小工具</li>
  <li class="active">空间背景音乐查询</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">使用说明</h3>
	</div>
	<div class="panel-body" align="left">
		<p style="color:red">使用此功能可以获取任意QQ空间的背景音乐，同时可以将下载地址做为音乐外链使用。</p>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading" style="background: #56892E;">
		<h3 class="panel-title" align="center">空间背景音乐查询</h3>
	</div>
	<ul align="center" class="list-group" style="list-style:none;">
		<li class="list-group-item">
    <form action="index.php" method="get"><input type="hidden" name="mod" value="qzmusic">
    请输入要查询的QQ:<input type="text" class="form-control" name="qq" size="20"><br><input type="submit" class="btn btn-primary btn-block" value="查询">
    </form>
<br>
	</ul>
</div>
<div class="panel panel-primary">
<?php
if(isset($_GET['qq'])){
	if($_GET['qq'] == ''){
?>
<table class="table table-bordered table-striped">
<thead>
    <tr>
      <td colspan="2">查询结果：</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2"><div class="alerte alert-error">请输入您要查询的QQ</div></td>
    </tr>
  </tbody>
</table>
<?php
	}elseif(!is_numeric($_GET['qq'])){
?>
<table class="table table-bordered table-striped">
<thead>
    <tr>
      <td colspan="2">查询结果：</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2"><div class="alerte alert-error">QQ必须为数字</div></td>
    </tr>
  </tbody>
</table>
<?php
	}else{
		$url = get_curl("http://qzone-music.qq.com/fcg-bin/cgi_playlist_xml.fcg?json=1&uin=".$_GET['qq']."&g_tk=5381");
		$url = mb_convert_encoding($url, "UTF-8", "GB2312");
		preg_match_all('@{xqusic_id\:.*xsong_name\:\"(.*)\".*qqmusic.qq.com/(.*)\'@Ui',$url,$arr);
		//print_r($arr);exit;
		$n = count($arr[1]);
	?>
<table class="table table-bordered table-striped">
<thead>
    <tr>
      <td colspan="2"><?php echo $_GET['qq'];?> 的查询结果：</td>
    </tr>
       </thead>
<?php
	if($n == 0){
?>
  <tbody>
    <tr>
      <td colspan="2"><div class="alerte alert-error">该QQ未设置背景音乐</div></td>
    </tr>
 </tbody>
<?php
	}else{
?>
  <tbody>
    <tr>
      <td>歌曲名称：</td>
      <td>下载地址：</td>
    </tr>
  </tbody>
<?php
		for($i=0;$i<$n;$i++){
?>
  <thead>
    <tr>
      <td><?php echo $arr[1][$i]?></td>
      <td><div class="btn-group"><a href="http://stream.qqmusic.tc.qq.com/<?php echo $arr[2][$i]?>" target="_blank">下载地址1</a>｜<a href="http://tsmusic24.tc.qq.com/<?php echo $arr[2][$i]?>" target="_blank">下载地址2</a></div></td>
    </tr>
  </thead>
<?php
			}
		}
?>
</table>
<?php
	}
}
?>
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