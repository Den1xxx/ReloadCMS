<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$gallery = new gallery();

/******************************************************************************
* Titles update                                                               *
******************************************************************************/
if(!empty($_POST['title'])){
	$result = '';
	foreach ($_POST['title'] as $filename => $title){
		$current = $gallery->getData($filename);
		if($current['title'] !== $title){
			if($gallery->setDataValue($filename, 'title', $title)) {
				$result .= __('Title updated') . ': ' . $filename . '<br/>';
			} else {
				$result .= __('Cannot update title') . ': ' . $filename . '<br/>';
			}
		}
	}
	$gallery->saveIndexFiles();
	if(!empty($result)) rcms_showAdminMessage($result);
}

/******************************************************************************
* Keywords update                                                             *
******************************************************************************/
if(!empty($_POST['keywords'])){
	$result = '';
	foreach ($_POST['keywords'] as $filename => $keywords){
		$current = $gallery->getData($filename);
		if(@$current['keywords'] !== $keywords){
			if($gallery->changeKeywords($filename, $keywords)) {
				$result .= __('Keywords updated') . ': ' . $filename . '<br/>';
			} else {
				$result .= __('Cannot update keywords') . ': ' . $filename . '<br/>';
			}
		}
	}
	$gallery->saveIndexFiles();
	if(!empty($result)) rcms_showAdminMessage($result);
}

/******************************************************************************
* Perform deletion                                                            *
******************************************************************************/
if(!empty($_POST['delete'])) {
	$result = '';
	foreach ($_POST['delete'] as $filename => $cond){
		if(!empty($cond)) {
			if($gallery->removeImage($filename)) {
				$result .= __('Image removed') . ': ' . $filename . '<br/>';
			} else {
				$result .= __('Error occurred') . ': ' . $filename . '<br/>';
			}
		}
	}
	$gallery->saveIndexFiles();
	if(!empty($result)) rcms_showAdminMessage($result);
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
$files = $gallery->getFullImagesList();

$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Uploaded images'));
$frm->addmessage(__('To divide keywords use ; symbol'));
if(!empty($files)) {
	// Pagination
	if(!empty($system->config['perpage'])) {
		$perpage = $system->config['perpage'] * 3;
		$pages = ceil(sizeof($files) / $perpage);
		if(!empty($_GET['page']) && ((int) $_GET['page']) > 0) $page = ((int) $_GET['page']) - 1; else $page = 0;
		$start = $page * $perpage;
		$total = $perpage;
	} else {
		$pages = 1;
		$page = 0;
		$start = 0;
		$total = sizeof($files);
	}
	$keys = @array_keys($files);
	$pagination = rcms_pagination(sizeof($files), $perpage, $page + 1, '?' . $_SERVER['QUERY_STRING']);

	//Output
	if(!empty($pagination)) $frm->addrow($pagination);
	$c = $start;
	while ($total > 0 && $c < sizeof($keys)){
		$filename = &$files[$keys[$c]];
		$data = $gallery->getData($filename);
		$frm->addrow($filename . ' aka ' . $frm->text_box('title[' . $filename . ']', $data['title']) . '<br/>' . __('Keywords') . ': ' . $frm->text_box('keywords[' . $filename . ']', @$data['keywords']) . '<br/>' . $data['type'] . '(' . $data['size'] . ')<br/>' . $frm->checkbox('delete[' . $filename . ']', 'true', __('Delete')), $gallery->getThumbnail($filename), 'top');
		$total--;
		$c++;
	}
}
$frm->addmessage(__('To divide keywords use ; symbol'));
$frm->show();
?>