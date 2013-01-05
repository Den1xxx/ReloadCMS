<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

// Initialisation
$installerPath = DATA_PATH.'installer/';
if (!is_dir($installerPath)) @mkdir($installerPath,0777);
$installer = parse_ini_file(CONFIG_PATH . 'installer.ini', true);
$uninstaller = parse_ini_file(CONFIG_PATH . 'uninstaller.ini', true);

// Delete installing modules
if(!empty($_POST['delete'])) {
    $result = '';
    foreach ($_POST['delete'] as $filename => $cond){
        $filename = basename($filename);
        if(!empty($cond)) {
			$name = explode ('_',$filename);
			if (empty($_POST['patch'][$uninstaller [$name[0]]['filename']]))	{
			$files = explode(',',$uninstaller [$name[0]]['files']);
			foreach ($files as $file)	{
				$file = RCMS_ROOT_PATH.$file;
				if (is_file($file) && ($file != ".") && ($file != "..")) { 
				rcms_delete_files($file);
				$result .= __('File removed') . ': ' . $file . '<br/>';
				} 
				if (is_dir($file) && ($file != ".") && ($file != "..")) {
				if(@rmdir($file))	$result .= __('Directory removed') . ': ' . $file . '<br/>';
				}
			}
			if ($file!='./') $result .= __('File removed') . ': ' . $file . '<br/>';//last file?
			else $result .= '------------------------------------------------------<br/>' .
                        __('Module removed') . ': ' . $name[0] . '<br/><br/>';
			if (is_file($installerPath.$filename)) 
			$installer [$name[0]] = $uninstaller [$name[0]];
			} 
			rcms_remove_index($name[0], $uninstaller, true);
			write_ini_file($installer,CONFIG_PATH . 'installer.ini', true);
			write_ini_file($uninstaller,CONFIG_PATH . 'uninstaller.ini', true);
        }
    }
    if(!empty($result)) rcms_showAdminMessage($result);
}


// Interface generation

// Show information from uninstaller.ini
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Uninstall modules'));
if (!empty($uninstaller)) $frm->addrow( __('Available modules'),__('Modules management'));
else $frm->addrow( __('Module not found'),'');
    foreach ($uninstaller as $key=>$modules) {
	if (isset($modules[$system->language]))  $descr = $modules[$system->language]; else $descr = $modules['description'];
	$operation = __('Delete');
		if (!empty($modules['patch'])) {
		$descr .= ". <b>".__('This is a patch. You can only delete information, but not the module.')."</b>";
		$frm->hidden('patch['.$modules ['filename'].']', '1');
		$operation = __('Information').' - '. $operation;
		}

        $frm->addrow(' [' . __($modules['name']) . '] '.__('Description').': '. $descr.'<br/> [' . $modules['filename'].'] ['.__('Module created').': '.date("d F Y H:i:s", $modules['date']).'] ['.__('Author').': '. $modules['author'].']', 
		$frm->checkbox('delete[' . $modules ['filename']. ']', 'true', $operation), 'top');
    }
$frm->show();


?>