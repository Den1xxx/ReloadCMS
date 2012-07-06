<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$gallery = new gallery();

if(!empty($_POST['rebuild'])){
	$save = false;
	if(!empty($_POST['scanfornew'])) {
		$gallery->scanForNewImages();
		rcms_showAdminMessage(__('Scan for new uploaded images') . '... ' . __('Done'));
		$save = true;
	}
	if(!empty($_POST['cleanupdirs'])) {
		$gallery->cleanUpDirectories();
		rcms_showAdminMessage(__('Directories cleanup from images not listed in index') . '... ' . __('Done'));
		$save = true;
	}
	if(!empty($_POST['cleanup'])) {
		$gallery->scanForRemovedImages();
		$gallery->cleanUpIndexes();
		rcms_showAdminMessage(__('Indexes cleanup') . '... ' . __('Done'));
		$save = true;
	}
	if($save){
		$gallery->saveIndexFiles();
	}
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
$frm = new InputForm ('', 'post', __('Rebuild index'));
$frm->addbreak(__('Indexes management'));
$frm->hidden('rebuild', '1');
$frm->addrow(__('Scan for new uploaded images'), $frm->checkbox('scanfornew', '1', '', 1));
$frm->addrow(__('Directories cleanup from images not listed in index'), $frm->checkbox('cleanupdirs', '1', '', 1));
$frm->addrow(__('Indexes cleanup'), $frm->checkbox('cleanup', '1', '', 1));
$frm->show();
?>