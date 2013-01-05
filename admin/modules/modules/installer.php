<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

// Initialisation
rcms_loadAdminLib('file-uploads');
rcms_loadAdminLib('download');
rcms_loadAdminLib('archive');
$installerPath = DATA_PATH.'installer/';
if (!is_dir($installerPath)) @mkdir($installerPath,0777);
$installer = parse_ini_file(CONFIG_PATH . 'installer.ini', true);
$uninstaller = parse_ini_file(CONFIG_PATH . 'uninstaller.ini', true);
$ignored_modules = @parse_ini_file(CONFIG_PATH.'ignored.ini',true);
$status = '';

// Perform uploading  
if(!empty($_FILES['upload'])) {
    if(fupload_array($_FILES['upload'],$installerPath)){
        rcms_showAdminMessage(__('Files uploaded'));
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
}

// Restore ignored modules
if(!empty($_POST['restore'])) {
    $result = '';
    foreach ($_POST['restore'] as $filename => $cond){
        $filename = basename($filename);
        if(!empty($cond)) {
			$name = explode ('_',$filename);
			$installer [$name[0]] = $ignored_modules [$name[0]];
			rcms_remove_index($name[0], $ignored_modules, true);
			write_ini_file($ignored_modules,CONFIG_PATH . 'ignored.ini', true);
        }
    }
    if(!empty($result)) rcms_showAdminMessage($result);
}

// Delete ignored modules. If date = 0, modules not show.
if(!empty($_POST['del_info'])) {
    $result = '';
    foreach ($_POST['del_info'] as $filename => $cond){
        $filename = basename($filename);
        if(!empty($cond)) {
			$name = explode ('_',$filename);
			$ignored_modules [$name[0]]['date']='0';
			write_ini_file($ignored_modules,CONFIG_PATH . 'ignored.ini', true);
        }
    }
    if(!empty($result)) rcms_showAdminMessage($result);
}



// Delete uploading file(s)
if(!empty($_POST['delete'])) {
    $result = '';
    foreach ($_POST['delete'] as $file => $cond){
        $file = basename($file);
        if(!empty($cond)) {
            if(fupload_delete($file,$installerPath)) {
				$name = explode ('_',$file);
			$result .= __('File removed') . ': ' . $file . '<br/>';
			rcms_remove_index($name[0], $installer, true);
			write_ini_file($installer,CONFIG_PATH . 'installer.ini', true);
			} else $result .= __('Error occurred') . ': ' . $file . '<br/>';
        }
    }
    if(!empty($result)) rcms_showAdminMessage($result);
}

//Update information
if(!empty($_POST['update'])) {
load_file('http://reloadcms.into.by/downloads/modules/2012/installer.ini', CONFIG_PATH.'install_from_server.ini', 0);
}

//Ignore array files from repository
	if(!empty($_POST['ignore'])) {
		$server_modules = parse_ini_file(CONFIG_PATH.'install_from_server.ini',true);
		if (!is_array($ignored_modules = @parse_ini_file(CONFIG_PATH.'ignored.ini',true))) $ignored_modules = array();
		foreach ($_POST['ignore'] as $file => $cond){
			if ($cond) {
			$name = explode ('_',$file);
			$file = basename($file);
			$name = explode ('_',$file);
			$ignored_modules[$name[0]] = $server_modules [$name[0]];
			write_ini_file($ignored_modules,CONFIG_PATH . 'ignored.ini', true);
			} 
		}
	}



//Download array files from repository
	if(!empty($_POST['download']) && empty($_POST['ignore'])) {
		$server_modules = parse_ini_file(CONFIG_PATH.'install_from_server.ini',true);
		foreach ($_POST['download'] as $file => $cond){
			if ($cond) {
			$name = explode ('_',$file);
				if (load_file('http://reloadcms.into.by/downloads/modules/2012/'.$file, $installerPath.$file, 0)){
				$file = basename($file);
				$name = explode ('_',$file);
				
					if (isset($uninstaller[$name[0]])) {	//if installed
					$status = __('Installed');
					@rcms_remove_index($name[0], $uninstaller, true);
					$uninstaller[$name[0]] = $server_modules [$name[0]];
					write_ini_file($uninstaller,CONFIG_PATH . 'uninstaller.ini', true);
					} else {								//if not installed
					$status = __('Not installed');
					@rcms_remove_index($name[0], $installer, true);
					$installer[$name[0]] = $server_modules [$name[0]];
					write_ini_file($installer,CONFIG_PATH . 'installer.ini', true);} 
				}
			}
		}
	}

// Install uploading file(s)
if(!empty($_POST['install']) && empty($_POST['delete'])) {
    $result = '';
    foreach ($_POST['install'] as $file => $cond){
		$file = basename($file);
		$name = explode ('_',$file);
		$restorefilename = $installerPath . $file;
        if(!empty($cond)) {
			if (is_file ( $restorefilename )){
			$Archive = new archiveTar();
			$Archive->archive_name = $restorefilename;
			$success = $Archive->restoreArchive(RCMS_ROOT_PATH);
		if (!$success) $Errors = $Archive->showErrors(basename($restorefilename));
					if(!empty($Errors)) 	$result .= $Errors;
		else { 
		rcms_remove_index($name[0], $installer, true);
		if(!empty($_POST['name'][$name[0]])) $uninstaller[$name[0]]['name'] = $_POST['name'][$name[0]];
		$uninstaller[$name[0]]['filename'] = $file;
		if(!empty($_POST['date'][$name[0]])) $uninstaller[$name[0]]['date'] = $_POST['date'][$name[0]];
		if(!empty($_POST['description'][$name[0]])) $uninstaller[$name[0]]['description'] = $_POST['description'][$name[0]];
		if(!empty($_POST[$system->language][$name[0]])) $uninstaller[$name[0]][$system->language] = $_POST[$system->language][$name[0]];
		if(!empty($_POST['author'][$name[0]])) $uninstaller[$name[0]]['author'] = $_POST['author'][$name[0]];
		$files = $Archive->files;
		asort($files);
		$list_files = '';
		foreach ($files as $key=>$file)
		$list_files = str_replace('./','',$file).','.$list_files;
		$uninstaller[$name[0]]['files'] = $list_files;
		$result .= '<br/>' . __('Installed') . ' ' . __($uninstaller[$name[0]]['name']);
		}
			} else  $result .= __('Nothing founded') . ': (' . basename($restorefilename) . ')';
        }
    } 
	write_ini_file($uninstaller,CONFIG_PATH . 'uninstaller.ini', true);
	write_ini_file($installer,CONFIG_PATH . 'installer.ini', true);
    if(!empty($result)) rcms_showAdminMessage($result);
}

// Interface generation

// Update information show
$frm =new InputForm ('', 'post', __('Update'));
$frm->addbreak(__('Update information from server'));
$frm->hidden('update', '1');
$frm->addrow( __('Information'),__('Click the Update button to get information about available modules'));
$frm->show();

// Show information from repository
if (is_file(CONFIG_PATH.'install_from_server.ini'))	{
$server_modules = parse_ini_file(CONFIG_PATH.'install_from_server.ini',true);
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Select files to upload'));
$frm->addrow( __('Available modules'),__('Manage'));
$ignored_modules = @parse_ini_file(CONFIG_PATH.'ignored.ini',true);
$icount = 0;
    foreach ($server_modules as $key=>$modules) {
		if (!isset($ignored_modules[$key])) {
			if (isset($installer[$key])) {
				if ($installer[$key]['filename'] == $modules['filename'])	$status = __('Uploaded');
				if ($installer[$key]['date'] < $modules['date'])	$status = '<b>'.__('New').'</b>';
			} elseif (isset($uninstaller[$key])) $status = __('Installed');
				else $status = '<b>'.__('Not uploaded').'</b>';
			if (isset($modules[$system->language]))  $descr = $modules[$system->language]; else $descr = $modules['description'];
			$frm->addrow(' [' . __($modules['name']) . '] '.__('Description').': '. $descr.' ['. __('Status').': '.$status.'] <br/>['.
			$modules['filename'].'] ['.__('Module created').': '.date("d F Y H:i:s", $modules['date']).' '. $modules['author'].'] ', 
			$frm->checkbox('download[' . $modules ['filename']. ']', 'true', __('Download')). ' '.
			$frm->checkbox('ignore[' . $modules ['filename']. ']', 'true', __('Ignore')), 'top'
			);
			$icount++;
		}  
    }
if ($icount==0) $frm->addrow( __('Module not found'),'');
$frm->show();
}

// Show and manage uploaded files
$files = fupload_get_list(DATA_PATH.'installer/');
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Installer'));
if(!empty($files)) {
$frm->addrow(__('Uploaded files'),__('Manage'));
    foreach ($files as $file) {
	$name = explode ('_',$file['name']);
	
if (isset($uninstaller[$name[0]])) {//Show info	
$status = __('Installed');
$install = $uninstaller;
} else {
$status = '<b>'.__('Not installed').'</b>';
$install = $installer;
}

if (isset($install[$name[0]][$system->language]))  $descr = $install[$name[0]][$system->language]; 
elseif (isset ($install[$name[0]]['description'])) $descr = $install[$name[0]]['description']; 
else { $install[$name[0]]['description'] = $name[0];  $descr = __(' is empty');}
if (isset($install[$name[0]]['name']))  $mod_name = $install[$name[0]]['name']; 
else $mod_name = ucwords($name[0]);
if (isset($install[$name[0]]['author']))  $author = $install[$name[0]]['author']; 
else $author = __(' is empty');
if (isset($install[$name[0]]['date']))  $date = $install[$name[0]]['date']; 
else $date = $file['mtime'];
if (isset($install[$name[0]]['patch']))  $patch = $install[$name[0]]['patch']; 
else $patch = '';

        $frm->addrow(
		$frm->hidden('module['.$name[0].']', $name[0]).
		$frm->hidden('name['.$name[0].']', $mod_name).
		$frm->hidden('filename['.$name[0].']', $file['name']).
		$frm->hidden('date['.$name[0].']', $date).
		$frm->hidden('description['.$name[0].']', $install[$name[0]]['description']).
		$frm->hidden($system->language.'['.$name[0].']', $descr).
		$frm->hidden('author['.$name[0].']', $author).
		$frm->hidden('patch['.$name[0].']', $patch).
		'['.  __($mod_name).']&nbsp;&nbsp;&nbsp;'.
		__('Description').': '.__($descr).'&nbsp;&nbsp;[' .__('Status').': '.$status .']<br/>['.
		 $file['name'] .  '] [' . 
		 $file['size'] .' '. __('bytes').'] [' .
		__('Author'). ': ' . $author.'] ['.
		__('Uploaded').': '. date("d F Y H:i", $file['mtime']) . ']',
		
		$frm->checkbox('install[' . $file['name'] . ']', 'true', __('Install')).
		$frm->checkbox('delete[' . $file['name'] . ']', 'true', __('Delete')), 'top');
    }
} else $frm->addrow( __('Module not found'),'');
$frm->show();

// Upload modules manually                                                 
$frm =new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data');
$frm->addbreak(__('Upload files'));
$frm->addrow(__('Select files to upload').' <br/>'.__('Example').': module_1234567890.tar.gz, (1234567890=Unix Time)', $frm->file('upload[]') . $frm->file('upload[]') . $frm->file('upload[]'), 'top');
$frm->show();

// Show information from ignored.ini
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Ignored modules'));
	if (empty($ignored_modules)) $frm->addrow( __('Module not found'),'');
	else {
		$frm->addrow( __('Available modules'),__('Manage'));
		foreach ($ignored_modules as $key=>$modules) {
			if (isset($modules[$system->language]))  $descr = $modules[$system->language]; else $descr = $modules['description'];
			if (!empty($modules['date']))
			$frm->addrow(
			' [' . __($modules['name']) . '] '.
			__('Description').': '. $descr.' <br/>[' . 
			$modules['filename'].'] ['.
			__('Module created').': '.date("d F Y H:i:s", $modules['date']).'] ['.
			__('Author').': '. $modules['author'].']', 
			$frm->checkbox('restore[' . $modules ['filename']. ']', 'true', __('Restore')).
			$frm->checkbox('del_info[' . $modules ['filename']. ']', 'true', __('Delete')), 'top'
			);
		}
	}
$frm->show();

?>