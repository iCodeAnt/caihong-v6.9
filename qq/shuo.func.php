<?php
function randstr() {
$str=@file_get_contents('ss.txt');
$str=explode("\n",$str);
$content=$str[array_rand($str,1)];
/*$str = @file_get_contents('sji.db');
$a = explode('|',$str);
$content=$a[array_rand($a,1)];*/
return $content;
}
function randimg() {
$row=@file_get_contents('pic.txt');
$row=explode("\r\n",$row);
shuffle($row);
$pic=$row[0];
$pic=trim($pic);
return $pic;
}
function jokei_img() {
$str=curl_get('http://jokei.aliapp.com/m/api.php?key=hu60&act=img');
$str=json_decode($str,true);
return $str['url'];
}
function jokei_txt() {
$str=curl_get('http://jokei.aliapp.com/m/api.php?key=hu60&act=txt');
$str=json_decode($str,true);
if(!$str)return randstr();
$txt=str_replace('[br]',"\r\n",$str['txt']);
$title=$str['title'];
$str='《'.$title.'》'.$txt;
return $str;
}

function budejie() {
$pipeinum = rand(0,19);
$ch=curl_init('http://www.budejie.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$tu=curl_exec($ch);
curl_close($ch);
preg_match_all('/<img src=\"(.*)\" id=\"(.*)\" alt=\"(.*)\">/',$tu,$p);
$imgurl =$p[1][$pipeinum];
return $imgurl;
}

?>