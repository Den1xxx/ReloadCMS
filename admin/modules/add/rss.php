<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////


if(!empty($_POST['save'])) {
	foreach ($system->disable_feeds as $dkey => $dvalue) {
	if (!empty($_POST['rss_disable'][$dkey])) $system->disable_feeds[$dkey]=1;
	else $system->disable_feeds[$dkey]=0;
	}
write_ini_file($system->disable_feeds, CONFIG_PATH . 'rss_disable.ini');
rcms_showAdminMessage(__('Configuration updated'));
}

// Interface generation
$frm =new InputForm ('', 'post', __('Save'));

//RSS configuration
$frm->addbreak(__('RSS Feeds list'));
	foreach ($system->disable_feeds as $key=>$value) {
	$frm->addrow(__('Disable').' '.__('RSS feed'), $frm->checkbox('rss_disable['.$key.']', '1', $key, $system->disable_feeds[$key]));
	}
	$frm->hidden('save', '1');
$frm->show();
?>
