<?php
/*
* wap phpmyadmin 
* ionutvmi
* pimp-wap.net
*/
include 'config.php';
include 'head.php';

 
$tb=trim($_GET['tb']);
$prim=$_GET['pri'];
$pri=base64_decode($prim);

$vmi=$_GET['vmi'];
echo "<div class='shout'>".$lang["INFO"]."<p align='left'><a href='tables.php?k=$k'>".$lang["tables"]."</a>><a href='table.php?k=$k&tb=$tb'>$tb</a><br/>- - -<br/>";
$result = mysql_query("SELECT * FROM `$tb`");
if (!$result) {
printf($lang["table_not_exists"],$tb); } else {
if (!$vmi) {
echo "<form action='?k=$k&tb=$tb&pri=$prim&vmi=ionutvmi' align='left' method='post'>";
$de = mysql_query("SHOW COLUMNS FROM `$tb`");
$nr=@mysql_num_rows($de);
if ($nr >0) {
$i = 0;
while ($row = mysql_fetch_array($de)) {
$meta = mysql_fetch_field($result, $i);
$nm=$meta->name;
$ga = mysql_query("SELECT `$nm` FROM `$tb` WHERE $pri");
$r = mysql_fetch_array($ga);
$rr=$r["$nm"];
echo "&#187; $nm: <textarea rows='1' name='$nm'>".$rr."</textarea><br/>";
echo "<br/> ";
$i++;
}
echo "<input type='submit' value='".$lang["Save"]."'></form>";
} else {
echo $lang["No_values_inserted"];
}
} else {
$de = mysql_query("SHOW COLUMNS FROM `$tb`");
$updt="";
$i=0;
while ($row = mysql_fetch_array($de)) {
$meta = mysql_fetch_field($result, $i);
$nm=$meta->name; // column name
$ga = mysql_query("SELECT `$nm` FROM `$tb` WHERE $pri");
$r = mysql_fetch_array($ga);
$rr=$r["$nm"]; // original value
$nv =mysql_real_escape_string(trim($_POST["$nm"])); // new value
// check if is anything to change 
$nvv=stripslashes($nv);
if($rr != $nvv){
$updt.= " `$nm`='$nv',";
}
$i++;
}
if($updt !="")	{
$updt=substr($updt,"0","-1");
$dosql = "UPDATE `$tb` SET $updt WHERE $pri  LIMIT 1";
echo "<i>".htmlspecialchars($dosql)."</i><br/>";
$do = mysql_query($dosql);
if (!$do) 
echo mysql_error(); 
else
echo $lang["saved"]." !";
		} else {
				echo $lang["Nothing_to_change"];
				
				}
}
}
echo "</div>";
include('foot.php');
?>
