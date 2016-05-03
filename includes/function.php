<?php
function get_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$httpheader[] = "Accept:application/json";
	$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
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
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
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
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function get_socket($url,$post=0,$cookie=0,$referer=1){
	$urlinfo = parse_url($url);
	$domain = $urlinfo['host'];
	$query = $urlinfo['path'].'?'.$urlinfo['query'];
	$length=strlen($post);
	$fp = fsockopen($domain, 80, $errno, $errstr, 30);
	if (!$fp) {
		return false;
	} else {
		if($post)
			$out = "POST {$query} HTTP/1.1\r\n";
		else
			$out = "GET {$query} HTTP/1.1\r\n";
		$out .= "Accept: application/json\r\n";
		$out .= "Accept-Language: zh-CN,zh;q=0.8\r\n";
		$out .= "X-Requested-With: XMLHttpRequest\r\n";
		if($post){
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "Content-Length: {$length}\r\n";
		}
		$out .= "Host: {$domain}\r\n";
		if($referer) {
			if($referer==1)
				$out .= "Referer: http://m.qzone.com/infocenter?g_f=\r\n";
			else
				$out .= "Referer: {$referer}\r\n";
		}
		$out .= "User-Agent: Mozilla/5.0 (Linux; U; Android 2.3; en-us) AppleWebKit/999+ (KHTML, like Gecko) Safari/999.9\r\n";
		$out .= "Connection: close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: {$cookie}\r\n\r\n";
		if($post)
			$out .= "{$post}";
		$str='';
		fwrite($fp, $out);
		while (!feof($fp)) {
			$str.=fgets($fp, 2048);
		}
		fclose($fp);
	}
	return $str;
}
function dgmdate($timestamp, $d_format = 'Y-m-d H:i') {
	$timestamp=strtotime($timestamp);
	$timestamp += 8 * 3600;
	$todaytimestamp = TIMESTAMP - (TIMESTAMP + 8 * 3600) % 86400 + 8 * 3600;
	$s = gmdate($d_format, $timestamp);
	$time = TIMESTAMP + 8 * 3600 - $timestamp;
	if($timestamp >= $todaytimestamp) {
		if($time > 3600) {
			return '<span title="'.$s.'">'.intval($time / 3600).'&nbsp;小时前</span>';
		} elseif($time > 1800) {
			return '<span title="'.$s.'">半小时前</span>';
		} elseif($time > 60) {
			return '<span title="'.$s.'">'.intval($time / 60).'&nbsp;分钟前</span>';
		} elseif($time > 0) {
			return '<span title="'.$s.'">'.$time.'&nbsp;秒前</span>';
		} elseif($time == 0) {
			return '<span title="'.$s.'">刚刚</span>';
		} else {
			return $s;
		}
	} elseif(($days = intval(($todaytimestamp - $timestamp) / 86400)) >= 0 && $days < 7) {
		if($days == 0) {
			return '<span title="'.$s.'">昨天&nbsp;'.gmdate('H:i', $timestamp).'</span>';
		} elseif($days == 1) {
			return '<span title="'.$s.'">前天&nbsp;'.gmdate('H:i', $timestamp).'</span>';
		} else {
			return '<span title="'.$s.'">'.($days + 1).'&nbsp;天前</span>';
		}
	} else {
		return $s;
	}
}

function real_ip(){
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
	foreach ($matches[0] AS $xip) {
		if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
			$ip = $xip;
			break;
		}
	}
}
return $ip;
}

function send_mail($to, $sub, $msg) {
	global $conf,$mail_api;
	if($mail_api!=0) {
		global $mail_api_url;
		$post[sendto]=$to;
		$post[title]=$sub;
		$post[content]=$msg;
		$post[user]=$conf['mail_name'];
		$post[pwd]=$conf['mail_pwd'];
		$post[nick]=$conf['sitename'];
		$post[host]=$conf['mail_stmp'];
		$post[port]=$conf['mail_port'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$mail_api_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		if($ret=='1')return true;
		else return $ret;
	} else {
		include_once ROOT.'includes/smtp.class.php';
		$From = $conf['mail_name'];
		$Host = $conf['mail_stmp'];
		$Port = $conf['mail_port'];
		$SMTPAuth = 1;
		$Username = $conf['mail_name'];
		$Password = $conf['mail_pwd'];
		$Nickname = $conf['sitename'];
		$SSL = false;
		$mail = new SMTP($Host , $Port , $SMTPAuth , $Username , $Password , $SSL);
		$mail->att = array();
		if($mail->send($to , $From , $sub , $msg, $Nickname)) {
			return true;
		} else {
			return $mail->log;
		}
	}
}

function send_mail_qqgx($to, $qq) {
	global $conf,$siteurl,$date;
	$sub='QQ:'.$qq.' SID/SKEY过期提醒';//邮件标题
	$msg="您好！你在 ".$conf['sitename']." [".$_SERVER['HTTP_HOST']."] 添加的QQ: <font color=\"green\">{$qq}</font> 的SID/SKEY已经过期，为了不影响你的正常使用，尽快到 <a href='".$siteurl."'>".$siteurl."</a> 更新你的SID！<br/>----------<br/>".$conf['sitename']."<br/>".$date;//邮件内容
	return send_mail($to, $sub, $msg);
}

function send_mail_findpwd($to, $user, $code) {
	global $conf,$siteurl,$date;
	$sub='密码找回邮件';//邮件标题
	$msg="$user,您好。帮助你找回在&nbsp;".$conf['sitename']."&nbsp;的密码!<br>请点击下面的连接完成设置新密码！<br><a href='".$siteurl."index.php?mod=findpwd&code=$code'>".$siteurl."index.php?mod=findpwd&code=$code</a><br>(如果链接不能点击，请复制并粘贴到浏览器的地址栏，然后按回车键，此链接在48小时内有效。)<br/>----------<br/>".$conf['sitename']."<br/>".$date;
	return send_mail($to, $sub, $msg);
}

function daddslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}

function dstrpos($string, $arr) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			return true;
		}
	}
	return false;
}

function checkmobile() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ualist = array('ucweb', 'midp', 'mqqrowser/mini');
	$noualist = array('macintosh', 'msie', 'windows nt', 'applewebkit');
	if((dstrpos($useragent, $ualist) || strexists($_SERVER['HTTP_ACCEPT'], "VND.WAP") || strexists($_SERVER['HTTP_VIA'],"wap")) && !dstrpos($useragent, $noualist))
		return true;
	else
		return false;
}

function checkpc() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ualist = array('macintosh', 'msie', 'windows nt', 'applewebkit');
	$noualist = array('android','moblie','wap','phone');
	if(dstrpos($useragent, $ualist) && !dstrpos($useragent, $noualist))
		return true;
	else
		return false;
}

function checksid($uin,$skey)
{
	$gtk=getGTK($skey);
	$cookie='uin=o0'.$uin.'; skey='.$skey.';';
	$url=get_curl('http://user.qzone.qq.com/p/base.s2/cgi-bin/user/cgi_userinfo_get_all?uin='.$uin.'&vuin='.$uin.'&fupdate=1&format=json&rd=0.516339'.time().'&g_tk='.$gtk,0,0,$cookie);
	$arr=json_decode($url, true);
	if($arr['code']==-3000)return false;
	else return true;
}
function get_ip_city($ip)
{
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
    @$city = get_curl($url . $ip);
    $city = json_decode($city, true);
    if ($city['city']) {
        $location = $city['province'].$city['city'];
    } else {
        $location = $city['province'];
    }
	if($location){
		return $location;
	}else{
		return false;
	}
}
function usergroup()
{
	global $isadmin,$isdaili,$row;
	if ($isadmin==1)return'<font color=blue>管理员</font>';
	elseif($isdaili==1)return'<font color=blue>代理商</font>';
	elseif($row['vip']==1 || $row['vip']==2)return'<font color=red>VIP会员</font>';
	else return'<font color=green>普通会员</font>';
}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key ? $key : ENCRYPT_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}
function getSetting($k, $force = false){
	global $DB,$CACHE;
	if($force) return $setting[$k] = $DB->get_row("SELECT v FROM ".DBQZ."_config WHERE k='$k' limit 1");
	$cache = $CACHE->get($k);
	return $cache[$k];
}
function saveSetting($k, $v){
	global $DB;
	$v = daddslashes($v);
	return $DB->query("REPLACE INTO ".DBQZ."_config SET v='$v',k='$k'");
}
function vipfunc_select($num){
	global $vip_func;
	if(in_array($num,$vip_func)) return 'selected';
	else return null;
}
function vipfunc_check($num){
	global $vip_func,$isvip,$isadmin;
	if(in_array($num,$vip_func) && $isvip==0 && $isadmin==0) {
		showmsg('抱歉，您还不是网站VIP会员，无法使用此功能。<br/>[<a href="index.php?mod=shop&kind=2">点此开通VIP</a>]',3);
		exit;
	}
}
function coin_display($num){
	global $conf,$rules,$isvip;
	if($conf['jifen']==1) {
		if($isvip!=0 && $conf['vip_coin']==1)
			echo '此任务收费标准：<font color="red">VIP会员免'.$conf['coin_name'].'</font><br/>';
		else
			echo '此任务收费标准：<font color="red">每条任务每天收取 '.$rules[$num].' '.$conf['coin_name'].'</font><br/>';
	}
}
function getGTK($skey){
	$len = strlen($skey);
	$hash = 5381;
	for($i = 0; $i < $len; $i++){
		$hash += ($hash << 5) + ord($skey[$i]);
	}
	return $hash & 0x7fffffff;//计算g_tk
}
?>