<?php header("Content-type: image/png");
//��������� �����: ���� ����� - �����!
if (!empty($_GET['text'])) $text=$_GET['text']; else die();
//������ ������ ��� ������ ������
if (!empty($_GET['size'])) $size=$_GET['size']; else $size=4;
//����� ��������; ������ � ��������� �������
$img =ImageCreate($size*3*strlen($text),$size*5);
//���
$white = imagecolorallocate($img, 255, 255, 255);
imagecolortransparent($img, $white);
//���� ������
$gray = ImageColorAllocate ($img, 100, 100, 100);
//���������� ������ ������
$x=0; $y=0;
//����� �����
ImageString($img, $size, $x, $y, $text, $gray);
//������� ��������
ImagePNG($img);
//����������� ���������� �������� ������
imagedestroy($img);
?>