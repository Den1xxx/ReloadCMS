<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if (cfr('GALLERY')) {
$gallery = new gallery();
$gallery_link='<a href="?module=gallery">'.__('Gallery').'</a>';
$result='';
$system->config['pagename'] = __('Gallery').' &rarr; '.__('Editor');
if(!empty($_GET['edit']) && ($current = $gallery->getData(basename($_GET['edit'])))){
$filename=basename($_GET['edit']);
	if (!empty($_POST['save'])) {
		if($gallery->setDataValue($filename, 'title', $_POST['title'])) {
			$result .= __('Title updated') . ': ' . $filename . '<br/>';
		} else {
			$result .= __('Cannot update title') . ': ' . $filename . '<br/>';
		}
		if(@$current['keywords'] !== $_POST['keywords']){
			if($gallery->changeKeywords($filename, $_POST['keywords'])) {
				$result .= __('Keywords updated') . ': ' . $filename . '<br/>';
			} else {
				$result .= __('Cannot update keywords') . ': ' . $filename . '<br/>';
			}
		}
		$gallery->saveIndexFiles();
	}
$current = $gallery->getData(basename($_GET['edit']));	
$frm =new InputForm ('', 'post', __('Submit'));
$frm->hidden('save',1);
$frm->addrow(
__('Title') . ': ' . $frm->text_box('title', $current['title']) . '<br/>' . 
__('Keywords') . ': ' . $frm->text_box('keywords', @$current['keywords']) . '<br/>' . 
$current['type'] . '(' . $current['size'] . ')<br/>' . 
$frm->checkbox('delete', $filename, __('Delete')), 
$gallery->getThumbnail($filename),
 'top'
 );
$frm->addmessage(__('To divide keywords use ; symbol'));

if(!empty($result)) show_window($gallery_link.' &rarr; '.__('Result'),$result);
show_window($gallery_link.' &rarr; '.__('Edit').' '.$filename,$frm->show(true).back_button());
}

$result='';
if(!empty($_GET['delete'])) {
	$filename=$_GET['delete'];
	if($gallery->removeImage($filename)) {
		$result .= __('Image removed') . ': ' . $filename . '<br/>';
	} else {
		$result .= __('Error occurred') . ': ' . $filename . '<br/>';
	}
}

if(!empty($_POST['delete'])) {
	$filename=$_POST['delete'];
	if($gallery->removeImage($filename)) {
		$result .= __('Image removed') . ': ' . $filename . '<br/>';
	} else {
		$result .= __('Error occurred') . ': ' . $filename . '<br/>';
	}
}

if(!empty($result)) show_window($gallery_link.' &rarr; '.__('Result'),$result.back_button());
} else show_window(__('Error'),__('You are not administrator of this site'));
?>