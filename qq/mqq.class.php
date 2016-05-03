<?php
/*
* 手机QQ(KJAVA) & 3GQQ 二合一操作类
* From:iQQOL
http://conf.3g.qq.com/newConf/kjava/aubin2.jsp
*/
class SET_MQQ
{

    function __construct($uin, $pwd)
    {
        $this->uin = $uin;
        $this->pwd = $pwd;
        $this->host= '119.147.10.10';
        $this->port= '14000';
    }


    /*(1).登录QQ*/
    function Login($code = 10)
    {
        $pack = 'VER=1.4&CON=1&CMD=Login&SEQ=' . rand(1,9000) . '&UIN='.$this->uin.'&PS=' . $this->pwd . '&M5=1&LG=0&ST='.$code.'&LC=812822641C978097&GD=5MWX2PF3FOVGTP6B&CKE=';
        $return =$this->request_by_curl($pack);
        $result = $return;
        if(strpos($return,"CMD=VERIFYCODE")){
        $vc=explode("&VC=",$return);
        $return=json_encode(array('status'=>'vc','res'=>$vc[1]));
        }elseif(strpos($return,"Password error")){
        $return=json_encode(array('status'=>'invaid password'));
        }elseif(strpos($return,"&RS=0")){
            $return=json_encode(array("status"=>"ok"));
        }else{
            $return=json_encode(array("status"=>"error",'qq'=>$this->uin,'result'=>$result));
        }
    //setlog('QQ:'.$this->uin.'|'.json_encode($return));
    return $return;
    }
    /*验证码处理*/
    function Verifycode($code)
    {
        $pack = 'VER=1.4&CON=1&CMD=VERIFYCODE&SEQ=' . rand(1,9000) . '&UIN='.$this->uin.'&SID=&XP=C4CA4238A0B92382&SC=2&VC='.$code;
        $return =$this->request_by_curl($pack);
        if(strpos($return,"&VC=")){
        $vc=explode("&VC=",$return);
        $return=json_encode(array('status'=>'vc','res'=>$vc[1]));
        }else{$return=json_encode(array('status'=>'ok'));}
    //setlog('QQ:'.$this->uin.'|'.json_encode($return));
    return $return;
    }
    /*更改在线状态*/
    function ChangeStat($code)
    {
        //$this->Login($code);
        $pack = 'VER=1.4&CMD=Change_Stat&SEQ=' . rand(1,9000) . '&UIN='.$this->uin.'&ST='.$code;
        $return =$this->request_by_curl($pack);
        //setlog('QQ:'.$this->uin.'|'.$return);
        if(strpos($return,"&RES=20")){
        $return=json_encode(array('status'=>'offline'));
        }else{$return=json_encode(array('status'=>'ok'));}
    return $return;
    }
    function ChangeStat_BUG($code)    //一个小小的BUG，发挥了无限潜力
    {
        //$this->Login($code);
        $pack = 'VER=1.4&CMD=Change_Stat2&SEQ=' . rand(1,9000) . '&UIN='.$this->uin.'&ST='.$code;
        $return =$this->request_by_curl($pack);
        //setlog('QQ:'.$this->uin.'|'.$return);
        if(strpos($return,"&RES=20")){
        $return=json_encode(array('status'=>'offline'));
        }else{$return=json_encode(array('status'=>'ok'));}
    return $return;
    }
    /*(2).发送聊天消息*/
    function SendMsg($toperson, $msg)
    {
        $this->Login();
        $msg=str_replace("\n","%0D%0A",$msg);
        $msg2= str_replace("+"," ",$msg);
        $pack = 'VER=1.4&CON=0&CMD=CLTMSG&SEQ=' . rand(1,9000) . '&UIN='.$this->uin.'&SID=&XP=B58304217ADD0489&UN='.$toperson.'&MG='.$msg2;
        $return =$this->request_by_curl($pack);
        //setlog('QQ:'.$this->uin.'|'.json_encode($return));
    }

    /*(3).获得即时聊天消息*/
    function GetMsg($_reply)
    {
        $this->Login();
        $pack = 'VER=1.4&CON=0&CMD=GetMsgEx&SEQ='. rand(1,9000).'&UIN=' . $this -> uin . '&SID=&XP=29E41F6186ED43F8';
        $return =$this->request_by_curl($pack);
        $message=explode('&UN=',$return);
        $unandmsg=$message[1];
        $need=explode('&MG=',$unandmsg);
        $need_MTstr=explode('&MT=',$message[0]);
        $need_mt=$need_MTstr[1];
        $need_un=$need[0];
        $need_mg=$need[1];
        $un=explode(',',$need_un);
        $mg=explode(',',$need_mg);
        $mt=explode(',',$need_mt);
            for($i=0; $i<count($un); $i++){
                if($un[$i]!='' and $mt[$i]!='99'){
					$username=$this->GetInfo($un[$i],'NK','1',rand(1,9000));
                    $n .= urlencode($username);//QQ昵称
                    $n .= '('.$un[$i];$n .= ')%0D%0A';//收到消息来自的QQ号码
                    $n .= urlencode($mg[$i]).'%0D%0A';//收到的消息的文本内容
						global $method;
						if($method=='robot' || $method=='robot2' || $method=='robot3')$_reply=$this->robot($mg[$i],$username);
                        $this->SendMsg($un[$i],$_reply);

                }
                else {
                    continue;
                }
            }
        return $n;
    }

    /*(4).查看好友信息*/
    function GetInfo($toperson, $set, $lv)
    {
        $this->Login();
        $pack = 'VER=1.4&CMD=GetInfo&SEQ='.rand(1,9000).'&UIN=' . $this -> uin . '&LV='.$lv.'&UN='.$toperson;//配置信息详细程度LV
        $return =$this->request_by_curl($pack);
        $message=explode('&'.$set.'=',$return);
        $need_arr=explode('&',$message[1]);
        $need=$need_arr[0];
        //print_r($message);
        //print_r($message["$set"]);
        return $need;
    }
    /*(5).查看好友列表*/
    function GetList()
    {
        $this->Login();
        $pack = 'VER=1.4&CMD=List&SEQ='.rand(1,9000).'&UIN='.$this->uin;//配置信息详细程度LV
        $return =$this->request_by_curl($pack);
        $need = explode('&UN=',$return);
        return $need[1];
    }
	/*QQ机器人*/
	function robot($msg,$username)
	{
		global $method;
		if($method=='robot') {
		$apiurl='http://i.itpk.cn/api.php?question=';
		$cqname='QQ智能机器人';//你的昵称
		$ch=curl_init($apiurl.$msg);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content=curl_exec($ch);
		curl_close($ch);
		$content= str_replace("[cqname]",$cqname,$content);
		$content= str_replace("[name]",$username,$content);
        return $content;
		} elseif ($method=='robot2') {
		$apiurl='http://xink.aliapp.com/api/robot/api.php?msg='.$msg.'&ch='.$username;
		$ch=curl_init($apiurl);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content=curl_exec($ch);
		curl_close($ch);
        return $content;
		} elseif ($method=='robot3') {
		$apiurl='http://umin.aliapp.com/api.php?um='.$msg;
		$ch=curl_init($apiurl);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content=curl_exec($ch);
		curl_close($ch);
        return $content;
		}
    }

    function request_by_curl($fields)
    {
  $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://{$this->host}:{$this->port}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        //curl_setopt($ch, CURLOPT_PROXY, PROXYIP);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "iQQol Client");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    function HexToText($hex)
    {
        $str = "";
        for($i = 0; $i < strlen($hex); $i += 2)
        {
            $str .=  chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $str;
    }
}
class SET_3GQQ
{
    function __construct($qq ,$sid)
    {
        $this->qq = $qq;
        $this->sid = $sid;
    }
    function Login()
    {
        $pack = "http://q16.3g.qq.com/g/s?sid=".$this->sid."&aid=chgStatus&s=10";
        $return = $this->request_by_curl($pack);
        $return_str1 = explode('ontimer="',$return);
        $return_str2 = explode('">',$return_str1[1]);
        $return_url = $return_str2[0];
        $return = $this->request_by_curl($return_url);
        return $return;
    }
    function Logout()
    {
        $pack = "http://q16.3g.qq.com/g/s?sid=".$this->sid."&aid=chgStatus&s=20";
        $return = $this->request_by_curl($pack);
        $return_str1 = explode('ontimer="',$return);
        $return_str2 = explode('">',$return_str1[1]);
        $return_url = $return_str2[0];
        $return = $this->request_by_curl($return_url);
        return $return;
    }
    function request_by_curl($fields)
    {
  $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fields);
        curl_setopt($ch, CURLOPT_PROXY, PROXYIP);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "iQQol Client");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

?>