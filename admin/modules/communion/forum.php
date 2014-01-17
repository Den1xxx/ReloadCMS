<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['forum_config'])&&write_ini_file($_POST['forum_config'], CONFIG_PATH . 'forum.ini')) rcms_showAdminMessage(__('Configuration updated'));

$forum_config = parse_ini_file(CONFIG_PATH . 'forum.ini');

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Forum'));
$frm->addrow(__('Length limit for topic title'), $frm->text_box('forum_config[max_topic_title]', @$forum_config['max_topic_title'], 5));
$frm->addrow(__('Length limit for topic text'), $frm->text_box('forum_config[max_topic_len]', @$forum_config['max_topic_len'], 5));
$frm->addrow(__('Length limit for post text'), $frm->text_box('forum_config[max_message_len]', @$forum_config['max_message_len'], 5));
$frm->show();
?>