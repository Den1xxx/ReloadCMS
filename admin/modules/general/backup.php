<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

//Initialization API	
	rcms_loadAdminLib('archive');
	$result = '';
	
	
//Save in archive ./content	and ./config
if (!empty($_POST['backupit'])) {
	if(!empty($_POST['gzip'])) $suffix = '.gz';
	else $suffix = '';
    $bkupfilename = BACKUP_PATH . 'backup_'.(!empty($_POST['all'])?'all_':'').date('d.m.Y_H-i-s').'.tar' . $suffix;
$backup = new archiveTar();
$backup->archive_name = $bkupfilename;
if(!empty($_POST['gzip'])) $backup->isGzipped = true; 
else $backup->isGzipped = false;
	if (empty($_POST['all'])) 
	$success = $backup->createArchive(array(DATA_PATH,CONFIG_PATH));
	else {	//All files to archive, except 'backups','uploads'
	$dir_list = rcms_scandir(RCMS_ROOT_PATH);
	unset($dir_list[array_search('backups', $dir_list)]);
	unset($dir_list[array_search('uploads', $dir_list)]);
	$dir_list = array_values($dir_list);
	$success = $backup->createArchive($dir_list);
	}
if (!$success) $Errors = $backup->showErrors(basename($bkupfilename));
		if(!empty($Errors)) $result .= $Errors;
		else  $result .= __('Backup complete') . ' (' . basename($bkupfilename) . ')';
}

//Delete the archive
if(!empty($_POST['delete'])) {
	$result = '';
	foreach ($_POST['delete'] as $backup_entry => $cond){
		if(!empty($cond)) {
			if(rcms_delete_files(BACKUP_PATH . $backup_entry)) {
				$result .= __('File removed') . ': ' . $backup_entry . '<br/>';
			} else {
				$result .= __('Error occurred') . ': ' . $backup_entry . '<br/>';
			}
		}
	}
}

//Restore ./content	and ./config from archive
if (!empty($_POST['browse_archive']) AND empty($_POST['delete']))	 {
	if(!empty($_POST['restore'])){
	$restorefilename = BACKUP_PATH . $_POST['restore'];
	if (is_file ( $restorefilename )){
	rcms_delete_files(CONFIG_PATH,true);
	rcms_delete_files(DATA_PATH,true);
$Archive = new archiveTar();
$Archive->archive_name = $restorefilename;
$success = $Archive->restoreArchive(RCMS_ROOT_PATH);
if (!$success) $Errors = $Archive->showErrors(basename($restorefilename));
		if(!empty($Errors)) 	$result .= $Errors;
			else  $result .= __('Restoring done') . ' (' . basename($restorefilename) . ')';

	} else  $result .= __('Nothing founded') . ': (' . basename($restorefilename) . ')';
  }
}

//Show result
if(!empty($result)) rcms_showAdminMessage($result);

// Interface generation
$frm =new InputForm ('', 'post', __('Backup data'));
$frm->addbreak( __('Backup data'));
$frm->hidden('backupit', '1');
$frm->addrow(__('To backup all your data from directories "config" and "content" press "Create backup" button. Speed of backup creation depends on size of your site. In order to be more secure we do not provide any backups management from there. You must download or delete backups using FTP or another way to reach /backups/ folder, because HTTP access for it was forbidden.'));
$frm->addrow(__('Pack file with gzip (uncheck if you experience problems)'), $frm->checkbox('gzip', '1', '', true));
$frm->addrow(__('Enable all').' ('.__('Ignore').' ./backup ./uploads).', $frm->checkbox('all', '1', '', false));
$frm->show();
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak( __('Restore data'));
$frm->hidden('browse_archive', '1');
$backups='';
$backups = rcms_scandir(RCMS_ROOT_PATH . 'backups');
	foreach ($backups as $backup_entry){
		if(preg_match("/^((.*?)-(.*?))\.tar(|.gz)$/i", $backup_entry, $matches)){
			$frm->addrow($frm->radio_button('restore', array($backup_entry => $backup_entry), '-1').'&nbsp;&nbsp;['.__('Size of file').'&nbsp;'.filesize(RCMS_ROOT_PATH . 'backups'.'/'.$backup_entry). __(' bytes in size').']&nbsp;&nbsp;', $frm->checkbox('delete[' . $backup_entry. ']', 'true', __('Delete')));
		}
	}
$frm->addrow(__('To restore all your data, select archive and press "Submit" button. Your directory "config" and "content" may be permanently overwritten'));
$frm->show();
?>
