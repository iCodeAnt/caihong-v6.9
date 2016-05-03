<?php
error_reporting(0);

@header('Content-Type: text/html; charset=UTF-8');
class qq_login{
	public function dovc(){
		$uin=empty($_POST['uin'])?exit('{"saveOK":-1,"msg":"uin不能为空"}'):$_POST['uin'];
		$sig=empty($_POST['sig'])?exit('{"saveOK":-1,"msg":"sig不能为空"}'):$_POST['sig'];
		$ans=empty($_POST['ans'])?exit('{"saveOK":-1,"msg":"验证码不能为空"}'):$_POST['ans'];
		$url='http://captcha.qq.com/cap_union_verify?aid=549000929&uin='.$uin.'&captype=8&ans='.$ans.'&sig='.$sig.'&0.960725'.time();
		$data=$this->get_curl($url);
		if(preg_match("/cap\_InnerCBVerify\((.*?)\);/", $data, $json)){
			$arr=json_decode($json[1],true);
			exit(str_replace(array('{',':',','),array('{"','":',',"'),$json[1]));
		}else{
			exit('{"rcode":0,"randstr":"@J_9","sig":"","errmsg":"验证失败，请重试。"}');
		}
	}
	public function getvcpic(){
		$uin=empty($_GET['uin'])?exit('{"saveOK":-1,"msg":"uin不能为空"}'):$_GET['uin'];
		$sig=empty($_GET['sig'])?exit('{"saveOK":-1,"msg":"sig不能为空"}'):$_GET['sig'];
		header('content-type:image/jpeg');
		$url='http://captcha.qq.com/getimgbysig?aid=549000929&uin='.$uin.'&sig='.$sig;
		echo $this->get_curl($url);
	}
	public function qqlogin(){
		$uin=empty($_POST['uin'])?exit('{"saveOK":-1,"msg":"uin不能为空"}'):$_POST['uin'];
		$pwd=empty($_POST['pwd'])?exit('{"saveOK":-1,"msg":"pwd不能为空"}'):$_POST['pwd'];
		$p=empty($_POST['p'])?exit('{"saveOK":-1,"msg":"p不能为空"}'):$_POST['p'];
		$vcode=empty($_POST['vcode'])?exit('{"saveOK":-1,"msg":"vcode不能为空"}'):strtoupper($_POST['vcode']);
		$pt_verifysession=empty($_POST['pt_verifysession'])?exit('{"saveOK":-1,"msg":"pt_verifysession不能为空"}'):$_POST['pt_verifysession'];
		if(strpos('s'.$vcode,'!')){
			$v1=0;
		}else{
			$v1=1;
		}
		$url='http://ptlogin2.qzone.com/login?verifycode='.$vcode.'&u='.$uin.'&p='.$p.'&pt_randsalt=0&ptlang=2052&low_login_enable=0&u1=http%3A%2F%2Fm.qzone.com%2Finfocenter%3Fg_f%3D&from_ui=1&fp=loginerroralert&device=2&aid=549000929&pt_ttype=1&pt_3rd_aid=0&ptredirect=1&h=1&g=1&pt_uistyle=9&pt_vcode_v1='.$v1.'&pt_verifysession_v1='.$pt_verifysession.'&';
		$ret = $this->get_curl($url,0,1,0,1);
		if(preg_match("/ptuiCB\('(.*?)'\);/", $ret, $arr)){
			$r=str_replace("', '","','",$arr[1]);
			$r=explode("','",$r);
			if($r[0]==0){
				preg_match('/skey=@(.{9});/',$ret,$skey);
				if($sid=$this->getsid($r[2])){
					exit('{"saveOK":0,"uin":"'.$uin.'","sid":"'.$sid.'","skey":"@'.$skey[1].'"}');
				}else{
					exit('{"saveOK":-3,"msg":"登录成功，获取SID失败！'.$r[2].'"}');
				}
			}elseif($r[0]==4){
				exit('{"saveOK":4,"uin":"'.$uin.'","msg":"验证码错误"}');
			}elseif($r[0]==3){
				exit('{"saveOK":3,"uin":"'.$uin.'","msg":"密码错误"}');
			}elseif($r[0]==19){
				exit('{"saveOK":19,"uin":"'.$uin.'","msg":"您的帐号暂时无法登录，请到 http://aq.qq.com/007 恢复正常使用"}');
			}else{
				exit('{"saveOK":-6,"msg":"'.str_replace('"','\'',$r[4]).'"}');
			}
		}else{
			exit('{"saveOK":-2,"msg":"'.$ret.'"}');
		}
	}
	public function getvc(){
		if(!$uin=$_POST['uin']) exit('{"saveOK":-1,"msg":"请先输入QQ号码"}');
		if(!$sig=$_POST['sig']) exit('{"saveOK":-1,"msg":"SIG不能为空"}');
		if(!preg_match("/^[1-9][0-9]{4,11}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		if(strlen($sig)==56){
			$url='http://captcha.qq.com/cap_union_show?captype=3&aid=549000929&uin='.$uin.'&cap_cd='.$sig.'&v=0.0672220'.time();
			$data=$this->get_curl($url);
			if(preg_match("/g\_click\_cap\_sig=\"(.*?)\";/", $data, $arr)){
				exit('{"saveOK":0,"vc":"'.$arr[1].'"}');
			}else{
				exit('{"saveOK":-3,"msg":"获取验证码失败"}');
			}
		}else{
			$url='http://captcha.qq.com/getQueSig?aid=549000929&uin='.$uin.'&captype=8&sig='.$sig.'&0.819966'.time();
			$data=$this->get_curl($url);
			if(preg_match("/cap_getCapBySig\(\"(.*?)\"\);/", $data, $arr)){
				exit('{"saveOK":0,"vc":"'.$arr[1].'"}');
			}else{
				exit('{"saveOK":-3,"msg":"获取验证码失败"}');
			}
		}
	}
	public function checkvc(){
		if(!$uin=$_POST['uin']) exit('{"saveOK":-1,"msg":"请先输入QQ号码"}');
		if(!preg_match("/^[1-9][0-9]{4,13}$/",$uin)) exit('{"saveOK":-2,"msg":"QQ号码不正确"}');
		$url='http://check.ptlogin2.qzone.com/check?pt_tea=1&uin='.$uin.'&appid=549000929&ptlang=2052&r=0.071823'.time();
		$data=$this->get_curl($url);
		if(preg_match("/ptui_checkVC\('(.*?)'\);/", $data, $arr)){
			$r=explode("','",$arr[1]);
			if(strlen($r[0])==4){
				exit('{"saveOK":0,"data":"1,'.$uin.','.$r[1].'"}');
			}else{
				exit('{"saveOK":0,"data":"'.$r[0].','.$uin.','.$r[1].','.$r[3].'"}');
			}
		}else{
			exit('{"saveOK":-3,"msg":"获取验证码失败"}');
		}
	}
	private function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		$httpheader[] = "Accept:application/json";
		$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
		$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
		$httpheader[] = "Connection:close";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($header){
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
		}
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}
		if($referer){
			curl_setopt($ch, CURLOPT_REFERER, "http://m.qzone.com/infocenter?g_f=");
		}
		if($ua){
			curl_setopt($ch, CURLOPT_USERAGENT,$ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0');
		}
		if($nobaody){
			curl_setopt($ch, CURLOPT_NOBODY,1);

		}
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	public function getsid($url, $do = 0)
	{
		$do++;
		if ($ret = $this->get_curl($url)) {
			$ret = preg_replace('/([\x80-\xff]*)/i','',$ret);
			if (preg_match('/\{"sid":"(.{24})"/iU', $ret, $sid)) {
				return $sid[1];
			} else {
				if ($do < 5) {
					return $this->getsid($url, $do);
				} else {
					$this->sidcode = 2;
					return;
				}
			}
		} else {
			$this->sidcode = 1;
			return;
		}
	}
}

$login=new qq_login();
if($_GET['do']=='checkvc'){
	$login->checkvc();
}
if($_GET['do']=='dovc'){
	$login->dovc();
}
if($_GET['do']=='getvc'){
	$login->getvc();
}
if($_GET['do']=='qqlogin'){
	$login->qqlogin();
}
if($_GET['do']=='getvcpic'){
	$login->getvcpic();
}
if ($_GET['do']=='getsid'){
	echo $login->getsid($_GET['url']);
}
