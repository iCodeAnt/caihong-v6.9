<?php
//3GQQ挂机
require 'qq.inc.php';

$sysid=isset($_POST['sys']) ? $_POST['sys'] : 1;
$qq=isset($_POST['qq']) ? $_POST['qq'] : $_GET['qq'];
$sid=isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
//$method=isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
//$msg=isset($_POST['msg']) ? $_POST['msg'] : $_GET['msg'];

if($qq && $sid){}else{echo"<font color='red'>输入不完整!<a href='javascript:history.back();'>返回重新填写</a></font>";exit;}

if($sysid==-1){echo"<font color='red'>该系统任务数量已满，请重新选择一个系统！</font><br/><a href='javascript:history.back();'><< 返回上一页</a>";exit;}

function robot($nc)
{
	$apiurl='http://i.itpk.cn/api.php?question=';
	$ch=curl_init($apiurl.$msg);
	curl_setopt($ch,CURLOPT_TIMEOUT,10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$content=curl_exec($ch);
	curl_close($ch);
	$content= str_replace("[cqname]",$nc,$content);
	$content= str_replace("[name]","你",$content);
	return $content;
}

function sendmsg($qq,$sid,$sendmsg)
{
global $method;
$url = 'http://q32.3g.qq.com/g/s?sid='.$sid.'&3G_UIN='.$qq.'&saveURL=0&aid=nqqChat';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_USERAGENT,'MQQBrowser WAP (Nokia/MIDP2.0)');
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
echo $url=curl_exec($ch);
curl_close($ch);
$url=htmlspecialchars($url);
$qq2=explode('【QQ功能】&lt;br/&gt;',$url);
$qq2=explode('&quot;&gt;',$qq2[1]);
$qq2=explode('q=',$qq2[0]);
$m=explode('提示&lt;/a&gt;)',$url);
$m=explode('&lt;input',$m[1]);
$msg=explode('&lt;br/&gt;',$url);
$nc=explode(':',$msg[2]);
$nc=$nc[0];   
$qq2=$qq2[1];
$array = explode($nc, $m[0] );
$tj=(count($array) -1);
$sc=array($msg[3],$msg[5],$msg[7]);
$resultStr='';
for ( $i = 0 ; $i < $tj ; $i++ ){
$arr1 = array('&nbsp;','
',);
$arr2 = array('','',);
$msg = str_replace($arr1, $arr2, $sc[$i]);
$d=date("Y-m-j H:i:s");
if($method='robot'){
	$msg3=robot($msg);
	if($msg3=='')$msg3="{$nc}不懂这是什么意思哦～";
}elseif($method='diy'){
	$msg3=$sendmsg;
}
$resultStr.='问:<FONT color=green>'.$msg. '</FONT><br>答:'.$msg3.'<hr>';
$url5='http://q16.3g.qq.com/g/s?sid='.$sid.'&aid=sendmsg&tfor=qq&referer=';
$post='msg='.$msg3.'&u='.$qq2.'&saveURL=0&do=send';
$ch=curl_init();
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_URL,$url5);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_exec($ch);
curl_close($ch);
sleep(1);
}
return $resultStr;
}

$url = 'http://pt.3g.qq.com/s?aid=nLogin3gqqbysid&3gqqsid='.$sid;
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,$url);
curl_setopt($curl,CURLOPT_USERAGENT,'MQQBrowser WAP (Nokia/MIDP2.0)');
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
$content=curl_exec($curl);
curl_close($curl);

if (preg_match('/nqqchatMain/i', $content))
{
$url = 'http://q16.3g.qq.com/g/s?aid=chgStatus&s=10&sid='.$sid;
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,$url);
curl_setopt($curl,CURLOPT_USERAGENT,'MQQBrowser WAP (Nokia/MIDP2.0)');
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_exec($curl);
curl_close($curl);
$resultStr='3GQQ【'.$qq.'】登录成功!<hr/>';
//if($method='robot' || $method='diy')
//	$resultStr.=sendmsg($qq,$sid,$msg);
}
elseif (preg_match('/sid已经过期/i', $content))
{
sendsiderr($qq,$sid);
}
else
$resultStr=$content;

if(isset($_GET['isadd']))
{
$posturl='../index.php?mod=sc&my=add1&sys='.$sysid;
$addurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?qq='.$qq.'&sid=[sid]';
echo '<h3>运行结果:</h3>';
echo $resultStr;
echo <<<HTML
<hr/><form action='{$posturl}' method='POST'>
以下是你的签到任务网址：<br/>
<font color="blue">{$addurl}</font><br/>
<input type="hidden" name="mc" value="3GQQ挂机任务">
<input type="hidden" name="url" value="{$addurl}">
<input type="hidden" name="type" value="1">
<input type="hidden" name="proxy" value="{$qq}">
<input type='submit' id='submit' value='添加到任务列表'/>
HTML;
}
else
echo $resultStr;
?>