<?php 
class rms_captcha { 
function create($text){
$img = imagecreatefrompng("skins/icon.png"); 
$black = imageColorAllocate($img, 0, 0, 0);
$white = imageColorAllocate($img, 255, 255, 255); 
imageString($img, 5, 20, 3, $text ,$white);
imagePNG($img); 
} 
function text() {
 if (isset($_GET['ident'])) { 
 if (!empty($_GET['ident'])) {
 $from_md5=substr(md5($_GET['ident']),0,5); 
 } 
 } else { $from_md5=''; } 
 return ($from_md5); 
 } 
 }
header("Content-type: image/png"); 
$captcha= new rms_captcha(); 
$captcha->create($captcha->text()); 
?> 