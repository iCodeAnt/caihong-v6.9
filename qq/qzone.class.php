<?php
/*
 *QQ空间多功能操作类
 *Author：消失的彩虹海 & 快乐是福 & 云上的影子
*/
class qzone{
	public $msg;
	public function __construct($uin,$sid,$skey=null){
		$this->uin=$uin;
		$this->sid=$sid;
		if(!empty($skey))$this->gtk=$this->getGTK($skey);
		else $this->gtk=time();
		$this->gtk=$this->getGTK($skey);
		$this->gtk2=$this->getGTK2($skey);
		$this->cookie='pt2gguin=o0'.$uin.'; uin=o0'.$uin.'; skey='.$skey.';';
		if(defined("SAE_ACCESSKEY"))$this->cookiefile=SAE_TMP_PATH.$uin.'.txt';
		else $this->cookiefile='./cookie/'.$uin.'.txt';
	}
	function gift($uin,$con){
		$url = "http://m.qzone.com/gift/giftweb?g_tk=".time();
		$post="action=3&itemid=106084&struin={$uin}&content=".urlencode($con)."&format=json&isprivate=0&sid={$this->sid}";
		$json=$this->get_curl($url,$post);
		$arr = json_decode($json, true);
		if(array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]=$this->uin." 送礼物成功！";
		}elseif($arr['code']==-3000){
			$this->sidzt=1;
			$this->msg[]=$this->uin." 未登录";
		}elseif($arr['code']==-10000){
			$this->msg[]=$this->uin." 收礼人设置了权限";
		}else{
			$this->msg[]=$this->uin." 送礼物失败！";
		}
	}
	function liuyan($uin,$con){
		$url = "http://m.qzone.com/msgb/fcg_add_msg";
		$post = "res_uin={$uin}&format=json&content={$con}&opr_type=add_comment&sid={$this->sid}";
		$json=$this->get_curl($url,$post);
		$arr = json_decode($json, true);
		if(array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]=$this->uin.' 为 '.$uin.' 刷留言成功[CP]';
		}elseif($arr['code']==-3000){
			$this->sidzt=1;
			$this->msg[]=$this->uin.' 为 '.$uin.' 刷留言失败[CP]！原因:'.$arr['message'];
		}else{
			$this->msg[]=$this->uin.' 为 '.$uin.' 刷留言失败[CP]！原因:'.$arr['message'];
		}
	}
	public function zyzan($uin){

		$url='http://w.qzone.qq.com/cgi-bin/likes/internal_dolike_app?g_tk='.$this->gtk;
		$post='qzreferrer=http://user.qzone.qq.com/'.$uin.'/main&appid=7030&face=0&fupdate=1&from=1&query_count=200&format=json&opuin='.$this->uin.'&unikey=http://user.qzone.qq.com/'.$uin.'&curkey=http://user.qzone.qq.com/'.$uin.'&zb_url=';
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$uin,$this->cookie);
		$arr=json_decode($json,true);
		if(@array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]=$this->uin.' 赞 '.$uin.' 主页成功[PC]';
		}elseif($arr[code]==-3000){
			$this->skeyzt=1;
			$this->msg[]=$this->uin.' 赞 '.$uin.' 主页失败[PC]！原因:'.$arr[message];
		}else{
			$this->msg[]=$this->uin.' 赞 '.$uin.' 主页失败[PC]！原因:'.$arr['message'];
		}
	}
	public function quantu(){
		if($shuos=$this->getnew()){
			foreach($shuos as $shuo){
				$albumid='';$lloc='';
				if($shuo['original']){
					$albumid=$shuo['original']['cell_pic']['albumid'];
					$lloc=$shuo['original']['cell_pic']['picdata'][0]['lloc'];
				}
				if($shuo['pic']){
					$albumid=$shuo['pic']['albumid'];
					$lloc=$shuo['pic']['picdata'][0]['lloc'];
				}
				if(!empty($albumid)) {
					$touin=$shuo['userinfo']['user']['uin'];
					$this->quantu_do($touin,$albumid,$lloc);
					if($this->skeyzt) break;
				}
			}
		}
	}
	public function quantu_do($touin,$albumid,$lloc){
		$url="http://app.photo.qq.com/cgi-bin/app/cgi_annotate_face?g_tk=".$this->gtk;
		$post="format=json&uin={$this->uin}&hostUin=$touin&faUin={$this->uin}&faceid=&oper=0&albumid=$albumid&lloc=$lloc&facerect=10_10_50_50&extdata=&inCharset=GBK&outCharset=GBK&source=qzone&plat=qzone&facefrom=moodfloat&faceuin={$this->uin}&writeuin={$this->uin}&facealbumpage=quanren&qzreferrer=http://user.qzone.qq.com/$uin/infocenter?via=toolbar";
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$uin.'/infocenter?via=toolbar',$this->cookie);
		$json=mb_convert_encoding($json, "UTF-8", "gb2312");
		$arr=json_decode($json,true);
		if(!array_key_exists('code',$arr)){
			$this->msg[]="圈{$touin}的图{$albumid}失败，原因：获取结果失败！";
		}elseif($arr['code']==-3000){
			$this->skeyzt=1;
			$this->msg[]="圈{$touin}的图{$albumid}失败，原因：SKEY已失效！";
		}elseif($arr['code']==0){
			$this->msg[]="圈{$touin}的图{$albumid}成功";
		}else{
			$this->msg[]="圈{$touin}的图{$albumid}失败，原因：".$arr['message'];
		}
	}
	public function getll(){
		$url='http://m.qzone.com/list?g_tk='.$this->gtk.'&res_attach=att%3Dpagenum%253D2%2526%26tl%3D1437169519&format=json&list_type=msg&action=0&res_uin='.$this->uin.'&count=20&sid='.$this->sid;
		$get=$this->get_curl($url);
		$arr = json_decode($get,true);
		if($arr['data']['vFeeds'])
			return $arr['data']['vFeeds'];
		else
			$this->msg[]='没有留言！';
	}
	public function cpdelll($uin,$id){
		$url = 'http://m.qzone.com/operation/operation_add?g_tk='.$this->gtk;
		$post='opr_type=delugc&res_type=334&res_id='.$id.'&real_del=0&res_uin='.$this->uin.'&format=json&sid='.$this->sid;
		$data = $this->get_curl($url,$post,1,$this->cookie);
		$arr=json_decode($data,true);
		if($json){
			if(array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]='删除 '.$uin.' 留言成功！';
			}elseif($arr['code']==-3000){
				$this->sidzt=1;
				$this->msg[]='删除 '.$uin.' 留言失败！原因:'.$arr['data']['msg'];
			}else{
				$this->msg[]='删除 '.$uin.' 留言失败！原因:'.$arr['data']['msg'];
			}
		}else{
			$this->msg[]='未知错误，删除失败！可能留言已删尽';
		}
	}
	public function delll(){
		if($liuyans=$this->getll()){
			foreach($liuyans as $row) {
				$cellid=$row['id']['cellid'];
				$uin=$row["userinfo"]['user']['uin'];
				if($cellid){
					$this->cpdelll($uin,$cellid);
				}
			}
		}
	}
	public function pczhuanfa($con,$touin,$tid){
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_forward_v6?g_tk='.$this->gtk;
		$post='tid='.$tid.'&t1_source=1&t1_uin='.$touin.'&signin=0&con='.urlencode($con).'&with_cmt=0&fwdToWeibo=0&forward_source=2&code_version=1&format=json&out_charset=UTF-8&hostuin='.$this->uin.'&qzreferrer=http://user.qzone.qq.com/'.$this->uin.'/infocenter';
		$ua = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 UBrowser/5.2.2466.102 Safari/537.36";
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin.'/311',$this->cookie,0,$ua);
		if($json){
			$arr=json_decode($json,true);
			if(array_key_exists('code',$arr) && $arr['code']==0){
				$this->shuotid=$arr[tid];
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说成功[PC]';
			}elseif($arr[code]==-3000){
				$this->skeyzt=1;
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说失败[PC]！原因:'.$arr['message'];
			}elseif(array_key_exists('code',$arr)){
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说失败[PC]！原因'.$json;
			}
		}else{
			$this->msg[]=$this->uin.'获取转发 '.$touin.' 说说结果失败[PC]';
		}
	}
	public function cpzhuanfa($con,$touin,$tid){
		$url='http://m.qzone.com/operation/operation_add?g_tk='.$this->gtk;
		$post='res_id='.$tid.'&res_uin='.$touin.'&format=json&reason='.urlencode($con).'&res_type=311&opr_type=forward&operate=1&sid='.$this->sid;
		$json=$this->get_curl($url,$post,1);
		if($json){
			$arr=json_decode($json,true);
			if(array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说成功[CP]';
			}elseif($arr['code']==-3000){
				$this->sidzt=1;
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说失败[CP]！原因:'.$arr['message'];
			}else{
				$this->msg[]=$this->uin.'转发 '.$touin.' 说说失败[CP]！原因:'.$arr['message'];
			}
		}else{
			$this->msg[]=$this->uin.'获取转发 '.$touin.' 说说结果失败[CP]';
		}
	}
	public function zhuanfa($do=0,$uins=array(),$con=null){
		if(count($uins)<=1) {
			$uin = $uins[0];
			if($shuos=$this->getmynew($uin)){			
				foreach($shuos as $shuo){
					$cellid=$shuo['tid'];
					if($do){
						$this->pczhuanfa($con,$uin,$cellid);
						if($this->skeyzt) break;
					}else{
						$this->cpzhuanfa($con,$uin,$cellid);
						if($this->sidzt) break;
					}
				}
			}
		} else {
			if($shuos=$this->getnew()){			
				foreach($shuos as $shuo){
					$uin = $shuo['userinfo']['user']['uin'];
					if(in_array($uin,$uins)) {
						$cellid=$shuo['id']['cellid'];
						if($do){
							$this->pczhuanfa($con,$uin,$cellid);
							if($this->skeyzt) break;
						}else{
							$this->cpzhuanfa($con,$uin,$cellid);
							if($this->sidzt) break;
						}
					}
				}
			}
		}
	}
	public function timeshuo($content='',$time,$richval=''){
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_publish_timershuoshuo_v6?g_tk='.$this->gtk;		$post='syn_tweet_verson=1&paramstr=1&pic_template=';
		if($richval){
			$post.='&richtype=1&richval=,'.$richval.'&pic_bo=bgBuAAAAAAADACU! bgBuAAAAAAADACU!';
		}
		$post.='&special_url=&subrichtype=1&con='.$content.'&feedversion=1&ver=1&ugc_right=1&to_tweet=0&to_sign=0&time='.$time.'&hostuin='.$this->uin.'&code_version=1&format=json';
		
		$json=$this->get_curl($url,$post,0,$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->shuotid=$arr['tid'];
				$this->msg[]=$this->uin.' 发布定时说说成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]=$this->uin.' 发布定时说说失败[PC]！原因:SID已失效，请更新SID';
			}else{
				$this->msg[]=$this->uin.' 发布定时说说失败[PC]！原因'.$json;
			}
		}else{
			$this->msg[]=$this->uin.' 获取发布定时说说结果失败[PC]';
		}
	}
	public function timedel(){
		$sendurl='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_pubnow_timershuoshuo_v6?g_tk='.$this->gtk;
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_del_timershuoshuo_v6?g_tk='.$this->gtk;
		$post='hostuin='.$this->uin.'&tid=1&time=1426176000&code_version=1&format=json&qzreferrer=http://user.qzone.qq.com/'.$this->uin.'/311';
	}
	public function cpqd($content='签到',$sealid='10761'){
		$url='http://m.qzone.com/mood/publish_signin?g_tk='.$this->gtk;
		$post='opr_type=publish_signin&res_uin='.$this->uin.'&content='.$content.'&lat=0&lon=0&lbsid=&seal_id='.$sealid.'&seal_proxy=&is_winphone=0&source_name=&format=json&sid='.$this->sid;
		$json=$this->get_curl($url,$post,0,$this->cookie);
		$arr=json_decode($json,true);
		if(@array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]=$this->uin.' 签到成功[CP]';
		}elseif($arr['code']==-3000){
				$this->sidzt=1;
				$this->msg[]=$this->uin.' 签到失败[CP]！原因:SID已失效，请更新SID';
		}elseif($arr['code']==-11210){
			$this->msg[]=$this->uin.' 签到失败[CP]！原因:'.$arr['message'];
		}elseif(@array_key_exists('code',$arr)){
			$this->msg[]=$this->uin.' 签到失败[CP]！原因:'.$arr['message'];
		}else{
			$this->msg[]=$this->uin.' 签到失败[CP]！原因:'.$json;
		}
	
	}
	public function pcqd($content='',$sealid='10761'){
		$url='http://snsapp.qzone.qq.com/cgi-bin/signin/checkin_cgi_publish?g_tk='.$this->gtk;
		$post='qzreferrer=http%3A%2F%2Fctc.qzs.qq.com%2Fqzone%2Fapp%2Fcheckin_v4%2Fhtml%2Fcheckin.html&plattype=1&hostuin='.$this->uin.'&seal_proxy=&ttype=1&termtype=1&content='.$content.'&seal_id='.$sealid.'&uin='.$this->uin.'&time_for_qq_tips='.time().'&paramstr=1';
		$get=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin.'/311',$this->cookie);
		preg_match('/callback\((.*?)\)\; <\/script>/is',$get,$json);
		if($json=$json[1]){
			$arr=json_decode($json,true);
			$arr['feedinfo']='';
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]=$this->uin.' 签到成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]=$this->uin.' 签到失败[PC]！原因:SID已失效，请更新SID';
			}elseif(@array_key_exists('code',$arr)){
				$this->msg[]=$this->uin.' 签到失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]=$this->uin.' 签到失败[PC]！原因:'.$json;
			}
		}else{
			$this->msg[]=$this->uin.' 获取签到结果失败[PC]';
		}
		
	}
	public function qiandao($do=0,$content='签到',$sealid=10319){
		if($do){
			$this->pcqd($content,$sealid);
			if($this->skeyzt) break;
		}else{
			$this->cpqd($content,$sealid);
		}
	}

	public function cpshuo($content,$richval='',$sname='',$lon='',$lat=''){
		$url='http://m.qzone.com/mood/publish_mood?g_tk='.$this->gtk;
		$post='opr_type=publish_shuoshuo&res_uin='.$this->uin.'&content='.$content.'&richval='.$richval.'&lat='.$lat.'&lon='.$lon.'&lbsid=&issyncweibo=0&is_winphone=2&format=json&source_name='.$sname.'&sid='.$this->sid;
		$result=$this->get_curl($url,$post,1,$this->cookie);
		$json=json_decode($result,true);
		if(@array_key_exists('code',$json) && $json['code']==0){
			$this->msg[]=$this->uin.' 发布说说成功[CP]';
		}elseif($arr['code']==-3000){
			$this->sidzt=1;
			$this->msg[]=$this->uin.' 发布说说失败[CP]！原因:SID已失效，请更新SID';
		}elseif(@array_key_exists('code',$json)){
			$this->msg[]=$this->uin.' 发布说说失败[CP]！原因:'.$arr['message'];
	}else{
			$this->msg[]=$this->uin.' 发布说说失败[CP]！原因:'.$result;
		}
	}
	public function pcshuo($content,$richval=0){
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_publish_v6?g_tk='.$this->gtk;
		$post='syn_tweet_verson=1&paramstr=1&pic_template=';
		if($richval){
			$post.="&richtype=1&richval=".$this->uin.",{$richval}&special_url=&subrichtype=1&pic_bo=uAE6AQAAAAABAKU!%09uAE6AQAAAAABAKU!";
		}else{
			$post.="&richtype=&richval=&special_url=";
		}
		$post.="&subrichtype=&con={$content}&feedversion=1&ver=1&ugc_right=1&to_tweet=0&to_sign=0&hostuin=".$this->uin."&code_version=1&format=json&qzreferrer=http://user.qzone.qq.com/".$this->uin."/311";
		$ua = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0";
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin.'/311',$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			$arr['feedinfo']='';
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]=$this->uin.' 发布说说成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]=$this->uin.' 发布说说失败[PC]！原因:SID已失效，请更新SID';
			}elseif($arr['code']==-10045){
				$this->msg[]=$this->uin.' 发布说说失败[PC]！原因:'.$arr['message'];
			}elseif(@array_key_exists('code',$arr)){
				$this->msg[]=$this->uin.' 发布说说失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]=$this->uin.' 发布说说失败[PC]！原因'.$json;
			}
		}else{
			$this->msg[]=$this->uin.' 获取发布说说结果失败[PC]';
		}
	}
	public function shuo($do=0,$content,$image=null,$sname=''){
		if(!empty($image)){
			if($pic=$this->get_curl($image))
				$richval=$this->uploadimg($pic);
		}else{
			$richval=null;
		}
		if($do){
			$this->pcshuo($content,$richval);
		}else{
			$this->cpshuo($content,$richval,$sname);
		}
	}

	public function pcdel($cellid){
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_delete_v6?g_tk='.$this->gtk;
		$post='hostuin='.$this->uin.'&tid='.$cellid.'&t1_source=1&code_version=1&format=json&qzreferrer=http://user.qzone.qq.com/'.$this->uin.'/main';
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin.'/311',$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]='删除说说'.$cellid.'成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]='删除说说'.$cellid.'失败[PC]！原因:SID已失效，请更新SID';
			}elseif(@array_key_exists('code',$json)){
				$this->msg[]='删除说说'.$cellid.'失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]='删除说说'.$cellid.'失败[PC]！原因:'.$json;
			}
		}else{
			$this->msg[]=$this->uin.'获取删除结果失败[PC]';
		}
	}
	public function cpdel($cellid){
		$url='http://m.qzone.com/operation/operation_add?g_tk='.$this->gtk;
		$post='opr_type=delugc&res_type=311&res_id='.$cellid.'&real_del=0&res_uin='.$this->uin.'&format=json&sid='.$this->sid;
		$json=$this->get_curl($url,$post,'http://m.qzone.com/infocenter?g_ut=3&g_f=6676',$this->cookie);
		$arr=json_decode($json,true);
		if(@array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]='删除说说'.$cellid.'成功[CP]';
		}elseif($arr['code']==-3000){
			$this->sidzt=1;
			$this->msg[]='删除说说'.$cellid.'失败[CP]！原因:SID已失效，请更新SID';
		}elseif(@array_key_exists('code',$json)){
			$this->msg[]='删除说说'.$cellid.'失败[CP]！原因:'.$arr['message'];
		}else{
			$this->msg[]='删除说说'.$cellid.'失败[CP]！原因:'.$json;
		}
	}
	public function shuodel($do=0){
		if($shuos=$this->getmynew()){
			//print_r($shuos);exit;
			foreach($shuos as $shuo){
				$cellid=$shuo['tid'];
				if($do){
					$this->pcdel($cellid);
					if($this->skeyzt) break;
				}else{
					$this->cpdel($cellid);
				}
			}
		}
	}
	public function cpreply($content,$uin,$cellid,$type,$param){
		$post='res_id='.$cellid.'&res_uin='.$uin.'&format=json&res_type='.$type.'&content='.urlencode($content).'&busi_param='.$param.'&opr_type=addcomment&sid='.$this->sid;
		$url='http://m.qzone.com/operation/publish_addcomment?g_tk='.$this->gtk;
		$json=$this->get_curl($url,$post,1,$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]='评论 '.$uin.' 的说说成功[CP]';
			}elseif($arr['code']==-3000){
				$this->sidzt=1;
				$this->msg[]='评论 '.$uin.' 的说说失败[CP]！原因:SID已失效，请更新SID';
			}elseif(@array_key_exists('message',$arr)){
				$this->msg[]='评论 '.$uin.' 的说说失败[CP]！原因:'.$arr['message'];
			}else{
				$this->msg[]='评论 '.$uin.' 的说说失败[CP]！原因:'.$json;
			}
		}else{
			$this->msg[]='获取评论'.$uin.'的说说结果失败[CP]！';
		}
	}
	public function pcreply($content,$uin,$cellid,$from,$richval=0){
		$post='topicId='.$uin.'_'.$cellid.'__'.$from.'&feedsType=100&inCharset=utf-8&outCharset=utf-8&plat=qzone&source=ic&hostUin='.$uin.'&isSignIn=&platformid=52&uin='.$this->uin.'&format=json&ref=feeds&content='.urlencode($content).'&richval=&richtype=&private=0&paramstr=1&qzreferrer=http://user.qzone.qq.com/'.$this->uin;
		$url='http://user.qzone.qq.com/q/taotao/cgi-bin/emotion_cgi_re_feeds?g_tk='.$this->gtk;
		$json=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin,$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			$arr['data']['feeds']='';
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]='评论 '.$uin.' 的说说成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]='评论 '.$uin.' 的说说失败[PC]！原因:SID已失效，请更新SID';
			}elseif($arr['code']==-10052 || $arr['code']==-10025){
				$this->msg[]='评论 '.$uin.' 的说说失败[PC]！原因:'.$arr['message'];
			}elseif(@array_key_exists('message',$arr)){
				$this->msg[]='评论 '.$uin.' 的说说失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]='评论 '.$uin.' 的说说失败[PC]！原因'.$json;
			}
		}else{
			$this->msg[]='获取评论结果失败[PC]';
		}
	}
	public function reply($do=0,$content='',$forbid=array(),$richval=null){
		if(preg_match("/\[随机\]/",$content) || $content==''){
			$str = @file_get_contents('sji.db');
			$a = explode('|',$str);
			$isrand=1;
		}else $isrand=0;
		if($shuos=$this->getnew()){
			foreach($shuos as $shuo){
				if($this->is_comment($this->uin,$shuo['comment']['comments']) && !in_array($shuo['userinfo']['user']['uin'],$forbid)){
					$appid=$shuo['comm']['appid'];
					$typeid=$shuo['comm']['feedstype'];
					$curkey=urlencode($shuo['comm']['curlikekey']);
					$uinkey=urlencode($shuo['comm']['orglikekey']);
					$uin=$shuo['userinfo']['user']['uin'];
					$from=$shuo['userinfo']['user']['from'];
					$cellid=$shuo['id']['cellid'];
					if($isrand==1)$content=$a[array_rand($a,1)];
					if($do){
						$this->pcreply($content,$uin,$cellid,$from,$richval);

						if($this->skeyzt) break;
					}else{						$param=$this->array_str($shuo['operation']['busi_param']);
						$this->cpreply($content,$uin,$cellid,$appid,$param);
					}
				}
			}
		}
	}
	public function cplike($uin,$type,$uinkey,$curkey){
		$post='opr_type=like&action=0&res_uin='.$uin.'&res_type='.$type.'&uin_key='.$uinkey.'&cur_key='.$curkey.'&format=json&sid='.$this->sid;
		$url='http://wap.m.qzone.com/praise/like?g_tk='.$this->gtk;
		$json=$this->get_curl($url,$post,1,$this->cookie);
		if($json){
			$arr=json_decode($json,true);
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[]='赞 '.$uin.' 的说说成功[CP]';
			}elseif($arr[code]==-3000){
				$this->sidzt=1;
				$this->msg[]='赞'.$uin.'的说说失败[CP]！原因:SID已失效，请更新SID';
			}elseif($arr['code']==-11210){
				$this->msg[]='赞 '.$uin.' 的说说失败[CP]！原因:'.$arr['message'];
			}elseif(@array_key_exists('message',$arr)){
				$this->msg[]='赞 '.$uin.' 的说说失败[CP]！原因:'.$arr['message'];
			}else{
				$this->msg[]='赞 '.$uin.' 的说说失败[CP]！原因:'.$json;
			}
		}else{
			$this->msg[]='获取赞'.$uin.'的说说结果失败[CP]！';
		}
	}
	public function pclike($uin,$curkey,$uinkey,$from,$appid,$typeid,$abstime,$fid){
		$post='qzreferrer=http://user.qzone.qq.com/'.$this->uin.'&opuin='.$this->uin.'&unikey='.$uinkey.'&curkey='.$curkey.'&from='.$from.'&appid='.$appid.'&typeid='.$typeid.'&abstime='.$abstime.'&fid='.$fid.'&active=0&fupdate=1';
		$url='http://w.qzone.qq.com/cgi-bin/likes/internal_dolike_app?g_tk='.$this->gtk;
		$get=$this->get_curl($url,$post,'http://user.qzone.qq.com/'.$this->uin.'/311',$this->cookie);
		preg_match('/callback\((.*?)\)\;/is',$get,$json);
		if($json=$json[1]){
			$arr=json_decode($json,true);
			if($arr['message']=='succ' || $arr['msg']=='succ'){
				$this->msg[]='赞 '.$uin.' 的说说成功[PC]';
			}elseif($arr['code']==-3000){
				$this->skeyzt=1;
				$this->msg[]='赞 '.$uin.' 的说说失败[PC]！原因:SID已失效，请更新SID';
			}elseif(@array_key_exists('message',$arr)){
				$this->msg[]='赞 '.$uin.' 的说说失败[PC]！原因:'.$arr['message'];
			}else{
				$this->msg[]='赞 '.$uin.' 的说说失败[PC]！原因:'.$json;
			}
		}else{
			$this->msg[]=$uin.' 获取赞结果失败[PC]';
		}
	}
	public function newpclike2($forbid=array())
	{
		$url = 'http://ic2.s51.qzone.qq.com/cgi-bin/feeds/feeds3_html_more?format=json&begintime=' . time() . '&count=20&uin=' . $this->uin . '&g_tk=' . $this->gtk;
		$json = $this->get_curl($url, 0, 0, $this->cookie);
		$arr = json_decode($json, true);

		if ($arr[code] == -3000) {
			$this->skeyzt = 1;
			$this->msg[] = $this->uin . '获取说说列表失败，原因:SKEY过期！[PC]';
		}
		else {
			$this->msg[] = $this->uin . '获取说说列表成功[PC]';
			$json = str_replace(array("\\x22", "\\x3C", "\/"), array('"', '<', '/'), $json);

			if (preg_match_all('/data\-unikey="([0-9A-Za-z\.\-\_\/\:]+)" data\-curkey="([0-9A-Za-z\.\-\_\/\:]+\/([0-9A-Za-z]+))" data\-clicklog="like" href="javascript\:\;"><i class="ui\-icon icon\-praise"><\/i>赞/iUs', $json, $arr)) {
				foreach ($arr[1] as $k => $row ) {
					preg_match('/\/(\d+)\//', $row, $match);
					$touin = $match[1];
					$type = 0;
					$key = $arr[2][$k];
					$fid = $arr[3][$k];

					if ($row != $key) {
						$type = 5;
					}
					if(!in_array($touin,$forbid))
					$this->pclike($touin,$key, $row, 1, '311', $type, time(), $fid);

					if ($this->skeyzt) break;
				}
			}
			else {
				$this->msg[] = $this->uin . '没有要赞的说说[PC]';
			}
		}
	}
	public function newpclike($forbid=array())
	{
		$url='http://taotao.qq.com/cgi-bin/emotion_cgi_get_mix_v6?uin='.$this->uin.'&inCharset=utf-8&outCharset=utf-8&hostUin='.$this->uin.'&notice=0&sort=0&pos=0&num=20&code_version=1&format=json&need_private_comment=1&g_tk='.$this->gtk;
		$json = $this->get_curl($url, 0, 1, $this->cookie,0,$ua);
		if($json){
			$arr=json_decode($json,true);
			//print_r($arr);exit;
			if(@array_key_exists('code',$arr) && $arr['code']==0){
				$this->msg[] = '获取说说列表成功[PC]';
				foreach ($arr['msglist'] as $k => $row ) {
					$touin = $row['uin'];
					$type = 0;
					$key = 'http://user.qzone.qq.com/'.$touin.'/mood/'.$row['tid'];
					
					$fid = $row['tid'];
					if(!in_array($touin,$forbid))
					$this->pclike($touin,$key, $key, 1, '311', $type, time(), $fid);

					if ($this->skeyzt) break;
				}
			}elseif($arr[code]==-3000){
				$this->sidzt=1;
				$this->msg[]='获取最新说说失败！[PC]原因:SKEY过期！';
			}elseif(@array_key_exists('message',$arr)){
				$this->msg[]='获取最新说说失败！[PC]原因:'.$arr['message'];
			}else{
				$this->msg[]='获取最新说说失败！[PC]原因:'.$json;
			}
		}else{
			$this->msg[]='获取最新说说失败！[PC]';
		}

	}
	public function like($do=0,$forbid=array()){
		if ($do==2) {
			$this->newpclike($forbid);
		}
		elseif($shuos=$this->getnew()){
			foreach($shuos as $shuo){
				$like=$shuo['like']['isliked'];
				if($like==0 && !in_array($shuo['userinfo']['user']['uin'],$forbid)){
					$appid=$shuo['comm']['appid'];
					$typeid=$shuo['comm']['feedstype'];
					$curkey=urlencode($shuo['comm']['curlikekey']);
					$uinkey=urlencode($shuo['comm']['orglikekey']);
					$uin=$shuo['userinfo']['user']['uin'];
					$from=$shuo['userinfo']['user']['from'];
					$abstime=$shuo['comm']['time'];
					$cellid=$shuo['id']['cellid'];
					if($do){
						$this->pclike($uin,$curkey,$uinkey,$from,$appid,$typeid,$abstime,$cellid);
						if($this->skeyzt) break;
					}else{
						$this->cplike($uin,$appid,$uinkey,$curkey);
					}
				}
			}
		}
	}



	public function getnew($do=''){
		$ua='Mozilla/5.0 (Linux; U; Android 4.0.3; zh-CN; Lenovo A390t Build/IML74K) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 UCBrowser/9.8.9.457 U3/0.8.0 Mobile Safari/533.1';
		if($do=='my'){
			$url="http://m.qzone.com/list?g_tk=".$this->gtk."&res_attach=att%3D10&format=json&list_type=shuoshuo&action=0&res_uin=".$this->uin."&count=20&sid=".$this->sid;
		}else{
			$url="http://m.qzone.com/get_feeds?res_type=0&res_attach=&refresh_type=2&format=json&sid=".$this->sid;
		}
		$json=$this->get_curl($url,0,1,'sid='.$this->sid.';');
		if(!$json)
			$json=$this->get_curl('http://qqol.aliapp.com/api/getfeeds.php?sid='.$this->sid);
		$arr=json_decode($json,true);
		if(@array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]='获取说说列表成功！';
			return $arr['data']['vFeeds'];
		}elseif(strpos($arr['message'],'务器繁忙')){
			$this->msg[]='获取最新说说失败！原因:'.$arr['message'];
			return false;
		}elseif(strpos($arr['message'],'登录')){
			$this->sidzt=1;
			$this->msg[]='获取最新说说失败！原因:'.$arr['message'];
			return false;
		}else{
			$this->msg[]='获取最新说说失败！原因:'.$arr['message'];
			return false;
		}	
	}

	public function getmynew($uin=null){
		if(empty($uin))$uin=$this->uin;
		$url='http://sh.taotao.qq.com/cgi-bin/emotion_cgi_feedlist_v6?hostUin='.$uin.'&ftype=0&sort=0&pos=0&num=10&replynum=0&code_version=1&format=json&need_private_comment=1&g_tk='.$this->gtk;
		$json=$this->get_curl($url,0,0,$this->cookie);
		$arr=json_decode($json,true);
		if(@array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]='获取说说列表成功！';
			return $arr['msglist'];
		}else{
			$this->msg[]='获取最新说说失败！原因:'.$arr['message'];
			return false;
		}	
	}

	public function flower(){
		$url = 'http://m.qzone.com/flower/rattan/cgi-bin/touch_cgi_plant?t=0.90354'.time();
		//水滴
		$post = "act=rain&uin=".$this->uin."&newflower=2&fl=1&fupdate=1&format=json&sid=".$this->sid;
		$this->get_curl($url,$post);
		//爱心
		$post = "act=love&uin=".$this->uin."&newflower=2&fl=1&fupdate=1&format=json&sid=".$this->sid;
		$this->get_curl($url,$post);
		//阳光
		$post = "act=sun&uin=".$this->uin."&newflower=2&fl=1&fupdate=1&format=json&sid=".$this->sid;
		$this->get_curl($url,$post);
		//树叶
		$post = "act=nutri&uin=".$this->uin."&newflower=2&fl=1&fupdate=1&format=json&sid=".$this->sid;
		$this->get_curl($url,$post);
	}
	public function get_curl($url,$post=0,$referer=1,$cookie=0,$header=0,$ua=0,$nobaody=0){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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
			if($referer==1){
				curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
			}else{
				curl_setopt($ch, CURLOPT_REFERER, $referer);
			}
		}
		if($ua){
			curl_setopt($ch, CURLOPT_USERAGENT,$ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.5 Mobile Safari/533.1');
		}
		if($nobaody){
			curl_setopt($ch, CURLOPT_NOBODY,1);
		}
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	
	}
	public function get_socket($url,$post=0,$cookie=0,$referer=1){ //Author:消失的彩虹海
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
		$str=strstr($str,'{');
		return $str;
	}
	private function getGTK($skey){
        $len = strlen($skey);
        $hash = 5381;
        for($i = 0; $i < $len; $i++){
            $hash += ($hash << 5) + ord($skey[$i]);
        }
        return $hash & 0x7fffffff;//计算g_tk
    }
	private function getGTK2($skey){
		$skey = str_replace('@','',$skey);
		$salt = 5381;
		$md5key = 'tencentQQVIP123443safde&!%^%1282';
		$hash = array();
		$hash[] = ($salt << 5);
		$len = strlen($skey);
		for($i = 0; $i < $len; $i ++)
		{
			$ASCIICode = mb_convert_encoding($skey[$i], 'UTF-32BE', 'UTF-8');
			$ASCIICode = hexdec(bin2hex($ASCIICode));
			$hash[] = (($salt << 5) + $ASCIICode);
			$salt = $ASCIICode;
		}
		$md5str = md5(implode($hash) . $md5key);
		return $md5str;
	}
	private function is_comment($uin,$arrs){
        if($arrs){
	    	foreach($arrs as $arr){
    	   		if($arr['user']['uin'] == $uin){
        			return false;
            		break;
        		}
    		}
        	return true; 
        }else{
        	return true; 
        }
	}
	private function array_str($array){
    	$str='';
        if($array[-100]){
	        $array100=explode(' ',trim($array[-100]));
    	    $new100=implode('+',$array100);
            $array[-100]=$new100;
        }
 		foreach($array as $k=>$v){
            if($k!='-100'){
	    		$str=$str.$k.'='.$v.'&';
            }
 		}
        $str=urlencode($str.'-100=').$array[-100].'+';
        $str=str_replace(':','%3A',$str);
    	return $str;
    }
	public function uploadimg($image,$image_size=array()){
		$url='http://up.qzone.com/cgi-bin/upload/cgi_upload_pic_v2';
        $post='picture='.urlencode(base64_encode($image)).'&base64=1&hd_height='.$image_size[1].'&hd_width='.$image_size[0].'&hd_quality=90&output_type=json&preupload=1&charset=utf-8&output_charset=utf-8&logintype=sid&Exif_CameraMaker=&Exif_CameraModel=&Exif_Time=&uin='.$this->uin.'&sid='.$this->sid;
        $data=preg_replace("/\s/","",$this->get_curl($url,$post));
		preg_match('/_Callback\((.*)\);/',$data,$arr);
		$data=json_decode($arr[1],true);
        if($data && array_key_exists('filemd5',$data)){
			$this->msg[]='图片上传成功！';
			$post='output_type=json&preupload=2&md5='.$data['filemd5'].'&filelen='.$data['filelen'].'&batchid='.time().rand(100000,999999).'&currnum=0&uploadNum=1&uploadtime='.time().'&uploadtype=1&upload_hd=0&albumtype=7&big_style=1&op_src=15003&charset=utf-8&output_charset=utf-8&uin='.$this->uin.'&sid='.$this->sid.'&logintype=sid&refer=shuoshuo';
			$img=preg_replace("/\s/","",$this->get_curl($url,$post));
			preg_match('/_Callback\(\[(.*)\]\);/',$img,$arr);
			$data=json_decode($arr[1],true);
            if($data && array_key_exists('picinfo',$data)){
				if($data[picinfo][albumid]!=""){
					$this->msg[]='图片信息获取成功！';
					return ''.$data['picinfo']['albumid'].','.$data['picinfo']['lloc'].','.$data['picinfo']['sloc'].','.$data['picinfo']['type'].','.$data['picinfo']['height'].','.$data['picinfo']['width'].',,,';
				}else{
					$this->msg[]='图片信息获取失败！';
					return;
				}
            }else{
                $this->msg[]='图片信息获取失败！';
                return;
            }
		}else{
			$this->msg[]='图片上传失败！原因：'.$data['msg'];
            return;
        }
	}


	public function scqd()
	{
		$url = "http://ebook.3g.qq.com/user/v3/normalLevel/sign?sid=" . $this->sid . "&g_ut=2";
		$this->get_curl($url);
		$this->msg[] = "书城签到成功~";
	}

	public function vipqd()
	{
		$url = "http://vipfunc.qq.com/growtask/sign.php?cb=vipsign.signCb&action=daysign&actId=16&t=" . time() . "141&g_tk=" . $this->gtk2;
		$dd = "http://vipfunc.qq.com/act/client_oz.php?action=client&g_tk=" . $this->gtk2;
		$this->get_curl($url,0,0,$this->cookie);
		$this->get_curl($dd,0,0,$this->cookie);

		$this->get_curl("http://iyouxi.vip.qq.com/ams3.0.php?_c=page&actid=23314&callback=vipSignNew.signCb&format=json&g_tk=" . $this->gtk2 ."&cachetime=".time(),0,0,$this->cookie);
		$this->get_curl("http://iyouxi.vip.qq.com/ams3.0.php?_c=load&actid=23314&isLoadUserInfo=1&format=json&g_tk=" . $this->gtk2 ."&cachetime=".time(),0,0,$this->cookie);
		$this->get_curl("http://iyouxi.vip.qq.com/ams3.0.php?actid=23868&rand=0.387115".time()."&g_tk=".$this->gtk2."&sid=".$this->sid."&format=json",0,0,$this->cookie);
		$this->get_curl("http://iyouxi.vip.qq.com/jsonp.php?_c=page&actid=5474&isLoadUserInfo=1&format=json&g_tk=".$this->gtk2."&_=".time(),0,0,$this->cookie);
		$this->msg[] = "VIP签到成功~";
	}
	public function lzqd(){
		$url='http://share.music.qq.com/fcgi-bin/dmrp_activity/fcg_feedback_send_lottery.fcg?activeid=110&rnd='.time().'157&g_tk='.$this->gtk.'&uin='.$this->uin.'&hostUin=0&format=json&inCharset=UTF-8&outCharset=UTF-8&notice=0&platform=activity&needNewCode=1';
		$data = $this->get_curl($url,0,'http://y.qq.com/vip/fuliwo/index.html',$this->cookie);
		$arr = json_decode($data, true);
		if(array_key_exists('code',$arr) && $arr['code']==0){
			if($arr['data']['alreadysend']==1)
				$this->msg[]='您今天已经签到过了！';
			else
				$this->msg[]='绿钻签到成功！';
		}else{
			$this->msg[]='绿钻签到失败！';
		}

		$url='http://share.music.qq.com/fcgi-bin/dmrp_activity/fcg_dmrp_draw_lottery.fcg?activeid=159&rnd='.time().'482&g_tk='.$this->gtk.'&uin='.$this->uin.'&hostUin=0&format=json&inCharset=UTF-8&outCharset=UTF-8&notice=0&platform=activity&needNewCode=1';
		$data = $this->get_curl($url,0,'http://y.qq.com/vip/fuliwo/index.html',$this->cookie);
		$arr = json_decode($data, true);
		if(array_key_exists('code',$arr) && $arr['code']==0){
			$this->msg[]='绿钻抽奖成功！';
		}elseif($arr['code']==200008){
			$this->msg[]='您没有抽奖机会！';
		}else{
			$this->msg[]='绿钻抽奖失败！';
		}
		
	}
	public function pqd(){
		$url="http://iyouxi.vip.qq.com/ams3.0.php?g_tk=".$this->gtk2."&pvsrc=102&ozid=511022&vipid=&actid=32961&sid=".$this->sid."&callback=json14330311066734&cache=3654"; //Powered by 云影 QQ8711973
		$data = $this->get_curl($url,0,0,$this->cookie);
		$arr = json_decode($data, true);
		if($arr['ret']==0){
			$this->msg[]='签到成功！';
		}else{
			$this->msg[]='签到失败！';
		}	
	}

	public function is_zan(){
		$ch=curl_init('http://ish.z.qq.com/infocenter_v2.jsp?B_UID='.$this->uin.'&sid='.$this->sid.'&g_ut=1');
		curl_setopt($ch, CURLOPT_USERAGENT, "MQQBrowser WAP (Nokia/MIDP2.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$contents=curl_exec($ch);
		curl_close($ch);
		preg_match_all('@<a href="http://blog(\d)0.z.qq.com/like/like_action.jsp(.*)">赞\(\d{1,}\)</a>@Ui',$contents,$url_array);
		$n=count($url_array['2']);
		for($i=0;$i<$n;$i++){
			$url="http://blog".$url_array['1'][$i]."0.z.qq.com/like/like_action.jsp".$url_array['2'][$i];
			$ch=curl_init(html_entity_decode($url));
			curl_setopt($ch, CURLOPT_USERAGENT, "MQQBrowser WAP (Nokia/MIDP2.0)");
			curl_setopt($ch,CURLOPT_TIMEOUT,5);
			curl_setopt($ch,CURLOPT_NOBODY,0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
			curl_exec($ch);
			curl_close($ch);
		}
	}

	public function mood_reply($content=''){
		if(preg_match("/\[随机\]/",$content) || $content==''){
			$str = @file_get_contents('sji.db');
			$a = explode('|',$str);
			$isrand=1;
		}else $isrand=0;
		$url = 'http://ish.z.qq.com/feeds_friends.jsp?B_UID='.$this->uin.'&sid='.$this->sid.'&type=taotao&g_ut=2';
		$ch=curl_init($url);//获取好友动态列表
		curl_setopt($ch, CURLOPT_USERAGENT, "MQQBrowser WAP (Nokia/MIDP2.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$b=curl_exec($ch);
		curl_close($ch);
		$type=1;
		if ($type==1){
			preg_match_all('@<a href="http://info(.*).z.qq.com/infocenter/myfeed_mood.jsp\?sid=(.*)B_(.*)_source(.*)">评论\(0\)</a>@',$b,$ur_array);
		}elseif ($type==2){
			preg_match_all('@<a href="http://blog(.*).z.qq.com/mood/mood_detail.jsp\?sid=(.*)B_(.*)_source(.*)">评论\(\d{1,}\)</a>@',$b,$ur_array);
		}elseif ($type==3){
			preg_match_all('@(.*)/mood_detail.jsp\?sid=(.*)B_(.*)_source(.*)">评论\(\d{1,}\)</a>@',$b,$ur_array);
		}
		$n=count($ur_array['3']);
		for($i=0;$i<$n;$i++){
			$ur=html_entity_decode($ur_array['3'][$i]);
			if($isrand==1)$content=$a[array_rand($a,1)];
			$nr=str_replace("\r\n","",$content);
			$urla='http://info60.z.qq.com/infocenter/myfeed_mood.jsp?sid='.$this->sid.'&g_ut=2';
			$po="B_{$ur}_source=1&comeFrom=mainpage_guest&content=".urlencode($nr);
			$ch=curl_init();//提交评论
			curl_setopt($ch,CURLOPT_URL,$urla);
			curl_setopt($ch,CURLOPT_TIMEOUT,5);
			curl_setopt($ch, CURLOPT_USERAGENT, "MQQBrowser WAP (Nokia/MIDP2.0)");
			curl_setopt($ch,CURLOPT_NOBODY,0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$po);
			curl_exec($ch);
			curl_close($ch);
		}
	}
}