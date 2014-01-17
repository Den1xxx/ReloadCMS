<?php header("Content-type: image/png");
//выводимый текст: если пусто - нафиг!
if (!empty($_GET['text'])) $text=$_GET['text']; else die();
//размер шрифта для вывода текста
if (!empty($_GET['size'])) $size=$_GET['size']; else $size=4;
//новая картинка; создаём и вычисляем размеры
$img =ImageCreate($size*3*strlen($text),$size*5);
//фон
$white = imagecolorallocate($img, 255, 255, 255);
imagecolortransparent($img, $white);
//цвет текста
$black = ImageColorAllocate ($img, 0, 0, 0);
//координаты вывода строки
$x=0; $y=0;
//пишем текст
ImageString($img, $size, $x, $y, $text, $black);
//выводим картинку
ImagePNG($img);
//освобождаем занимаемую рисунком память
imagedestroy($img);
?>