<?php
/*
* wap phpmyadmin
* ionutvmi
* pimp-wap.net
*/
include 'config.php';
include 'head.php';
$tb=trim($_GET['tb']);
$vmi=$_GET['vmi'];
$sql="SELECT * FROM `$tb`";
echo "<div class='shout'>".strtoupper($lang["Insert"])."<br/>".$lang["Query"].": <i>".htmlspecialchars($sql)."</i><p align='left'><a href='tables.php?k=$k'>".$lang["tables"]."</a>><a href='table.php?k=$k&tb=$tb'>$tb</a><br/>- - -<br/><a href='br.php?k=$k&tb=$tb'>".$lang["Browse"]."</a> |<a href='table.php?k=$k&tb=$tb'> ".$lang["Structure"]." </a> | <a href='search.php?k=$k&tb=$tb'>".$lang["Search"]."</a> | ".$lang["Insert"]." | <a href='trun.php?k=$k&tb=$tb'>".$lang["Clear"]."</a> | <a href='dropt.php?k=$k&tb=$tb'>".$lang["Drop"]."</a><br/>- - -<br/>
";
$result = mysql_query($sql);
if (!$result) {
printf($lang["table_not_exists"],$tb);
} else {
if (!$vmi) {
echo "<form action='?k=$k&tb=$tb&vmi=ionutvmi' align='left' method='post'>
";
$i = 0;
while ($i < mysql_num_fields($result)) {
$meta = mysql_fetch_field($result, $i);
echo "<b>".htmlspecialchars($meta->name)."</b>";
echo ": <input name='$meta->name'><br/>
";
$i++;
}
echo "<input type='submit' value='".$lang["Insert"]."'></form>";
mysql_free_result($result);
} else {
$i = 0; $wh=""; $val="";
$nrf= mysql_num_fields($result) - 1;
while ($i < mysql_num_fields($result)) {
$meta = mysql_fetch_field($result, $i);
$t=$_POST[$meta->name];
if($i == $nrf) {
$wh.="`$meta->name`";
$val.="'$t'"; } else {
$wh.="`$meta->name`,";
$val.="'$t',"; }
$i++;
}
$do = mysql_query("INSERT INTO `$tb` ($wh) VALUES ($val)");
$sqll="INSERT INTO `$tb` ($wh) VALUES ($val)";
if($do)
echo "<center>".$lang["Query"].": <i>".htmlspecialchars($sqll)."</i></center><p align='left'><br/><br/>".$lang["Done"]." !";
else
echo "<center>".$lang["Query"].": <i>".htmlspecialchars($sqll)."</i></center><p align='left'><br/>".$lang["Error"].": ".mysql_error();
} }
echo "</div>";
include('foot.php');
?>