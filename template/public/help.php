<?php
 /*
　*　功能简介文件
*/
if(!defined('IN_CRONLITE'))exit();
$title="功能简介";
include_once(TEMPLATE_ROOT."head.php");

navi();
?>
<div class="panel panel-primary">
<div class="panel-heading w h">
<h3 class="panel-title" align="center">为什么选择我们?</h3>
</div>
<div class="box">
<li class="list-group-item"><p class="bg-info" style="padding: 10px; font-size: 90%;">1、秒赞秒评效率高，速度快，实时秒赞。</p>
<p class="bg-danger" style="padding: 10px; font-size: 90%;">2、秒赞协议分为触屏版以及PC版，双协议秒赞，更实用，更稳定。</p>
<p class="bg-warning" style="padding: 10px; font-size: 90%;">3、本站系统采用高速VPS搭建，安全稳定</p>
<p class="bg-success" style="padding: 10px; font-size: 90%;">4、强大的任务运行机制，分布式响应功能，提升运行效率，并力求将服务器负载降到最低。</p>
<p class="bg-info" style="padding: 10px; font-size: 90%;">5、拥有QQ邮箱SID/SKEY过期提醒功能。方便实时更新，不漏赞，秒赞一直领先。</p>
</li>
</div>
<button href="#" class="btn btn-warning btn-block" data-toggle="modal" data-target="#help">本站拥有的功能</button>
</div>

<div class="modal fade" align="left" id="help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">本站功能查看</h4>
      </div>
      <div class="modal-body">
1、秒赞秒评—双协议离线秒赞秒评好友，时时刻刻关注，更显身份地位<br>
2、删发说说—自动发说说，提高访问量，还可删说说，删除剩最近十条<br>
3、挂Q签到—24小时自动为你挂Q和空间签到，免去您没时间操作的烦恼<br>
4、花藤服务—平台24小时自动为你管理花藤，提高空间等级必备的功能<br>
5、会员签到—系统自动给你签到，每天都可获得会员成长值和会员积分<br>
6、图书签到—系统自动为你的图书签到，用于某些爱好读QQ读书的用户<br>
7、转发说说—自动帮你转发指定人的说说，给接包月的用户带来便捷<br>
8、秒赞认证—专属页面可随手截图分享给网友，CQY/MZ+的有力说明！<br>
9、钱包签到—专为懒人设计的QQ钱包签到功能，轻松获取0.2活跃天数<br>
10、绿钻签到—每天自动为您签到绿钻，每天轻松无压力获得5点成长值<br>
11、互刷留言—平台内的QQ为你留言，真实度高，效率好，内容绝不统一<br>
12、主页刷赞—利用平台内的QQ给你刷主页赞，每天免费增加几百主页赞<br>
13、单项检测—揪出那些单向你的好友并且删除，为您清理死尸带来便捷<br>
14、圈圈99+—利用平台内的QQ给你拉圈圈99+，再也不用找别人帮忙拉圈圈了<br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</div>
</div>
<?php
echo'<div class="panel panel-primary">
<div class="panel-heading w h"><h3 class="panel-title">功能简介</h3></div>';
echo'<div class="panel-body box">';
echo'<b>新版特性：</b><br/>●全新界面：基于Bootstrap设计，响应式布局，电脑与智能手机两用。<br/>
●强大的任务运行机制：分布式任务调度、秒刷模式、多线程模式，提升运行效率，并力求将服务器负载降到最低。自定义运行时间,自定义使用代理,自定义代理ip及端口号,自定义POST模拟,自定义POST数据,自定义来源地址,自定义模拟浏览器,暂停网络任务<br/>
●完善的QQ管理系统：增加QQ账号管理，添加QQ任务更加快捷，一键更新失效的sid。<br/>
●丰富的QQ挂机功能：拥有说说秒赞、秒评、自动图片说说、3G挂Q、QQ机器人等挂机功能。互刷留言、互赞主页<br/>
●自动签到：包含柯林、DZ、360、115、新浪微盘、虾米音乐、文网、刀云等自动签到插件，并支持扩展<br/>
●强大的任务管理：支持批量添加、文件导入导出，支持暂停任务，任务运行状况一目了然<br/>
●更多的界面风格：可在新版界面和旧版界面自由切换，同时针对两种界面分别预置了多款不同的皮肤供你选择<br/>
●全平台支持：支持ACE、SAE等应用引擎，支持SQLite和MySQL两种数据库<br/>
●安全性保障：360网站卫士全局防SQL注入、IP禁访配置、网址屏蔽配置<br/>
</div>';
echo'<div class="panel-heading w h"><h3 class="panel-title">ＱＱ功能一览</h3></div>';
echo'<div class="panel-body box"><ul class="list-group" style="list-style:none;"><font color="#2200DD">';
echo'<li class="list-group-item">1、3GQQ、JAVA双协议挂Q</li>';
echo'<li class="list-group-item">2、触屏、PC双协议秒赞</li>';
echo'<li class="list-group-item">3、触屏、PC双协议秒评</li>';
echo'<li class="list-group-item">4、双协议自动空间签到</li>';
echo'<li class="list-group-item">5、双协议自动发表说说</li>';
echo'<li class="list-group-item">6、双协议自动删除说说</li>';
echo'<li class="list-group-item">7、双协议自动转发说说</li>';
echo'<li class="list-group-item">8、图书签到,VIP签到,花藤服务</li>';
echo'<li class="list-group-item">9、单向好友检测工具</li>';
echo'</font></ul>
</div>';
echo'<div class="panel-heading w h"><h3 class="panel-title">签到功能一览</h3></div>';
echo'<div class="panel-body box"><ul class="list-group" style="list-style:none;"><font color="#2200DD">';
echo'<li class="list-group-item">1、柯林自动签到</li>';
echo'<li class="list-group-item">2、Discuz自动签到</li>';
echo'<li class="list-group-item">3、Discuz自动打卡</li>';
echo'<li class="list-group-item">4、115网盘签到</li>';
echo'<li class="list-group-item">5、360云盘签到</li>';
echo'<li class="list-group-item">6、新浪微盘签到</li>';
echo'<li class="list-group-item">7、虾米音乐签到</li>';
echo'<li class="list-group-item">8、福利论坛签到</li>';
echo'<li class="list-group-item">9、文网自动签到</li>';
echo'</font></ul>
</div>';
echo'<div class="panel-heading w h"><h3 class="panel-title">新手帮助</h3></div>';
echo'<div class="panel-body box">';
echo'●<b>如何添加秒赞任务？</b><br/>1.注册登录系统后进入QQ管理，点击上方的“添加QQ账号”。<br/>2.添加完成后，会自动返回到QQ列表，点击你刚才添加的QQ号，即进入任务列表。<br/>3.在任务列表上方点击“添加QQ挂机任务”，然后点击“添加空间说说秒赞任务”。<br/>4.设置好运行时间段和任务系统之后点击“提交”，即可成功添加任务到本系统。<br/>5.在任务列表中可以看到任务的运行情况。<br/>6.QQ管理中可查看SID是否失效，如果提示失效请手动更新sid。<br/>';
echo'●<b>如何添加签到任务？</b><br/>1.注册登录系统后进入任务管理。<br/>2.选择一个任务系统，注意每个系统的执行频率，签到任务建议选择6～12小时的。<br/>3.在任务列表上方点击“添加任务”，然后点击“添加网站签到任务”。<br/>4.进入相应的签到模块根据提示完成任务添加。';
echo'</div>';
echo'<div class="panel-heading w h"><h3 class="panel-title">关于网络任务</h3></div>';
echo'<div class="panel-body box">';
echo'什么是网络任务?网络任务是可以一天24小时不间断执行某一特定动作的特殊程序.利用网络任务可以轻易完成很多重复的动作,例如不间断访问某网页,或者定时执行某些程序等等.
';
echo'</div></div>';

echo'<div class="panel panel-primary"><div class="panel-body box" style="text-align: center;">';
echo date("Y年m月d日 H:i:s");
include(ROOT.'includes/foot.php');
echo'</div>';
if($conf['sjyl']==1)
{$txt=file(ROOT.'includes/content/content.db');
shuffle($txt);
echo $txt[0];}
echo'</div>
</div>
</div></body></html>';
?>