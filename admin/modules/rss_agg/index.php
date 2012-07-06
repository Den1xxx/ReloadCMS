<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['rssaggcfg']) && isset($_POST['feeds'])) {
	write_ini_file(array('config' => $_POST['rssaggcfg'], 'feeds' => explode("\n", str_replace("\r", '', $_POST['feeds']))), CONFIG_PATH . 'rss_aggregator.ini', true);
}

$rss_cfg = parse_ini_file(CONFIG_PATH . 'rss_aggregator.ini', true);

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('RSS Aggregator'));
$frm->addrow(__('Cache timeout (seconds)'), $frm->text_box('rssaggcfg[cache_time]', $rss_cfg['config']['cache_time'], 40));
$frm->addrow(__('Length limit for title'), $frm->text_box('rssaggcfg[max_title_length]', $rss_cfg['config']['max_title_length'], 40));
$frm->addrow(__('Length limit for description'), $frm->text_box('rssaggcfg[max_desc_length]', $rss_cfg['config']['max_desc_length'], 40));
$frm->addrow(__('Feeds to aggregate (One URL per line)'), $frm->textarea('feeds', implode("\n", $rss_cfg['feeds']), 40));
$frm->show();
?>