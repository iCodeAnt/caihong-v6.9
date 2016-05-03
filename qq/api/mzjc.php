<?php
/*秒赞检测核心
Author:消失的彩虹海
*/
function get_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if ($header) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if ($referer) {
		curl_setopt($ch, CURLOPT_REFERER, "http://m.qzone.com/infocenter?g_f=");
	}
	if ($ua) {
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	}
	else {
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
	}
	if ($nobaody) {
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function getGTK($skey){
	$len = strlen($skey);
	$hash = 5381;
	for($i = 0; $i < $len; $i++){
		$hash += ($hash << 5) + ord($skey[$i]);
	}
	return $hash & 0x7fffffff;//计算g_tk
}
	$qq= $_GET['qq'];
	$skey = $_GET['skey'];
	$sid = $_GET['sid'];
	$count = 4; //获取说说的条数

$gtk = getGTK($skey);
$cookie="uin=o0" . $qq . "; skey=" . $skey . ";";

//$url = 'http://m.qzone.com/friend/mfriend_list?res_uin='.$qq.'&res_type=normal&format=json&count_per_page=10&page_index=0&page_type=0&mayknowuin=&qqmailstat=&sid='.$sid;
$url='http://rc.qzone.qq.com/p/r/cgi-bin/tfriend/friend_show_qqfriends.cgi?uin='.$qq.'&follow_flag=0&groupface_flag=0&fupdate=1&format=json&g_tk='.$gtk;
$json = get_curl($url,0,0,$cookie);
$json=mb_convert_encoding($json, "UTF-8", "UTF-8");
$arr = json_decode($json, true);

if (!$arr) {
	exit('{"code":-1,"msg":"好友列表获取失败！"}');
}elseif ($arr["code"] == -3000) {
	exit('{"code":-1,"msg":"SKEY已失效！"}');
}
$friend=$arr["data"]["items"];
$gpnames=$arr["data"]["gpnames"];


$url='http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin='.$qq.'&ftype=0&sort=0&pos=0&num='.$count.'&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk='.$gtk;
$data = get_curl($url,0,0,$cookie);
$arr=json_decode($data,true);
//print_r($data);exit;
$qqrow=array();
$qquins=array();
if (@array_key_exists('code',$arr) && $arr['code']==0) {
	foreach ($arr['msglist'] as $k => $row ) {
		$url='http://users.cnc.qzone.qq.com/cgi-bin/likes/get_like_list_app?uin='.$qq.'&unikey='.urlencode($row['key1']).'&begin_uin=0&query_count=200&if_first_page=1&g_tk='.$gtk;
		$data2 = get_curl($url,0,0,$cookie);
		if(!$data2)exit('{"code":-1,"msg":"获取失败！SKEY已失效"}');
		preg_match('/_Callback\((.*?)\)\;/is',$data2,$json);
		$arr2=json_decode($json[1],true);
		$data2=$arr2['data']['like_uin_info'];
		foreach ($data2 as $row2 ) {
			$fuin=$row2['fuin'];
			if(isset($qqrow[$fuin])){$qqrow[$fuin]++;$qquins[]=$fuin;}
			else $qqrow[$fuin]=1;
		}
	}
}else{
	exit('{"code":-1,"msg":"获取失败！'.$arr['message'].'"}');
}

$result['code']=0;
$result['msg']='suc';
$result['mzcount']=count($qqrow);
$result['uins']=$qquins;
$result['gpnames']=$gpnames;
foreach ($friend as $row3 ) {
	$fuin=$row3['uin'];
	if(isset($qqrow[$fuin]))$list['mz']=$qqrow[$fuin];
	else $list['mz']=0;
	$list['uin']=$row3['uin'];
	$list['name']=$row3['name'];
	if($row3['remark'])$list['remark']=$row3['remark'];
	else $list['remark']=$row3['name'];
	$result['friend'][]=$list;
	unset($list);
}
rsort($result['friend']);
$json=json_encode($result, true);
echo $json;
?>
