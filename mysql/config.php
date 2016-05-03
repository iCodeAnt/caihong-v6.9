<?php
error_reporting(0);
// language pack
include "lang/index.php";

$k=trim($_GET['k']);
if($k=="") die('error');
$kz=base64_decode($k);
$kz=explode("^^^",$kz);
$host=$kz[0];
$user=$kz[1];
$pass=$kz[2];
$dbn=$kz[3];
$conn = @mysql_connect($host, $user, $pass) or die ($lang["No_connection_to_mysql"]);
$db = @mysql_select_db("$dbn") or die ($lang["ERROR_WRONG_DB_NAME"]);

?>