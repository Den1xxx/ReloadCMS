<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if (!isset($_POST['nconfig'])) $_POST['nconfig'] = array();
if (!empty($_POST['save'])&&write_ini_file($_POST['nconfig'], CONFIG_PATH . 'smiles.ini')) rcms_showAdminMessage(__('Configuration updated'));


$system->config = parse_ini_file(CONFIG_PATH . 'smiles.ini');
$config = &$system->config;

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Smiles configuration'));
$frm->hidden('save','yes');
$res = rcms_scandir(SMILES_PATH);
sort($res);
foreach ($res as $key) {
	if (file_exists(SMILES_PATH.basename($key, ".gif").".gif")){
		$frm->addrow($frm->checkbox('nconfig['.$key.']', '1', '', @$config[$key]), '<img src = "'.SMILES_PATH.$key.'" /> ('.basename($key, ".gif").')');
	}
}
$frm->show();
?>