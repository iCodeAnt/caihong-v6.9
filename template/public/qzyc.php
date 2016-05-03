<?php
/*
 *空间状态检测
 *Original:鱼儿飞雨峰博客
*/ 
if(!defined('IN_CRONLITE'))exit();
$title="空间状态检测";
include_once(TEMPLATE_ROOT."head.php");

if($theme=='default')echo '<div class="col-md-9" role="main">';

if($islogin==1){
$qq=daddslashes($_GET['qq']);
if(!$qq) {
	showmsg('参数不能为空！');
	exit();
}
?>
<ol class="breadcrumb">
  <li><a href="index.php?mod=index">首页</a></li>
  <li><a href="index.php?mod=qqlist">QQ管理</a></li>
  <li><a href="index.php?mod=list&qq=<?php echo $qq ?>"><?php echo $qq ?></a></li>
  <li class="active">空间状态检测</li>
</ol>
<div class="panel panel-primary">
	<div class="panel-heading" style="background: #56892E;">
		<h3 class="panel-title" align="center">空间状态检测</h3>
	</div>
	<?php
$url = get_curl("http://user.qzone.qq.com/".$qq."");
if(preg_match('@不符合互联网相关安全规范@',$url)){
	$qzone = '您的QQ空间已被永久封闭';
}elseif(preg_match('@您访问的空间需要权限@',$url)){
	$qzone = '您的空间设置了好友权限<br>如果设置好友访问,可以不用管本提示';
}elseif(preg_match('@\[http\:\/\/'.$qq.'\.qzone\.qq\.com\]@',$url)){
	$qzone = '您的空间一切正常';
}elseif(preg_match('@您需要登录才可以访问QQ空间@',$url)){
	$qzone = '您的空间,被强制需要登录QQ号才能正常访问<br>具体错误原因是：空间设置或腾讯限制';
}elseif(preg_match('@暂不支持非好友访问@',$url)){
	$qzone = '您的QQ空间已被封单项,非互相好友不能访问';
}else{
	$qzone = '您的空间一切正常';
}
?>
<table class="table table-bordered table-striped">
<thead>
    <tr>
      <td align="center"><span class="badge badge-info">#<?php echo $qq; ?>#</span></td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center"><img src="//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin=<?php echo $qq; ?>&src_uin=<?php echo $qq; ?>&fid=<?php echo $qq; ?>&spec=100&url_enc=0&referer=bu_interface&term_type=PC"></td>
      <!--<th><span class="badge badge-success">@</span></th>-->
    </tr>
  </tbody>
  <thead>
    <tr>
      <td align="center"><?php echo $qzone; ?></td>
    </tr>
  </thead>
</table>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title" align="center">异常说明</h3>
	</div>
	<div class="panel-body">
        <div class="bs-docs-example">
          <div class="accordion" id="accordion2">
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#faq1">您的QQ空间已被封了单项?</a> </div>
              <div id="faq1" class="accordion-body collapse" style="height: 0px;">
                <div class="accordion-inner">就是非好友不能访问,直接删除所有空间内容,打电话联系可以报异常,一般在5分钟内解除</div>
              </div>
            </div>
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#faq2">您的空间设置了好友权限?</a> </div>
              <div id="faq2" class="accordion-body collapse" style="height: 0px;">
                <div class="accordion-inner">就是您自己把空间设置了不是好友无法访问</div>
              </div>
            </div>
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#faq3">需要登录QQ号才能正常访问?</a> </div>
              <div id="faq3" class="accordion-body collapse" style="height: 0px;">
                <div class="accordion-inner">这个问题就是,您的空间无论是谁访问的时候必须要登录自己的空间才能访问,具体原因要么自己空间设置问题,或者腾讯数据异常。</div>
              </div>
            </div>
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#faq4">未知错误?</a> </div>
              <div id="faq4" class="accordion-body collapse" style="height: 0px;">
                <div class="accordion-inner">应该是空间有其他异常,可以联系管理员进行检测。</div>
              </div>
            </div>
            
          </div>
        </div>

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