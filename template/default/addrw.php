<?php
if(!defined('IN_CRONLITE'))exit();
echo'<a href="'.$_SERVER['HTTP_REFERER'].'"><< 返回我的任务列表</a><br/>';
if($_GET['my']=='add')
echo '<h3>创建一个新任务</h3>
<form action="index.php?mod=sc&my=add1&sys='.$sysid.$link.'" method="post">
<div class="form-group">
<label>名称:</label><font color="green">(可不填)</font><br/>
<input type="text" class="form-control" name="mc" value="">
</div>
<div class="form-group">
<label>网址:</label><font color="green">(必须包含且只能包含一个http://)</font><br/>
<textarea class="form-control" name="url" rows="3"></textarea>
</div>';
elseif($_GET['my']=='edit')
echo '<h3>修改任务</h3>
<form action="index.php?mod=sc&my=edit1&sys='.$sysid.'&jobid='.$jobid.$link.'" method="post">
<div class="form-group">
<label>名称:</label><font color="green">(可不填)</font><br>
<input type="text" class="form-control" name="mc" value="'.$row1['mc'].'">
</div>
<div class="form-group">
<label>网址:</label><font color="green">(必须包含且只能包含一个http://)</font><br>
<textarea class="form-control" name="url" rows="3">'.$row1['url'].'</textarea>
</div>';
elseif($_GET['my']=='bulk')
echo '<h3>批量添加任务</h3>
<form action="index.php?mod=sc&my=bulk1&sys='.$sysid.$link.'" method="post">
<div class="form-group">
<label>名称:</label><font color="green">(可不填)</font><br>
<input type="text" class="form-control" name="mc" value="">
</div>
<div class="form-group">
<label>网址:</label><br><font color="green">每行一个，最多'.$conf['bulk'].'个(管理员无限制)，分别以 http:// 开头</font><br>
<textarea name="url" class="form-control" rows="6"></textarea><br><font color="green">结尾不要有空行，否则也算一个</font></div>';
elseif($_GET['my']=='upload')
echo '<h3>从文件导入任务</h3>
<label>导入文本格式：</label><br/><font color="green">每行一个网址，最多'.$conf['bulk'].'个(管理员无限制)，分别以 http:// 开头。</font><form action="index.php?mod=sc&my=bulk1&sys='.$sysid.$link.'" method="post" enctype="multipart/form-data">
<div class="form-group"><input type="file" class="form-control" name="file"/></div>';

echo '<div class="form-group">
<label>任务运行时段:</label><br/>
<select class="form-control" style="width:40%;display:inline" name="start" ivalue="'.$row1['start'].'">
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
</select>&nbsp;时-&nbsp;<select class="form-control" style="width:40%;display:inline" name="stop" ivalue="'.$row1['stop'].'">
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
</select>&nbsp;时<br><font color="green">运行时间段设置<br>如:01小时-01小时(每天在02时停止)。</font>
</div>
<div class="form-group">
<label>运行频率(秒/次):</label>(<u><a href="index.php?mod=sc&my=sj">时间公式</a></u>)<br>
<input type="text" class="form-control" name="pl" value="'.$row1['pl'].'"><font color="green">运行频率最高无法高于本系统的运行频率(可留空)</font>
</div>';

if($_GET['my']=='add'||$_GET['my']=='edit') {
echo '<a id="openadvance" onclick=\'$("#advance").toggle();$("#openadvance").hide();\' class="btn btn-default btn-block">显示高级功能</a>
<div id="advance" style="display:none;">
<a id="closeadvance" onclick=\'$("#advance").toggle();$("#openadvance").show();\' class="btn btn-default btn-block">隐藏高级功能</a>
<font color=red>如果你什么都不懂，请不要使用以下功能！</font><br>
<div class="form-group">
<label>使用代理:</label><br>';
if($row1['usep']=='1'){ 
echo'<select class="form-control" name="usep">
<option value="1">是</option> 
<option value="0">否</option>
</select></div>';
}else{
echo'<select class="form-control" name="usep">
<option value="0">否</option>
<option value="1">是</option>
</select></div>';
}
echo'<div class="form-group">
<label>代理ip及端口号:</label>(<u><a href="index.php?mod=sc&my=gn">国内代理地址</a>|<a href="index.php?mod=sc&my=gw">国外代理地址</a></u>)<br><font color="green">格式:000.000.000.000:00</font><br><input type="text" class="form-control" name="proxy" value="'.$row1['proxy'].'"><font color="green">注意:不需要代理时千万不要随便填写</font></div>
<div class="form-group"><label>POST模拟:</label><br>';
if($row1['post']=='1'){ 
echo'<select class="form-control" name="post"><option value="1">开启</option><option value="0">关闭</option></select></div>';
}else{
echo'<select class="form-control" name="post"><option value="0">关闭</option><option value="1">开启</option></select></div>';
}
echo'<div class="form-group">
<label>POST数据:</label><br>
<font color="green">格式:user=***&pass=***</font><br>
<input type="text" class="form-control" name="postfields" value="'.$row1['postfields'].'"><font color="green">不启用POST时此项可留空</font></div>
<div class="form-group">
<label>Cookie数据:</label><br>
<font color="green">格式:token=***;pass=***;</font><br>
<input type="text" class="form-control" name="cookie" value="'.$row1['cookie'].'"><font color="green">不启用Cookie时此项可留空</font></div>
<div class="form-group"><label>来源地址:</label><br>
<input type="text" class="form-control" name="referer" value="'.$row1['referer'].'"><font color="green">不需要设置来源地址时请不要填写</font></div>
<div class="form-group"><label>模拟浏览器UA:</label>(<u><a href="index.php?mod=sc&my=ua">常用浏览器UA</a></u>)<br>
<input type="text" class="form-control" name="useragent" value="'.$row1['useragent'].'">
<font color="green">不需要模拟浏览器时请不要填写</font></div>
</div><br/>';
}
coin_display(2);
echo '<button type="submit" class="btn btn-primary btn-block">提交</button><br/>
</form><div class="well">PS:监控任务不能运行请加谷歌代理（仅限国外空间）<a>http://www.google.com/gwt/x?u=监控网址</a></div>';
?>