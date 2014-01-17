<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
rcms_loadAdminLib('file-uploads');

/******************************************************************************
* Perform uploading                                                           *
******************************************************************************/
if(!empty($_FILES['upload'])) {
    if(fupload_array($_FILES['upload'])){
        rcms_showAdminMessage(__('Files uploaded'));
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
}

/******************************************************************************
* Perform deletion                                                            *
******************************************************************************/
if(!empty($_POST['delete'])) {
    $result = '';
    foreach ($_POST['delete'] as $file => $cond){
        $file = basename($file);
        if(!empty($cond)) {
            if(fupload_delete($file)) $result .= __('File removed') . ': ' . $file . '<br/>';
            else $result .= __('Error occurred') . ': ' . $file . '<br/>';
        }
    }
    if(!empty($result)) rcms_showAdminMessage($result);
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
$frm =new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data');
$frm->addbreak(__('Upload files'));
$frm->addrow(__('Select files to upload'), $frm->file('upload[]') . $frm->file('upload[]') . $frm->file('upload[]'), 'top');
$frm->show();
$files = fupload_get_list();
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Uploaded files'));
if(!empty($files)) {
    foreach ($files as $file) {
        $loadlink = '&nbsp;&nbsp;<a href="'.RCMS_ROOT_PATH.'admin.php?show=module&id=tools.uploads&download='.base64_encode(FILES_PATH.$file['name']).'">'.__('Download').'</a>';
		if (!is_dir(FILES_PATH.$file['name']))
        $frm->addrow(__('Filename') . ' = <a href="' . FILES_PATH . $file['name'] . '" title="'.__('Show').'">' . $file['name'] . '</a> [' . __('Size of file') . ' = ' . $file['size'] . '] [' . __('Last modification time') . ' = ' . date("d F Y H:i:s", $file['mtime']) . ']', $frm->checkbox('delete[' . $file['name'] . ']', 'true', __('Delete')).$loadlink, 'top');
    }
}
$frm->show();
?>