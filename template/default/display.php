<?php
error_reporting(0);
if($_GET['list']==1) {
	$qq=$_GET['qq'];
?>
<div class="modal fade" align="left" id="qqjob01" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加QQ挂机(挂Ｑ类)任务</h4>
      </div>
      <div class="modal-body">
<?php
//echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=guaq&qq='.$qq.'"><img src="images/icon/qq.ico" class="logo">添加手机QQ挂机(机器人)任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=3gqq&qq='.$qq.'"><img src="images/icon/3gqq.ico" class="logo">添加３ＧＱＱ挂机任务</a>';
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==2) {
	$qq=$_GET['qq'];
?>
<div class="modal fade" align="left" id="qqjob02" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加QQ挂机(空间类)任务</h4>
      </div>
      <div class="modal-body">
<?php
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=zan&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说秒赞任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=pl&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说秒评任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=qqsign&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=qqss&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加发表图片说说任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=ht&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间花藤挂机任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=zfss&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加好友说说转发任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=del&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说删除任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=delll&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间留言删除任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=quantu&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间说说圈图任务</a>';
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==3) {
	$qq=$_GET['qq'];
?>
<div class="modal fade" align="left" id="qqjob03" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加QQ挂机(互刷类)任务</h4>
      </div>
      <div class="modal-body">
<?php
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=zyzan&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间互赞主页任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=liuyan&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间互刷留言任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=gift&qq='.$qq.'"><img src="images/icon/qzone.ico" class="logo">添加空间互送礼物任务</a>';
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==4) {
	$qq=$_GET['qq'];
?>
<div class="modal fade" align="left" id="qqjob04" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加QQ挂机(签到类)任务</h4>
      </div>
      <div class="modal-body">
<?php
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=scqd&qq='.$qq.'"><img src="images/icon/3gqq.ico" class="logo">添加书城自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=lzqd&qq='.$qq.'"><img src="images/icon/yqq.ico" class="logo">添加绿钻自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=vipqd&qq='.$qq.'"><img src="images/icon/chen.ico" class="logo">添加VIP 自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=payqd&qq='.$qq.'"><img src="images/icon/iyouxi.ico" class="logo">添加钱包自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=qqjob&my=qqpet&qq='.$qq.'"><img src="images/icon/qqpet.ico" class="logo">添加ＱＱ宠物挂机任务</a>';
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==6) {
	$qq=$_GET['qq'];
	$sid=$_GET['sid'];
?>
<div class="modal fade" align="left" id="joinGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">在线申请加群</h4>
      </div>
      <div class="modal-body">
<form action="http://pt.3g.qq.com/s?aid=nLogin&amp;sid=<?php echo $sid ?>&amp;KqqWap_Act=3&amp;go_url=http%3A%2F%2Fkiss.3g.qq.com%2FactiveQQ%2Fmqq%2FqqGroup%2FjoinGroup.jsp"method="post">申请加入的QQ群号:<br/><input class="form-control" name="groupID3"type="text"maxlength="10"value=""title="群号码"/><br/>验证信息:<br><input class="form-control" name="verifyInfo3"type="text"maxlength="30"value=""title="验证信息"/><br/><input  class="btn btn-primary btn-block" type="submit" value="OK!申请加入"/></form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==7) {
	$qq=$_GET['qq'];
	$sid=$_GET['sid'];
?>
<div class="modal fade" align="left" id="inviteJoinGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">在线拉人进群</h4>
      </div>
      <div class="modal-body">
<form action="http://pt.3g.qq.com/s?aid=nLogin&amp;sid=<?php echo $sid ?>&amp;KqqWap_Act=3&amp;go_url=http%3A%2F%2Fkiss.3g.qq.com%2FactiveQQ%2Fmqq%2FcreateGroup%2FinviteJoinGroup.jsp%3Fsid%3D%26g_f%3D2653%26groupID%3D0%26groupName%3D%25E5%25B1%2595%25E8%25AE%25AF%25E8%2581%2594%25E7%259B%259F%26groupCode%3D$qhm%26friendQQ%3D$qqh%26pageNo%3D1%26nick%3D%25E5%25BC%2584%25E6%259C%2588" method="post">QQ群号:<br/><input class="form-control" name="qhm"type="text"maxlength="10"value=""title="群号码"/><br/>邀请好友QQ:<br><input class="form-control" name="qqh"type="text"maxlength="11"value=""title="好友QQ"/><br/><input class="btn btn-primary btn-block" type="submit"value="OK!邀请TA"/></form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==8) {
	$url=$_GET['url'];
?>
<div class="modal fade" align="left" id="showresult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">手动执行测试</h4>
      </div>
      <div class="modal-body">
	  <div id="load" style="text-align:center;"><img src="images/load.gif" height="25">正在加载...</div>
<iframe src="<?php echo $url;?>" frameborder="0" scrolling="auto" seamless="seamless" width="100%"  onload="$('#load').hide();"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}elseif($_GET['list']==5) {
	$sysid=$_GET['sys'];
?>

<div class="modal fade" align="left" id="signer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加网站签到任务</h4>
      </div>
      <div class="modal-body">
<?php
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=klqd&sys='.$sysid.'"><img src="images/icon/kelink.ico" class="logo">添加柯林自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=dzsign&sys='.$sysid.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=dzdk&sys='.$sysid.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz自动打卡任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=dztask&sys='.$sysid.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz任务助手任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=dzol&sys='.$sysid.'"><img src="images/icon/discuz.ico" class="logo">添加Discuz挂积时任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=115&sys='.$sysid.'"><img src="images/icon/115.ico" class="logo">添加115网盘签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=360yunpan&sys='.$sysid.'"><img src="images/icon/360yunpan.ico" class="logo">添加360云盘签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=vdisk&sys='.$sysid.'"><img src="images/icon/vdisk.ico" class="logo">添加新浪微盘签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=xiami&sys='.$sysid.'"><img src="images/icon/xiami.ico" class="logo">添加虾米音乐签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=fuliba&sys='.$sysid.'"><img src="images/icon/fuliba.ico" class="logo">添加福利论坛签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=3gwen&sys='.$sysid.'"><img src="images/icon/3gwen.ico" class="logo">添加文网自动签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=dyml&sys='.$sysid.'"><img src="images/icon/dyml.ico" class="logo">添加刀云论坛签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=cmgq&sys='.$sysid.'"><img src="images/icon/caomei.ico" class="logo">添加草莓挂Q签到任务</a>';
echo '<a class="btn btn-default btn-block" href="index.php?mod=signer&my=chenqd&sys='.$sysid.'"><img src="images/icon/chen.ico" class="logo">添加CHEN挂Q签到任务</a>';
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php }?>