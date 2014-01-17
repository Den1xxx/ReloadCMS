<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['disable'])){
	if(!is_array($_POST['disable'])) $_POST['disable'] = array();
	if(write_ini_file($_POST['disable'], CONFIG_PATH . 'disable.ini')){
		rcms_showAdminMessage(__('Configuration updated'));
	} else {
		rcms_showAdminMessage(__('Error occurred'));
	}
}
$system->initialiseModules(true);
if(!$disabled = @parse_ini_file(CONFIG_PATH . 'disable.ini')){
	$disabled = array();
}
// Interface generation
$frm = new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Modules management'));
$frm->hidden('disable', '');
foreach ($system->modules as $type => $modules){
	foreach ($modules as $module => $moduledata){
		$frm->addrow(__('Module') . ': ' . $moduledata['title'] . '<br/><b>' . $moduledata['copyright'] . '</b>', $frm->checkbox('disable[' . $module . ']', '1', __('Disable'), !empty($disabled[$module])));
	}
}
$frm->show();
?>