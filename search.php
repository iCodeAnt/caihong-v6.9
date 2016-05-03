<?php
$mod='blank';
include("./includes/common.php");
function get_qqnick($uin){
    if($data=get_curl("http://users.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins=".$uin)){
		$data=str_replace(array('portraitCallBack(',')'),array('',''),$data);
		$data=mb_convert_encoding($data, "UTF-8", "GBK");
		$row=json_decode($data,true);;
		return $row[$uin][6];
	}
}
$uin=is_numeric($_GET['q'])?$_GET['q']:'0';
@ini_set("short_open_tag", "on");
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?=$uin?>-秒赞认证-<?=$conf['sitename']?></title>
<meta name="keywords" content="离线CQY,<?=$uin?>,<?=$uin?>秒赞验证"/>
<meta name="description" content="<?=$config['web_name']?>"/>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="http://clouds.aliapp.com/static/search.css" rel="stylesheet" type="text/css">
<style type="text/css">
   	body,td,th {
	font-family: Raleway-Light;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
</head>
<?php
$result=$DB->query("select * from ".DBQZ."_qq where qq='$uin' and status='1' limit 1");
if($row = $DB->fetch($result)){
	$url="http://m.qzone.com/list?res_attach=att%3D0&format=json&list_type=shuoshuo&action=0&res_uin=".$row['qq']."&count=5&sid=".$row['sid'];
	$json=get_curl($url);
	$json=strstr($json,'{');
	$arr=json_decode($json,true);
	$zan=0;
	if($arr=$arr['data']['vFeeds']){
		foreach($arr as $new){
			if($new['like']['num']>$zan) $zan=$new['like']['num'];
		}
	}
	/*$cookie="uin=o0" . $row['qq'] . "; skey=" . $row['skey'] . ";";
	$url='http://ic2.s21.qzone.qq.com/cgi-bin/feeds/feeds_html_module?i_uin='.$row['qq'].'&i_login_uin='.$row['qq'].'&style=21&version=8&needDelOpr=false&hideExtend=true&showcount=1';
	$json=get_curl($url,0,0,$cookie);
	preg_match('/data-likecnt=\"(.*?)\"/i',$json,$match);
	$zan=$match[1];*/
?>
<body data-focus="1">
<div class="container mm-page">
	<div class="content">
		<div class="user-profile1 text-center">
			<img src="//q1.qlogo.cn/g?b=qq&nk=<?=$uin?>&s=100&t=<?=date("Ymd")?>" title="【QQ：<?=$uin?>】已获得<?=$conf['sitename']?>权威认证">
			<h3><?=get_qqnick($uin)?></h3>
			<ul class="list-unstyled list-inline">
				<li><a href="./" target="_blank" title="该QQ来自<?=$conf['sitename']?>"><span><i class="fa"></i></span></a></li>
			</ul>
			<p>
				您当前查看的QQ,正享受<a href="./"><?=$conf['sitename']?></a>系统认证
			</p>
			<a class="p-btn" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$row['qq']?>&site=qq&menu=yes" target="_blank">点击聊天</a>
			<a class="p-btn" href="http://user.qzone.qq.com/<?=$row['qq']?>" target="_blank">进入空间</a>
		</div>
		<div class="twitter-box">
			<div class="twitter-box-head">
				<h3>基本信息</h3>
				<div class="twitts-stat">
					<div class="twitts-stat-grid">
						<span>底赞数量</span>
						<label>
						<?=$zan?>
						</label>
					</div>
					<div class="twitts-stat-grid">
						<span>QQ号码</span>
						<label><?=$row['qq']?></label>
					</div>
					<div class="twitts-stat-grid">
					<span>是否秒赞</span>
					<label>是</label>
					</div>
					<div class="clearfix">
					</div>
				</div>
			</div>
			<script type="text/javascript" src="http://clouds.aliapp.com/static/jquery.easy-ticker.js?r=<?=rand()?>"></script>
			<script type="text/javascript">
							$(document).ready(function(){
								$('#demo').hide();
								$('.vticker').easyTicker();
							});
							</script>
			<div class="latest-tweets-box">
				<div class="vticker" style="display: block; position: relative; height: 134px; overflow: hidden;">
					<ul style="position: absolute; margin: 0px; top: 0px;">
						<?php
						$result=$DB->query("select * from ".DBQZ."_qq order by id limit 10");
						while($qq = $DB->fetch($result)){ 
						?>
						<li style="display: block; margin: 0px;">
						<p style="margin:0px;">
							恭喜<a href="?q=<?=$qq['qq']?>" target="_blank">QQ:<?=$qq['qq']?></a>,加入<?=$conf['sitename']?>认证！
						</p>
						<span style="margin: 0px;"><?=$qq['date']?></span></li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
                 <div class="social-tags">
			<h4>秒赞认证查询</h4>
			<div class="newsletter clearfix">
                <div class="wrap">
                                     <div class="newsletter-form clearfix">
                       

       <div class="container">
       <form action="search.php" method="get">
<input name="q" onkeyup="value=value.replace(/[^1234567890-]+/g,'')" placeholder="输入要查询的QQ号码" class="form-control" type="text">
      

<br><p style="text-align:center;"><button type="submit" class="p-btn" align="center">秒赞认证查询</button></p>


</form>
  
                  </div>
                    <br><p style="text-align:center;"><span style="color:#3AACF9;">仅支持查询在本平台开启秒赞的用户！</span></p>
                </div>
  
		</div>
		<div class="copy-right">
			<p>
				©2015 <a href="./"><?=$conf['sitename']?></a>
			</p>
		</div>
	</div>
</div>
</body>
<?php
}else{
?>
<body data-focus="1">
<div class="container mm-page">
	<div class="clearfix">
	</div>
	<div class="content">
		<div class="col-3-grid-3 alert-box text-center">
			<img src="http://cronsite.aliapp.com/static/qq.png" title="该QQ未加入本站认证系统">
			<h3>槽糕,系统未找到</h3>
			<p>
				该QQ未通过或未加入本站认证系统
			</p>
			<a class="a-alert" href="./">立马加入</a>
		</div>
		<div class="copy-right">
			<p>
				©2015 <a href="./"><?=$conf['sitename']?></a>
			</p>
		</div>
	</div>
</div>
</body>
<?php
}
?>
</html>