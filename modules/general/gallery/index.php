<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$gallery = new gallery();
$system->config['pagename'] = __('Gallery');

if(!empty($_GET['id']) && ($image_data = $gallery->getData(basename($_GET['id'])))){
	$id = basename($_GET['id']);

	$linkdata = '';
	if(!empty($_GET['size']) && !empty($_GET['type'])){
		$images_s = $gallery->getLimitedImagesList('size', $_GET['size']);
		$images_t = $gallery->getLimitedImagesList('type', $_GET['type']);
		$images = array();
		foreach ($images_s as $image){
			if(in_array($image, $images_t)) $images[] = $image;
		}
		$linkdata .= '&amp;size=' . $_GET['size'] . '&amp;type=' . $_GET['type'];
	} elseif(!empty($_GET['size'])){
		$images = $gallery->getLimitedImagesList('size', $_GET['size']);
		$linkdata .= '&amp;size=' . $_GET['size'];
	} elseif(!empty($_GET['type'])){
		$images = $gallery->getLimitedImagesList('type', $_GET['type']);
		$linkdata .= '&amp;type=' . $_GET['type'];
	} else {
		$images = $gallery->getFullImagesList();
	}
	if(!empty($_GET['keyword'])){
		$images_k = $gallery->getLimitedImagesList('keywords', $_GET['keyword']);
		$images_a = $images;
		$images = array();
		foreach ($images_k as $image){
			if(in_array($image, $images_a)) $images[] = $image;
		}
		$linkdata .= '&amp;keyword=' . $_GET['keyword'];
	}
	if(!empty($images)){
		$imagesf = array_flip($images);
		arsort($imagesf);
	} else {
		$imagesf = array();
	}
	$system->config['pagename'] .= ' - '.$image_data['title'];	
	$title='<a href="?module=gallery">'.__('Gallery').'</a>&rarr;'.$image_data['title'];
	$title.=cfr('GALLERY')?' '.edit_button(ADMIN_FILE.'?show=module&id=gallery.upload&tab=5'):'';
	$image_arr=array(
	'module' => $module,
	'id'=>$id,
	'linkdata'=>$linkdata,
	'image'=>$gallery->getImage($id),
	'previous' => ($imagesf[$id] > 0) ? $images[$imagesf[$id] - 1] : '', 
	'next'=>($imagesf[$id] < (sizeof($imagesf) - 1))?$images[$imagesf[$id] + 1] : ''
	);
	
	show_window($title,rcms_parse_module_template('gallery-image.tpl', $image_arr) , 'center');

	if(!empty($_POST['comtext'])) {
		if (isset($system->config['gallery-guest']) and !LOGGED_IN){
			show_error(__('You are not logined!'));
		} else {                                    

if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout'])))    
{
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
			$gallery->postComment($id, $_POST['comtext']);
			echo '<script type="text/javascript">document.location.href=document.location.href;</script>'; 
		}
else {
show_window(__('Error'),__('Invalid form data'));
} 
} else {
			$gallery->postComment($id, $_POST['comtext']);
			echo '<script type="text/javascript">document.location.href=document.location.href;</script>'; 
}
		}
	}

	if((!empty($_POST['gcd']) || @$_POST['gcd'] === '0') && $system->checkForRight('GALLERY')) {
		$gallery->removeComment($id, $_POST['gcd']);
	}

	foreach ($gallery->getComments($id) as $message) {
		show_window('', rcms_parse_module_template('gallery-comment.tpl', $message), 'center');
	}
	if (isset($system->config['forum-guest']) and !LOGGED_IN){
    	show_window(__('Post comment'), __('You are not logined!'), 'center');
    } else {
		show_window(__('Post comment'), rcms_parse_module_template('gallery-form.tpl'), 'center');
	}
} else {
	$data['sizes'] = $gallery->getAvaiableValues('size');
	$data['types'] = $gallery->getAvaiableValues('type');
	$data['keywords'] = $gallery->getAvaiableValues('keywords');

	$linkdata = '?module=' . $module;
	if(!empty($_GET['size']) && !empty($_GET['type'])){
		$images_s = $gallery->getLimitedImagesList('size', $_GET['size']);
		$images_t = $gallery->getLimitedImagesList('type', $_GET['type']);
		$images = array();
		foreach ($images_s as $image){
			if(in_array($image, $images_t)) $images[] = $image;
		}
		$linkdata .= '&amp;size=' . $_GET['size'] . '&amp;type=' . $_GET['type'];
	} elseif(!empty($_GET['size'])){
		$images = $gallery->getLimitedImagesList('size', $_GET['size']);
		$linkdata .= '&amp;size=' . $_GET['size'];
	} elseif(!empty($_GET['type'])){
		$images = $gallery->getLimitedImagesList('type', $_GET['type']);
		$linkdata .= '&amp;type=' . $_GET['type'];
	} else {
		$images = $gallery->getFullImagesList();
	}
	if(!empty($_GET['keyword'])){
		$images_k = $gallery->getLimitedImagesList('keywords', $_GET['keyword']);
		$images_a = $images;
		$images = array();
		foreach ($images_k as $image){
			if(in_array($image, $images_a)) $images[] = $image;
		}
		$linkdata .= '&amp;keyword=' . $_GET['keyword'];
	}
	if(!empty($images)){
		ksort($images);
	}

	if(!empty($system->config['perpage'])) {
		$pages = ceil(sizeof($images) / $system->config['perpage']);
		if(!empty($_GET['page']) && ((int) $_GET['page']) > 0) $page = ((int) $_GET['page']) - 1; else $page = 0;
		$start = $page * $system->config['perpage'];
		$total = $system->config['perpage'];
	} else {
		$pages = 1;
		$page = 0;
		$start = 0;
		$total = sizeof($images);
	}
	$keys = @array_keys($images);

	$data['pagination'] = rcms_pagination(sizeof($images), $system->config['perpage'], $page + 1, '?' . $_SERVER['QUERY_STRING']);
	$c = $start;
	$data['images'] = array();
	while ($total > 0 && $c < sizeof($keys)){
		$image = &$images[$keys[$c]];
		if($image_data = $gallery->getData($image)){
			$data['images'][$image] = $image_data + array('thumbnail' => $gallery->getThumbnail($image), 'comments' => $gallery->countComments($image));
		}
		$total--;
		$c++;
	}
	$data['linkdata'] = $linkdata;
	
	show_window(
	'<a href="?module=gallery">'.__('Gallery').'</a>'.(cfr('GALLERY')?'&nbsp;'.edit_button(ADMIN_FILE.'?show=module&id=gallery.upload&tab=5'):'').
	(get('keyword') ? ' &rarr; '.get('keyword') : ''), 
	!empty($images)?rcms_parse_module_template('gallery.tpl', $data):__('Nothing founded'), 
	'center'
	);
}
?>
