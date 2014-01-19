<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////


if(!empty($_POST['rss_disable'])) {
write_ini_file($_POST['rss_disable'], CONFIG_PATH . 'rss_disable.ini');
rcms_showAdminMessage(__('Configuration updated'));
sleep(1);
rcms_redirect('');
}

$disable_feeds = parse_ini_file(CONFIG_PATH . 'rss_disable.ini');

// Interface generation
$frm =new InputForm ('', 'post', __('Save'));

//RSS configuration
$frm->addbreak(__('RSS Feeds list'));
	foreach ($system->feeds as $module => $d) {
	if (isset($disable_feeds[$module])) $disable_feeds[$module]=1;
	else $disable_feeds[$module]=0;
	}
	ksort($disable_feeds);
	foreach ($disable_feeds as $key=>$value) {
	$frm->addrow(__('Disable').' '.__('RSS feed'), $frm->checkbox('rss_disable['.$key.']', '1', $key, $disable_feeds[$key]));
	}
$frm->show();
?>
