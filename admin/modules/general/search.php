<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['nconfig'])) write_ini_file($_POST['nconfig'], CONFIG_PATH . 'search.ini');

$system->config = parse_ini_file(CONFIG_PATH . 'search.ini');
$config = &$system->config;

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Search engine configuration'));
$frm->addrow(__('Allow guests use searching'), $frm->checkbox('nconfig[guest]', '1', '', @$config['guest']));
$frm->addrow(__('Allow chose search source'), $frm->checkbox('nconfig[chose]', '1', '', @$config['chose']));
$frm->addrow(__('Check access level before search in article'), $frm->checkbox('nconfig[access]', '1', '', @$config['access']));
$frm->addrow(__('Min length'), $frm->text_box("nconfig[min]", @$config['min']));
$frm->addrow(__('Max length'), $frm->text_box("nconfig[max]", @$config['max']));
$frm->addrow(__('Output block length'), $frm->text_box("nconfig[block]", @$config['block']));
$frm->addrow(__('Editbox width'), $frm->text_box("nconfig[width]", @$config['width']));
$frm->show();
?>