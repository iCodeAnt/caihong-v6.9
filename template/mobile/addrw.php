<?php
if(!defined('IN_CRONLITE'))exit();
if($_GET['my']=='add')
echo '<div class="w h">创建一个新任务</div>
<div class="box"><form action="index.php?mod=sc&my=add1&sys='.$sysid.$link.'" method="post">
名称:<font color="green">(可不填)</font><br>
<input type="text" name="mc" value=""><br>
网址:<font color="green">(必须包含且只能包含一个http://)</font><br>
<textarea name="url"></textarea><br>';
elseif($_GET['my']=='edit')
echo '<div class="w h">修改任务</div>
<div class="box"><form action="index.php?mod=sc&my=edit1&sys='.$sysid.'&jobid='.$jobid.$link.'" method="post">
名称:<font color="green">(可不填)</font><br>
<input type="text" name="mc" value="'.$row1['mc'].'"><br>
网址:<font color="green">(必须包含且只能包含一个http://)</font><br>
<textarea name="url">'.$row1['url'].'</textarea><br>';
elseif($_GET['my']=='bulk')
echo '<div class="w h">批量添加任务</div>
<div class="box"><form action="index.php?mod=sc&my=bulk1&sys='.$sysid.$link.'" method="post">
名称:<font color="green">(可不填)</font><br>
<input type="text" name="mc" value=""><br>
网址:<br><font color="green">每行一个，最多'.$conf['bulk'].'个(管理员无限制)，分别以 http:// 开头</font><br>
<textarea name="url" style="height:150px"></textarea><br>
<font color="green">结尾不要有空行，否则也算一个</font><br>';
elseif($_GET['my']=='upload')
echo '<div class="w h">从文件导入任务</div>
<div class="box">导入文本格式：<br/><font color="green">每行一个网址，最多'.$conf['bulk'].'个(管理员无限制)，分别以 http:// 开头。</font>
<form action="index.php?mod=sc&my=bulk1&sys='.$sysid.$link.'" method="post" enctype="multipart/form-data"><input type="file" name="file"/><br><br>';

echo '任务运行时段:<br/><select class="shi" name="start" ivalue="'.$row1['start'].'">
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
</select>时-<select class="shi" name="stop" ivalue="'.$row1['stop'].'">
<option value="24">24</option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
</select>时<br><font color="green">运行时间段设置<br>如:01小时-01小时(每天在02时停止)。</font>
<br>运行频率(秒/次):(<u><a href="index.php?mod=sc&my=sj">时间公式</a></u>)<br><input type="text" name="pl" value="'.$row1['pl'].'"><br><font color="green">运行频率最高无法高于本系统的运行频率(可留空)</font>';

if($_GET['my']=='add'||$_GET['my']=='edit') {
echo '<hr><font color=red>如果你什么都不懂，请不要使用以下功能！</font><br>
使用代理:<br>';
if($row1['usep']=='1'){ 
echo'<select name="usep">
<option value="1">是</option> 
<option value="0">否</option>
</select>';
}else{
echo'<select name="usep">
<option value="0">否</option>
<option value="1">是</option>
</select>';
}
echo'<br>代理ip及端口号:(<u><a href="index.php?mod=sc&my=gn">国内代理地址</a>|<a href="index.php?mod=sc&my=gw">国外代理地址</a></u>)<br><font color="green">格式:000.000.000.000:00</font><br><input type="text" name="proxy" value="'.$row1['proxy'].'"><br><font color="green">注意:不需要代理时千万不要随便填写</font><br>POST模拟:<br>';
if($row1['post']=='1'){ 
echo'<select name="post"><option value="1">开启</option><option value="0">关闭</option></select>';
}else{
echo'<select name="post"><option value="0">关闭</option><option value="1">开启</option></select>';
}
echo'<br>POST数据:<br><font color="green">格式:user=***&pass=***</font><br><input type="text" name="postfields" value="'.$row1['postfields'].'"><br><font color="green">不启用POST时此项可留空</font><br>Cookie数据:<br><font color="green">格式:token=***;pass=***;</font><br><input type="text" name="cookie" value="'.$row1['cookie'].'"><br><font color="green">不启用Cookie时此项可留空</font><br>来源地址:<br>
<input type="text" name="referer" value="'.$row1['referer'].'"><br><font color="green">不需要设置来源地址时请不要填写</font><br>模拟浏览器UA:(<u><a href="index.php?mod=sc&my=ua">常用浏览器UA</a></u>)<br>
<input type="text" name="useragent" value="'.$row1['useragent'].'">
<br><font color="green">不需要模拟浏览器时请不要填写</font><br>';
}
coin_display(2);
echo '<input type="submit"
value="提交"></form></div><div class="box">PS:监控任务不能运行请加谷歌代理（仅限国外空间）<a>http://www.google.com/gwt/x?u=监控网址</a></div> ';
echo'<a href="index.php?mod=list&sys='.$sysid.$link.'">>>返回我的任务列表</a><br/>';
?>