<?php
/*
* wap phpmyadmin 
* ionutvmi
* pimp-wap.net
*/
include 'config.php';
include 'head.php';


$tb=trim($_GET['tb']);
$cl=trim($_GET['cl']);
$vmi=$_GET['vmi'];
echo "<div class='shout'><b>".$lang["MODIFY_COLUMN"]."</b><br/>$cl<p align='left'><a href='tables.php?k=$k'>".$lang["tables"]."</a>><a href='table.php?k=$k&tb=$tb'>$tb</a>";
if (!$vmi) {
echo "><a href='col.php?k=$k&tb=$tb&cl=$cl'>$cl</a>><a href='?k=$k&tb=$tb&cl=$cl'>".$lang["edit_column"]."</a><br/>- - -<br/>";
echo "
<form action='?k=$k&vmi=ionutvmi&tb=$tb&cl=$cl' method='post' align='left'>
".$lang["colunm_name"].": <input name='tc' value='$cl'><br/> ".$lang["type"].": ";
/// get column details
$xx=mysql_query("SHOW COLUMNS FROM `$tb`");
while ($r = mysql_fetch_array($xx)){
if ($r["Field"] == $cl){
$t=$r["Type"];
$t=explode("(",$t);
$type = $t[0];
$l=explode(")",$t[1]);
$lh=$l[0];
$uns=$l[1];
$null=0;
if ($r["Null"] == "NO") $null=1;
$avmi=$r["Default"];
if ($avmi != "") $df=$avmi;
echo $r["Extra"];
if($r["Extra"] == "auto_increment") $zx="SELECTED";
}
}
print ' <select name="ct">';
	$qq=	array("VARCHAR","TINYINT","TEXT","DATE","SMALLINT","MEDIUMINT","INT","BIGINT","FLOAT","DOUBLE","DECIMAL","DATETIME","TIMESTAMP","TIME","YEAR","CHAR","TINYBLOB","TINYTEXT","BLOB","MEDIUMBLOB","MEDIUMTEXT","LONGBLOB","LONGTEXT","ENUM","SET","BOOL","BINARY","VARBINARY");
		foreach($qq as $q){
		if($type == strtolower($q))
		print "<option value='$q' SELECTED>$q</option>";
		else
		print "<option value='$q'>$q</option>";
		}
print "</select><br/>
".$lang["Length"].": <input name='lg' value='$lh'><br/>";
print $lang["Collation"].':<select name="co"> <option value="">&nbsp;</option>
    <optgroup label="armscii8" title="ARMSCII-8 Armenian">
        <option value="armscii8_bin" title="Armenian, Binary">armscii8_bin</option>
        <option value="armscii8_general_ci" title="Armenian, case-insensitive">armscii8_general_ci</option>
    </optgroup>
    <optgroup label="ascii" title="US ASCII">
        <option value="ascii_bin" title="West European (multilingual), Binary">ascii_bin</option>
        <option value="ascii_general_ci" title="West European (multilingual), case-insensitive">ascii_general_ci</option>
    </optgroup>
    <optgroup label="big5" title="Big5 Traditional Chinese">
        <option value="big5_bin" title="Traditional Chinese, Binary">big5_bin</option>
        <option value="big5_chinese_ci" title="Traditional Chinese, case-insensitive">big5_chinese_ci</option>
    </optgroup>
    <optgroup label="binary" title="Binary pseudo charset">
        <option value="binary" title="Binary">binary</option>
    </optgroup>
    <optgroup label="cp1250" title="Windows Central European">
        <option value="cp1250_bin" title="Central European (multilingual), Binary">cp1250_bin</option>
        <option value="cp1250_croatian_ci" title="Croatian, case-insensitive">cp1250_croatian_ci</option>
        <option value="cp1250_czech_cs" title="Czech, case-sensitive">cp1250_czech_cs</option>
        <option value="cp1250_general_ci" title="Central European (multilingual), case-insensitive">cp1250_general_ci</option>
        <option value="cp1250_polish_ci" title="Polish, case-insensitive">cp1250_polish_ci</option>
    </optgroup>
    <optgroup label="cp1251" title="Windows Cyrillic">
        <option value="cp1251_bin" title="Cyrillic (multilingual), Binary">cp1251_bin</option>
        <option value="cp1251_bulgarian_ci" title="Bulgarian, case-insensitive">cp1251_bulgarian_ci</option>
        <option value="cp1251_general_ci" title="Cyrillic (multilingual), case-insensitive">cp1251_general_ci</option>
        <option value="cp1251_general_cs" title="Cyrillic (multilingual), case-sensitive">cp1251_general_cs</option>
        <option value="cp1251_ukrainian_ci" title="Ukrainian, case-insensitive">cp1251_ukrainian_ci</option>
    </optgroup>
    <optgroup label="cp1256" title="Windows Arabic">
        <option value="cp1256_bin" title="Arabic, Binary">cp1256_bin</option>
        <option value="cp1256_general_ci" title="Arabic, case-insensitive">cp1256_general_ci</option>
    </optgroup>
    <optgroup label="cp1257" title="Windows Baltic">
        <option value="cp1257_bin" title="Baltic (multilingual), Binary">cp1257_bin</option>
        <option value="cp1257_general_ci" title="Baltic (multilingual), case-insensitive">cp1257_general_ci</option>
        <option value="cp1257_lithuanian_ci" title="Lithuanian, case-insensitive">cp1257_lithuanian_ci</option>
    </optgroup>
    <optgroup label="cp850" title="DOS West European">
        <option value="cp850_bin" title="West European (multilingual), Binary">cp850_bin</option>
        <option value="cp850_general_ci" title="West European (multilingual), case-insensitive">cp850_general_ci</option>
    </optgroup>
    <optgroup label="cp852" title="DOS Central European">
        <option value="cp852_bin" title="Central European (multilingual), Binary">cp852_bin</option>
        <option value="cp852_general_ci" title="Central European (multilingual), case-insensitive">cp852_general_ci</option>
    </optgroup>
    <optgroup label="cp866" title="DOS Russian">
        <option value="cp866_bin" title="Russian, Binary">cp866_bin</option>
        <option value="cp866_general_ci" title="Russian, case-insensitive">cp866_general_ci</option>
    </optgroup>
    <optgroup label="cp932" title="SJIS for Windows Japanese">
        <option value="cp932_bin" title="Japanese, Binary">cp932_bin</option>
        <option value="cp932_japanese_ci" title="Japanese, case-insensitive">cp932_japanese_ci</option>
    </optgroup>
    <optgroup label="dec8" title="DEC West European">
        <option value="dec8_bin" title="West European (multilingual), Binary">dec8_bin</option>
        <option value="dec8_swedish_ci" title="Swedish, case-insensitive">dec8_swedish_ci</option>
    </optgroup>
    <optgroup label="eucjpms" title="UJIS for Windows Japanese">
        <option value="eucjpms_bin" title="Japanese, Binary">eucjpms_bin</option>
        <option value="eucjpms_japanese_ci" title="Japanese, case-insensitive">eucjpms_japanese_ci</option>
    </optgroup>
    <optgroup label="euckr" title="EUC-KR Korean">
        <option value="euckr_bin" title="Korean, Binary">euckr_bin</option>
        <option value="euckr_korean_ci" title="Korean, case-insensitive">euckr_korean_ci</option>
    </optgroup>
    <optgroup label="gb2312" title="GB2312 Simplified Chinese">
        <option value="gb2312_bin" title="Simplified Chinese, Binary">gb2312_bin</option>
        <option value="gb2312_chinese_ci" title="Simplified Chinese, case-insensitive">gb2312_chinese_ci</option>
    </optgroup>
    <optgroup label="gbk" title="GBK Simplified Chinese">
        <option value="gbk_bin" title="Simplified Chinese, Binary">gbk_bin</option>
        <option value="gbk_chinese_ci" title="Simplified Chinese, case-insensitive">gbk_chinese_ci</option>
    </optgroup>
    <optgroup label="geostd8" title="GEOSTD8 Georgian">
        <option value="geostd8_bin" title="Georgian, Binary">geostd8_bin</option>
        <option value="geostd8_general_ci" title="Georgian, case-insensitive">geostd8_general_ci</option>
    </optgroup>
    <optgroup label="greek" title="ISO 8859-7 Greek">
        <option value="greek_bin" title="Greek, Binary">greek_bin</option>
        <option value="greek_general_ci" title="Greek, case-insensitive">greek_general_ci</option>
    </optgroup>
    <optgroup label="hebrew" title="ISO 8859-8 Hebrew">
        <option value="hebrew_bin" title="Hebrew, Binary">hebrew_bin</option>
        <option value="hebrew_general_ci" title="Hebrew, case-insensitive">hebrew_general_ci</option>
    </optgroup>
    <optgroup label="hp8" title="HP West European">
        <option value="hp8_bin" title="West European (multilingual), Binary">hp8_bin</option>
        <option value="hp8_english_ci" title="English, case-insensitive">hp8_english_ci</option>
    </optgroup>
    <optgroup label="keybcs2" title="DOS Kamenicky Czech-Slovak">
        <option value="keybcs2_bin" title="Czech-Slovak, Binary">keybcs2_bin</option>
        <option value="keybcs2_general_ci" title="Czech-Slovak, case-insensitive">keybcs2_general_ci</option>
    </optgroup>
    <optgroup label="koi8r" title="KOI8-R Relcom Russian">
        <option value="koi8r_bin" title="Russian, Binary">koi8r_bin</option>
        <option value="koi8r_general_ci" title="Russian, case-insensitive">koi8r_general_ci</option>
    </optgroup>
    <optgroup label="koi8u" title="KOI8-U Ukrainian">
        <option value="koi8u_bin" title="Ukrainian, Binary">koi8u_bin</option>
        <option value="koi8u_general_ci" title="Ukrainian, case-insensitive">koi8u_general_ci</option>
    </optgroup>
    <optgroup label="latin1" title="cp1252 West European">
        <option value="latin1_bin" title="West European (multilingual), Binary">latin1_bin</option>
        <option value="latin1_danish_ci" title="Danish, case-insensitive">latin1_danish_ci</option>
        <option value="latin1_general_ci" title="West European (multilingual), case-insensitive">latin1_general_ci</option>
        <option value="latin1_general_cs" title="West European (multilingual), case-sensitive">latin1_general_cs</option>
        <option value="latin1_german1_ci" title="German (dictionary), case-insensitive">latin1_german1_ci</option>
        <option value="latin1_german2_ci" title="German (phone book), case-insensitive">latin1_german2_ci</option>
        <option value="latin1_spanish_ci" title="Spanish, case-insensitive">latin1_spanish_ci</option>
        <option value="latin1_swedish_ci" title="Swedish, case-insensitive">latin1_swedish_ci</option>
    </optgroup>
    <optgroup label="latin2" title="ISO 8859-2 Central European">
        <option value="latin2_bin" title="Central European (multilingual), Binary">latin2_bin</option>
        <option value="latin2_croatian_ci" title="Croatian, case-insensitive">latin2_croatian_ci</option>
        <option value="latin2_czech_cs" title="Czech, case-sensitive">latin2_czech_cs</option>
        <option value="latin2_general_ci" title="Central European (multilingual), case-insensitive">latin2_general_ci</option>
        <option value="latin2_hungarian_ci" title="Hungarian, case-insensitive">latin2_hungarian_ci</option>
    </optgroup>
    <optgroup label="latin5" title="ISO 8859-9 Turkish">
        <option value="latin5_bin" title="Turkish, Binary">latin5_bin</option>
        <option value="latin5_turkish_ci" title="Turkish, case-insensitive">latin5_turkish_ci</option>
    </optgroup>
    <optgroup label="latin7" title="ISO 8859-13 Baltic">
        <option value="latin7_bin" title="Baltic (multilingual), Binary">latin7_bin</option>
        <option value="latin7_estonian_cs" title="Estonian, case-sensitive">latin7_estonian_cs</option>
        <option value="latin7_general_ci" title="Baltic (multilingual), case-insensitive">latin7_general_ci</option>
        <option value="latin7_general_cs" title="Baltic (multilingual), case-sensitive">latin7_general_cs</option>
    </optgroup>
    <optgroup label="macce" title="Mac Central European">
        <option value="macce_bin" title="Central European (multilingual), Binary">macce_bin</option>
        <option value="macce_general_ci" title="Central European (multilingual), case-insensitive">macce_general_ci</option>
    </optgroup>
    <optgroup label="macroman" title="Mac West European">
        <option value="macroman_bin" title="West European (multilingual), Binary">macroman_bin</option>
        <option value="macroman_general_ci" title="West European (multilingual), case-insensitive">macroman_general_ci</option>
    </optgroup>
    <optgroup label="sjis" title="Shift-JIS Japanese">
        <option value="sjis_bin" title="Japanese, Binary">sjis_bin</option>
        <option value="sjis_japanese_ci" title="Japanese, case-insensitive">sjis_japanese_ci</option>
    </optgroup>
    <optgroup label="swe7" title="7bit Swedish">
        <option value="swe7_bin" title="Swedish, Binary">swe7_bin</option>
        <option value="swe7_swedish_ci" title="Swedish, case-insensitive">swe7_swedish_ci</option>
    </optgroup>
    <optgroup label="tis620" title="TIS620 Thai">
        <option value="tis620_bin" title="Thai, Binary">tis620_bin</option>
        <option value="tis620_thai_ci" title="Thai, case-insensitive">tis620_thai_ci</option>
    </optgroup>
    <optgroup label="ucs2" title="UCS-2 Unicode">
        <option value="ucs2_bin" title="Unicode (multilingual), Binary">ucs2_bin</option>
        <option value="ucs2_czech_ci" title="Czech, case-insensitive">ucs2_czech_ci</option>
        <option value="ucs2_danish_ci" title="Danish, case-insensitive">ucs2_danish_ci</option>
        <option value="ucs2_esperanto_ci" title="Esperanto, case-insensitive">ucs2_esperanto_ci</option>
        <option value="ucs2_estonian_ci" title="Estonian, case-insensitive">ucs2_estonian_ci</option>
        <option value="ucs2_general_ci" title="Unicode (multilingual), case-insensitive">ucs2_general_ci</option>
        <option value="ucs2_hungarian_ci" title="Hungarian, case-insensitive">ucs2_hungarian_ci</option>
        <option value="ucs2_icelandic_ci" title="Icelandic, case-insensitive">ucs2_icelandic_ci</option>
        <option value="ucs2_latvian_ci" title="Latvian, case-insensitive">ucs2_latvian_ci</option>
        <option value="ucs2_lithuanian_ci" title="Lithuanian, case-insensitive">ucs2_lithuanian_ci</option>
        <option value="ucs2_persian_ci" title="Persian, case-insensitive">ucs2_persian_ci</option>
        <option value="ucs2_polish_ci" title="Polish, case-insensitive">ucs2_polish_ci</option>
        <option value="ucs2_roman_ci" title="West European, case-insensitive">ucs2_roman_ci</option>
        <option value="ucs2_romanian_ci" title="Romanian, case-insensitive">ucs2_romanian_ci</option>
        <option value="ucs2_slovak_ci" title="Slovak, case-insensitive">ucs2_slovak_ci</option>
        <option value="ucs2_slovenian_ci" title="Slovenian, case-insensitive">ucs2_slovenian_ci</option>
        <option value="ucs2_spanish2_ci" title="Traditional Spanish, case-insensitive">ucs2_spanish2_ci</option>
        <option value="ucs2_spanish_ci" title="Spanish, case-insensitive">ucs2_spanish_ci</option>
        <option value="ucs2_swedish_ci" title="Swedish, case-insensitive">ucs2_swedish_ci</option>
        <option value="ucs2_turkish_ci" title="Turkish, case-insensitive">ucs2_turkish_ci</option>
        <option value="ucs2_unicode_ci" title="Unicode (multilingual), case-insensitive">ucs2_unicode_ci</option>
    </optgroup>
    <optgroup label="ujis" title="EUC-JP Japanese">
        <option value="ujis_bin" title="Japanese, Binary">ujis_bin</option>
        <option value="ujis_japanese_ci" title="Japanese, case-insensitive">ujis_japanese_ci</option>
    </optgroup>
    <optgroup label="utf8" title="UTF-8 Unicode">
        <option value="utf8_bin" title="Unicode (multilingual), Binary">utf8_bin</option>
        <option value="utf8_czech_ci" title="Czech, case-insensitive">utf8_czech_ci</option>
        <option value="utf8_danish_ci" title="Danish, case-insensitive">utf8_danish_ci</option>
        <option value="utf8_esperanto_ci" title="Esperanto, case-insensitive">utf8_esperanto_ci</option>
        <option value="utf8_estonian_ci" title="Estonian, case-insensitive">utf8_estonian_ci</option>
        <option value="utf8_general_ci" title="Unicode (multilingual), case-insensitive">utf8_general_ci</option>
        <option value="utf8_hungarian_ci" title="Hungarian, case-insensitive">utf8_hungarian_ci</option>
        <option value="utf8_icelandic_ci" title="Icelandic, case-insensitive">utf8_icelandic_ci</option>
        <option value="utf8_latvian_ci" title="Latvian, case-insensitive">utf8_latvian_ci</option>
        <option value="utf8_lithuanian_ci" title="Lithuanian, case-insensitive">utf8_lithuanian_ci</option>
        <option value="utf8_persian_ci" title="Persian, case-insensitive">utf8_persian_ci</option>
        <option value="utf8_polish_ci" title="Polish, case-insensitive">utf8_polish_ci</option>
        <option value="utf8_roman_ci" title="West European, case-insensitive">utf8_roman_ci</option>
        <option value="utf8_romanian_ci" title="Romanian, case-insensitive">utf8_romanian_ci</option>
        <option value="utf8_slovak_ci" title="Slovak, case-insensitive">utf8_slovak_ci</option>
        <option value="utf8_slovenian_ci" title="Slovenian, case-insensitive">utf8_slovenian_ci</option>
        <option value="utf8_spanish2_ci" title="Traditional Spanish, case-insensitive">utf8_spanish2_ci</option>
        <option value="utf8_spanish_ci" title="Spanish, case-insensitive">utf8_spanish_ci</option>
        <option value="utf8_swedish_ci" title="Swedish, case-insensitive">utf8_swedish_ci</option>
        <option value="utf8_turkish_ci" title="Turkish, case-insensitive">utf8_turkish_ci</option>
        <option value="utf8_unicode_ci" title="Unicode (multilingual), case-insensitive">utf8_unicode_ci</option>
    </optgroup>
</select><br/>'.$lang["Attributes"].': <select name="at"> <option value="" selected="selected"></option>';
             $u=array("UNSIGNED","UNSIGNED ZEROFILL","ON UPDATE CURRENT_TIMESTAMP");
			 foreach ($u as $uu){
			if (trim($uns) == strtolower($uu)) 
			print "<option value='$uu' SELECTED>$uu</option>";
			else
			print "<option value='$uu'>$uu</option>";
			 }
print '</select><br/>NULL <select name="nu">';
if($null == 1){
print '<option value="NOT NULL" selected="selected">not null</option>
    <option value="">null</option>'; } else {
	print '<option value="NOT NULL">not null</option>
    <option value="" SELECTED>null</option>';
	}

print '</select><br/>'.$lang["Default"].': <input name="df" value="'.$df.'"><br/>'.$lang["Extra"].': <select name="ex">';

print '<option value="">&nbsp;</option>
<option value="AUTO_INCREMENT"'.$zx.'>auto_increment</option>';


print '</select><br/>';
print "<input type='submit' value='".$lang["Save"]."'></form>";
} else {
$tc=trim($_POST['tc']);
$ct=trim($_POST['ct']);
$lg=trim($_POST['lg']);
$co=trim($_POST['co']);
$at=trim($_POST['at']);
$nu=trim($_POST['nu']);
$df=trim($_POST['df']);
$ex=trim($_POST['ex']);
if($lg !="") $lg="($lg)";
$create=mysql_query("ALTER TABLE `$tb` CHANGE `$cl` `$tc` $ct $lg $co $at $nu $df $ex");
if ($create) {
echo "><a href='col.php?k=$k&tb=$tb&cl=$tc'>$tc</a>><a href='?k=$k&tb=$tb&cl=$tc'>".$lang["edit_column"]."</a><br/>- - -<br/>";
printf($lang["Column_saved"],$tc);
} else {
echo "><a href='col.php?k=$k&tb=$tb&cl=$cl'>$cl</a>><a href='?k=$k&tb=$tb&cl=$cl'>".$lang["edit_column"]."</a><br/>- - -<br/>".$lang["Error"].": ";
echo mysql_error();
}
}
echo '</div>';
include('foot.php');
?>
