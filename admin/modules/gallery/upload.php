<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
rcms_loadAdminLib('file-uploads');
$gallery = new gallery();

/******************************************************************************
* Perform uploading                                                           *
******************************************************************************/
if(!empty($_FILES['upload'])) {
	if(fupload_array($_FILES['upload'], GALLERY_UPLOAD_DIR, $gallery->img_preg)){
		$new_names = $gallery->scanForNewImages();
		foreach ($_FILES['upload']['name'] as $key => $name){
			$keywords = (!empty($_POST['gkeywords']) ? $_POST['gkeywords'] : '') . (!empty($_POST['keywords'][$key]) ? (!empty($_POST['gkeywords']) ? ';' : '') . $_POST['keywords'][$key] : '');
			if(!empty($keywords)) $gallery->setKeywords(@$new_names[$name], $keywords);
			if(!empty($_POST['title'][$key])) $gallery->setDataValue(@$new_names[$name], 'title', $_POST['title'][$key]);
		}
		$gallery->saveIndexFiles();
		rcms_showAdminMessage(__('Images uploaded'));
	} else {
		rcms_showAdminMessage(__('Error occurred'));
	}
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
$frm = new InputForm ('', 'post', __('Submit'), '', '' , 'multipart/form-data');
$frm->addbreak(__('Upload images'));
$code_to_repeat = __('Image') . ': ' . $frm->file('upload[]') . '<br />' . __('Keywords') . ': ' . $frm->text_box('keywords[]', '') . '<br />' . __('Title') . ': ' . $frm->text_box('title[]', '') . '<br /><br />';
$js_b = '<script type="text/javascript">function add_file_field(){ var ni = document.getElementById(\'upload_images\'); var newdiv = document.createElement(\'div\'); newdiv.innerHTML = \'' . $code_to_repeat . '\'; ni.appendChild(newdiv); }</script>';
$js_a = '<script type="text/javascript">add_file_field();</script>';
$frm->addrow(__('Select images to upload'), $js_b . '<span id="upload_images"></span>' . $js_a . '<a href="#" onclick="add_file_field()">' . __('Upload another image') . '</a>', 'top');
$frm->addrow(__('Keywords for all images'),  $frm->text_box('gkeywords', ''), 'top');
$frm->addmessage(__('To divide keywords use ; symbol'));
$frm->addmessage(__('Also you can upload your files using filemanager or FTP to directory') . ' ' . substr(GALLERY_UPLOAD_DIR, 1) . ' ' . __('(Relative to ReloadCMS installation path) and rebuild index file.'));
$frm->show();
?>