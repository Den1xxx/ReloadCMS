<?php 

//Preparations
define('RCMS_ROOT_PATH', './');
require_once(RCMS_ROOT_PATH . 'common.php');
global $lightbox_config,$system;
$folder=FILES_PATH;
$user = $system->user['username'];
$gd_formats = '';
if(function_exists('imagegif')) $gd_formats .= 'gif ';
if(function_exists('imagejpeg')) $gd_formats .= 'jpg jpeg jpe ';
if(function_exists('imagepng')) $gd_formats .= 'png ';

//Main process

	//Пользователь имеет право на загрузку?
	if (!user_can_upload_images() OR $user=='_thumb') {
	echo __('Access denied');
	return false;
	} 
	//Загружен файл?
	if (empty($_FILES['uploadfile'])) {
	echo __('No file'); 
	return false;
	}
	//Это картинка?
	if (!$size=getimagesize($_FILES['uploadfile']['tmp_name'])) {
	echo __('Your file is not an image or is corrupted') . ' (' . $_FILES['uploadfile']['name'] . ')';
	return false;
	} 
	//Сохраним данные изображения
	$width=$size[0];
	$height=$size[1];
	switch ($size[2]) {
		case 1:
		$image_type='gif';
		break;
		case 2:
		$image_type='jpg';
		break;
		case 3:
		$image_type='png';
		break;
		default:
		//Какая-то другая неподдерживаемая фигня типа swf или ещё что%)
		echo __('Your file is not an image or is corrupted') . ' (' . $_FILES['uploadfile']['name'] . ')';
		return false;		
		break;
	}
	//Делать имя файла уникальным?
	$u=empty($lightbox_config['unique'])?'':uniqid('').'_';
	//Включена опция распределения по подпапкам?
	if (!empty($lightbox_config['distribute_enable'])) {
		//Сообразно настройкам, добавим к $folder подпапки User/Year/Month
		if (!empty($lightbox_config['folders'])) {
				if(empty($user)) $user='.undefined!';
			switch ($lightbox_config['folders']) {
				case 'user':
				$folder.=$user.'/';
				break;
				case 'user_year':
				$folder.=$user.'/'.rcms_format_time('Y', rcms_get_time()).'/';
				break;
				case 'user_year_month':
				$folder.=$user.'/'.rcms_format_time('Y/m', rcms_get_time()).'/';
				break;
				case 'year':
				$folder.=rcms_format_time('Y', rcms_get_time()).'/';
				break;
				case 'year_month':
				$folder.=rcms_format_time('Y/m', rcms_get_time()).'/';
				break;
				default:
				break;
			}
		}
		if (!is_dir($folder)) rcms_mkdir($folder);
	}
	//Напоследок вырежем небезопасные символы и заменим русские буквы и пробелы в названии картинки на транслит
	$filename =  $folder.$u.make_safe_filename($_FILES['uploadfile']['name']);
	
	//Включено управление контроля картинок?
	if (!empty($lightbox_config['change_enable'])) {
	//Размер правильный?
	if (!empty($lightbox_config['max_size'])){
	if (($lightbox_config['max_size']*1000000) < filesize($_FILES['uploadfile']['tmp_name'])) {
	echo __('Error').'! '.__('Max size') . ': '.$lightbox_config['max_size'].' Mb';
	return false;
	}
	}
	//Ширина?
	if (!empty($lightbox_config['max_width'])&&($lightbox_config['max_width']<$size[0])) {
	echo __('Error').'! '.__('Maximum width').': '.$lightbox_config['max_width'].' px';
	return false;
	}
	//Высота?
	if (!empty($lightbox_config['max_height'])&&($lightbox_config['max_height'])<$size[1]) {
	echo __('Error').'! '.__('Maximum height').': '.$lightbox_config['max_height'].' px';
	return false;
	}
	if (!empty($lightbox_config['watermark'])) {
	$watermark=$lightbox_config['watermark'];
		switch ($image_type) {
		case 'gif':
		$img=imagecreatefromgif($_FILES['uploadfile']['tmp_name']);
		$white = imagecolorallocate($img, 250, 250, 250);
		imagecolortransparent($img, $white);		
		ImageString($img, 7, ($width-strlen($watermark)*10), $height-20, $watermark, $white);
		imagegif($img,$filename);
		imagedestroy($img);
		break;
		case 'jpg':
		$img=imagecreatefromjpeg($_FILES['uploadfile']['tmp_name']);
		$white = imagecolorallocate($img, 250, 250, 250);	
		ImageString($img, 7, ($width-strlen($watermark)*10), $height-20, $watermark, $white);
		imagejpeg($img,$filename);
		imagedestroy($img);
		break;
		case 'png':
		$img=imagecreatefrompng($_FILES['uploadfile']['tmp_name']);
		$white = imagecolorallocate($img, 250, 250, 250);
		imagecolortransparent($img, $white);		
		ImageString($img, 7, ($width-strlen($watermark)*10), $height-20, $watermark, $white);
		imagepng($img,$filename);
		imagedestroy($img);
		break;
		default:
		echo __('Your file is not an image or is corrupted') . ' (' . $_FILES['uploadfile']['name'] . ')';
		return false;		
		break;
		}		
	}
	}		
	if (is_file($filename)) {
	echo '[img]'.$filename."[/img]\n";
	return true;
	}
	if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filename)) {
	echo '[img]'.$filename."[/img]\n";
	return true;
	} else { 
	echo __('Cannot write to file') . ': ' . $filename;
	return false;
	}
	
	function add_logo($image, $logo){
    $srcImage = ImageCreateFromPNG($image);
    $logoImage = ImageCreateFromPNG($logo);
    $srcWidth  = ImageSX($srcImage);
    $srcHeight = ImageSY($srcImage);
    $logoWidth  = ImageSX($logoImage);
    $logoHeight = ImageSY($logoImage);
	imageAlphaBlending($logoImage, false);
    imageSaveAlpha($logoImage, true);
	$trcolor = ImageColorAllocate($logoImage, 255, 255, 255);
    ImageColorTransparent($logoImage , $trcolor);
    imagecopy($srcImage, $logoImage, $srcWidth - $logoWidth,
    $srcHeight - $logoHeight, 0, 0, $logoWidth, $logoHeight);
    ImagePNG($image, $srcImage);    ImageDestroy($logoImage);
    ImageDestroy($srcImage);
}

// изменение размера с сохранением пропорций изображения
function Resize($img,$target,$max,$type){
// $img - с полным путем, $target без пути, $max - максим. размер
$width=$max;
$height=$max;
switch ( $type ){
   case 'jpg':
    $srcImage = @ImageCreateFromJPEG($img);
    break;
   case 'gif':

    $srcImage = @ImageCreateFromGIF($img);

    break;

   case 'png':

    $srcImage = @ImageCreateFromPNG($img);

    break;

   default:

    die('Неверный формат файла изображения!');

}
$srcWidth = ImageSX($srcImage);
$srcHeight = ImageSY($srcImage);
if(($width < $srcWidth) || ($height > $srcHeight)){
    $ratioWidth = $srcWidth/$width;
    $ratioHeight = $srcHeight/$height;
    if($ratioWidth < $ratioHeight)  {
    $destWidth = intval($srcWidth/$ratioHeight);
    $destHeight = $height;
    } else {
    $destWidth = $width;
    $destHeight = intval($srcHeight/$ratioWidth);
    }
    // на этапе отладки вывожу получившийся размер
//    echo "Width=".$destWidth." Height=". $destHeight;
    $resImage = ImageCreateTrueColor($destWidth, $destHeight);
    ImageCopyResampled($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
    $target=$_SERVER['DOCUMENT_ROOT'].path_image.$target.'.jpg';    
    ImageJPEG($resImage, $target, 100); // 100 - максимальное качество
    ImageDestroy($srcImage);
    ImageDestroy($resImage);
}}
?>