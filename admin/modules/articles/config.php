<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['nconfig'])) {
write_ini_file($_POST['nconfig'], CONFIG_PATH . 'articles.ini');
rcms_showAdminMessage(__('Configuration updated'));
}

$articles->config = parse_ini_file(CONFIG_PATH . 'articles.ini');
$config = &$articles->config;

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));

//Containers configuration
$frm->addbreak(__('Containers'));
$articles = new articles();
$frm->addrow(__('News container ID'), $frm->select_tag('nconfig[news]', $articles->getContainers(0)), 'top');

//Categories configuration
$frm->addbreak(__('Categories'));

//Views counter configuration
$frm->addrow(__('Count and show views'), $frm->checkbox('nconfig[count_views]', '1', __('Enable'), @$config['count_views']));

//Comment counter configuration
$frm->addrow(__('Count and show comments'), $frm->checkbox('nconfig[count_comments]', '1', __('Enable'), @$config['count_comments']));

//Show date configuration
$frm->addrow(__('Date'), $frm->checkbox('nconfig[show_date]', '1', __('Show'), @$config['show_date']));

//Show author configuration
$frm->addrow(__('Author'), $frm->checkbox('nconfig[show_author]', '1', __('Show'), @$config['show_author']));

//Articles configuration
$frm->addbreak(__('Articles'));
$frm->addrow(__('Length limit for title') . ' ' .  __('Categories'), $frm->text_box('nconfig[category]', @$config['category'],4));
$frm->addrow(__('Length limit for title') . ' ' . __('Articles'), $frm->text_box('nconfig[title]', @$config['title'],4));
$frm->addrow('<a href="http://rating-widget.com/" title="Get new code" target="_blank">'.__('Rating').'</a> ', $frm->textarea('nconfig[code_rating]', @$config['code_rating'],80,7));
$frm->addrow(__('Code of social networks'), $frm->textarea('nconfig[social]', @$config['social'], 80, 7), 'top');
$frm->show();
?>