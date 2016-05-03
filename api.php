<?php
//API功能

$mod='blank';
$nosecu=true;
include("./includes/common.php");

if($_GET['my']=='siderr') {
	$qq=daddslashes($_GET['qq']);
	$sid=daddslashes($_GET['sid']);
	$err=daddslashes($_GET['err']);
	if($err=='sid')
	$sql="update `".DBQZ."_qq` set `status` ='0' where `qq`='$qq' and `sid`='$sid'";
	if($err=='skey')
	$sql="update `".DBQZ."_qq` set `status2` ='0' where `qq`='$qq' and `sid`='$sid'";
	$sds=$DB->query($sql);
	if($sds)exit('0');
	else exit('-1');
}elseif($_GET['my']=='siteinfo') {
	$zongs=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE 1");
	$users=$DB->count("SELECT count(*) from ".DBQZ."_user WHERE 1");
	$qqs=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE 1");
	if(function_exists("sys_getloadavg"))
		$fz=sys_getloadavg();
	else
		$fz=null;
	$auth=md5(md5(md5($authcode)));
	$siteinfo=array('name'=>$conf['sitename'],'version'=>VERSION,'authcode'=>$auth,'users'=>$users,'zongs'=>$zongs,'qqs'=>$qqs,'times'=>$info['times'],'last'=>$info['last'],'fz'=>$fz,'app_version'=>$conf['app_version'],'app_log'=>$conf['app_log'],'app_start_is'=>$conf['app_start_is'],'app_start'=>$conf['app_start']);
	echo json_encode($siteinfo);
	exit;
}elseif($_GET['my']=='coininfo') {
	$siteinfo=array('coin_name'=>$conf['coin_name'],'coin_swich'=>$conf['jifen'],'rules_2'=>$rules[2],'rules_3'=>$rules[3],'rules_4'=>$rules[4],'rules_5'=>$rules[5],'rules_6'=>$rules[6],'vip_coin'=>$conf['vip_coin'],'vip_func'=>$conf['vip_func'],'vip_sys'=>$conf['vip_sys'],'qd_jifen'=>$conf['qd_jifen']);
	echo json_encode($siteinfo);
	exit;
}elseif($_GET['my']=='gg'){	
	header("content-Type: text/html; charset=utf-8");
	$gg=$conf['gg'];
	echo $gg;
}elseif($_GET['my']=='shop'){	
	header("content-Type: text/html; charset=utf-8");
	$shop=$conf['shop'];
	echo $shop;
}elseif($_GET['my']=='client') {
	if($islogin==1){
		$act=daddslashes($_GET['act']);
		if($act=='syslist'){
			$result['code']=0;
			$result['count']=$conf['sysnum'];
			$show=explode('|',$conf['show']);
			for($i=1;$i<=$conf['sysnum'];$i++){
				$all_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$i'");
				$my_sys=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE sysid='$i' and lx='$gl'");
				$result['data'][]=array('id'=>$i,'all'=>$all_sys,'my'=>$my_sys,'max'=>$conf['max'],'pl'=>$show[($i-1)]);
			}
			echo json_encode($result);
		}elseif($act=='user'){
			$result=array('userid'=>$row['userid'],'user'=>$row['user'],'jobnum'=>$row['num'],'qqnum'=>$row['qqnum'],'regdate'=>$row['date'],'lastdate'=>$row['last'],'regip'=>$row['zcip'],'lastip'=>$row['dlip'],'email'=>$row['email'],'phone'=>$row['phone'],'coin'=>$row['coin'],'vip'=>$row['vip'],'vipdate'=>$row['vipdate'],'mail_on'=>$row['mail_on'],'daili'=>$row['daili'],'daili_rmb'=>$row['daili_rmb'],'daili_qq'=>$row['daili_qq']);
			echo json_encode($result);
		}elseif($act=='rw'){
			$jobid=isset($_GET['jobid'])?daddslashes($_GET['jobid']):null;
			$row=$DB->get_row("SELECT *FROM ".DBQZ."_job where jobid='{$jobid}' and lx='{$gl}' limit 1");
			if($row['type']==3){
				$row['qqstr']=json_decode($row['url']);
				$url=qqjob_decode($row['url']);
				$row['url']=$url['url'];}
			echo json_encode($row);
		}elseif($act=='list'){
			if(isset($_GET['qq'])) {
				$qq=daddslashes($_GET['qq']);
				$sql="proxy='".$qq."'";
				$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE proxy='{$qq}' and lx='{$gl}'");
			}elseif(isset($_GET['sys'])) {
				$sysid=daddslashes($_GET['sys']);
				$sql="sysid='".$sysid."'";
				$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE lx='{$gl}' and sysid='{$sysid}'");
			}
			$pagesize=$conf['pagesize'];
			if (!isset($_GET['page'])) {
				$page = 1;
				$pageu = $page - 1;
			} else {
				$page = $_GET['page'];
				$pageu = ($page - 1) * $pagesize;
			}
			$s = ceil($gls / $pagesize);
			$result['code']=0;
			$result['count']=$gls;
			$result['page']=$page;
			$result['pagesize']=$pagesize;
			$result['pages']=$s;
			$rs=$DB->query("SELECT * FROM ".DBQZ."_job WHERE lx='{$gl}' and {$sql} order by jobid desc limit $pageu,$pagesize");
			while($myrow = $DB->fetch($rs))
			{
				if($myrow['type']==3){
					$myrow['qqstr']=json_decode($myrow['url']);
					$url=qqjob_decode($myrow['url']);
					$myrow['url']=$url['url'];}
				$result['data'][]=$myrow;
			}
			echo json_encode($result);
		}elseif($act=='qqlist'){
			$pagesize=$conf['pagesize'];
			if (!isset($_GET['page'])) {
				$page = 1;
				$pageu = $page - 1;
			} else {
				$page = $_GET['page'];
				$pageu = ($page - 1) * $pagesize;
			}
			$gls=$DB->count("SELECT count(*) from ".DBQZ."_job WHERE proxy='{$qq}' and lx='{$gl}'");
			$s = ceil($gls / $pagesize);
			$gxsid=$DB->count("SELECT count(*) from ".DBQZ."_qq WHERE status!='1' and lx='{$gl}'");
			$result['code']=0;
			$result['count']=$gls;
			$result['page']=$page;
			$result['pagesize']=$pagesize;
			$result['pages']=$s;
			$result['gxsid']=$gxsid;
			$rs=$DB->query("SELECT * FROM ".DBQZ."_qq WHERE lx='{$gl}' order by id desc limit $pageu,$pagesize");
			while($myrow = $DB->fetch($rs))
			{
				$myrow['pw']=authcode($myrow['pw'],'DECODE',SYS_KEY);
				$result['data'][]=$myrow;
			}
			echo json_encode($result);
		}elseif($act=='chat'){
			$pagesize=intval($_GET['pagesize']);
			if (!isset($_GET['page'])) {
				$page = 1;
				$pageu = $page - 1;
			} else {
				$page = intval($_GET['page']);
				$pageu = ($page - 1) * $pagesize;
			}
			$gls=$DB->count("SELECT count(*) from ".DBQZ."_chat WHERE 1");
			$s = ceil($gls / $pagesize);
			$result['code']=0;
			$result['count']=$gls;
			$result['page']=$page;
			$result['pagesize']=$pagesize;
			$result['pages']=$s;
			$rs=$DB->query("SELECT * FROM ".DBQZ."_chat WHERE 1 order by id desc limit $pageu,$pagesize");
			while($myrow = $DB->fetch($rs))
			{
			if($myrow['user']==$gl)
			$myrow['user']='我';
			if($myrow['to']==$gl)
			$myrow['to']='我';
			$myrow['nr']=strip_tags($myrow['nr']);
				$result['data'][]=$myrow;
			}
			echo json_encode($result);
		}else{
			$result['code']=-1;
			$result['error']='无效请求';
			echo json_encode($result);
		}
	}else{
		$result['code']=-4;
		$result['error']='登录失败，可能是密码错误或者身份失效了';
		echo json_encode($result);
	}
	exit;
}
?>