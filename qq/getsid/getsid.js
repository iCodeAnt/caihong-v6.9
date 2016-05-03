var xiha={
	postData: function(url, parameter, callback, dataType, ajaxType) {
		if(!dataType) dataType='json';
		$.ajax({
			type: "POST",
			url: url,
			async: true,
			dataType: dataType,
			json: "callback",
			data: parameter,
			success: function(data) {
				if (callback == null) {
					return;
				} 
				callback(data);
			},
			error: function(error) {
				alert('创建连接失败');
			}
		});
	}
}
function login(uin,pwd,p,vcode,pt_verifysession){
	$('#load').html('正在登录，请稍等...');
	var loginurl="qq/getsid/login.php?do=qqlogin";
	xiha.postData(loginurl,"uin="+uin+"&pwd="+pwd+"&p="+p+"&vcode="+vcode+"&pt_verifysession="+pt_verifysession+"&r="+Math.random(1), function(d) {
		if(d.saveOK ==0){
			$('#load').html('SID获取成功，请稍等...');

			var backurl = "index.php?mod=addqq&my=add&qq="+uin+"&qpwd="+encodeURIComponent(pwd)+"&qsid="+d.sid+"&skey="+encodeURIComponent(d.skey);
			//alert("QQ "+uin+" 登录成功！");
			window.location.href=backurl;
		}else if(d.saveOK ==4){
			$('#load').html('验证码错误，请重新登录。');
			$('#submit').attr('do','submit');
			$('.code').hide();
			$('#code').val("");
		}else if(d.saveOK ==3){
			$('#load').html('您输入的帐号或密码不正确，请重新输入密码！');
			$('#submit').attr('do','submit');
			$('.code').hide();
			$('#login').show();
		}else{
			$('#load').html(d.msg);
			$('#submit').attr('do','submit');
		}
	});
	
}

function getvc(uin,sig){
	$('#load').html('获取验证码，请稍等...');
	var getvcurl="qq/getsid/login.php?do=getvc";
	xiha.postData(getvcurl,'uin='+uin+'&sig='+sig+'&r='+Math.random(1), function(d) {
		if(d.saveOK ==0){
			$('#load').html('请输入验证码');
			$('#codeimg').attr('vc',d.vc);
			$('#codeimg').html('<img onclick="getvc(\''+uin+'\',\''+d.vc+'\')" src="qq/getsid/login.php?do=getvcpic&uin='+uin+'&sig='+d.vc+'&r='+Math.random(1)+'" title="点击刷新">');
			$('#submit').attr('do','code');
			$('.code').show();
		}else{
			alert(d.msg);
		}
	});

}
function dovc(uin,code,vc){
	$('#load').html('验证验证码，请稍等...');
	var getvcurl="qq/getsid/login.php?do=dovc";
	xiha.postData(getvcurl,'uin='+uin+'&ans='+code+'&sig='+vc+'&r='+Math.random(1), function(d) {
		if(d.rcode ==0){
			var pwd=$('#pwd').val();
			p=getmd5(uin,pwd,d.randstr.toUpperCase());
			login(uin,pwd,p,d.randstr.toUpperCase(),d.sig);
			
		}else{
			$('#load').html('验证码错误，重新生成验证码，请稍等...');
			getvc(uin,vc);
		}
	});

}
function checkvc(){
	var uin=$('#uin').val(),
		pwd=$('#pwd').val();
	$('#load').html('登录中，请稍候...');
	var checkvcurl="qq/getsid/login.php?do=checkvc";
	xiha.postData(checkvcurl,'uin='+uin+'&r='+Math.random(1), function(d) {
		if(d.saveOK ==0){
			var strs= new Array(); //定义一数组
			strs=d.data.split(",");
			if(strs[0]==0){
				pt_verifysession=strs[3];
				p=getmd5(uin,pwd,strs[2]);
			login(strs[1],pwd,p,strs[2],pt_verifysession);
			}else{
				getvc(uin,strs[2]);
			}
		}else{
			alert(d.msg);
			$('#load').html('');
		}
	});
}
$(document).ready(function(){
	$('#submit').click(function(){
		var self=$(this);
		var uin=$('#uin').val(),
			pwd=$('#pwd').val();
	if(uin==''|| pwd=='') {
		alert("请确保每项不能为空！");
		return false;
	}
		if(self.attr('do') == 'code'){
			var vcode=$('#code').val(),
				vc=$('#codeimg').attr('vc');
			dovc(uin,vcode,vc);
		}else{
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
			checkvc();
			self.attr("data-lock", "false");
		}
	});
});