<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

// Update minichat configuration                                       

if(!empty($_POST['minichat_config'])&& is_array($_POST['minichat_config'])) {
    if(empty($_POST['minichat_config']['allow_guests_view'])) $_POST['minichat_config']['allow_guests_view'] = '0';
    if(empty($_POST['minichat_config']['allow_guests_post'])) $_POST['minichat_config']['allow_guests_post'] = '0';
    if(empty($_POST['minichat_config']['allow_guests_enter_name'])) $_POST['minichat_config']['allow_guests_enter_name'] = '0';
    if(empty($_POST['minichat_config']['max_db_size'])) $_POST['minichat_config']['max_db_size'] = $_POST['minichat_config']['messages_to_show'];
    if(write_ini_file($_POST['minichat_config'], CONFIG_PATH . 'minichat.ini')){
        rcms_showAdminMessage(__('Configuration updated'));
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
}


// Interface generation                                                    

$minichat_config = parse_ini_file(CONFIG_PATH . 'minichat.ini');
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Minichat configuration'));
$frm->addrow(__('Number of messages to show'), $frm->text_box('minichat_config[messages_to_show]', $minichat_config['messages_to_show'], 4));
$frm->addrow(__('Maximum message length'), $frm->text_box('minichat_config[max_message_len]', $minichat_config['max_message_len'], 4));
$frm->addrow(__('Maximum word length'), $frm->text_box('minichat_config[max_word_len]', $minichat_config['max_word_len'], 4));
$frm->addrow(__('Allow guests to view minichat'), $frm->checkbox('minichat_config[allow_guests_view]', '1', '', $minichat_config['allow_guests_view']));
$frm->addrow(__('Allow guests to post in minichat'), $frm->checkbox('minichat_config[allow_guests_post]', '1', '', $minichat_config['allow_guests_post']));
$frm->addrow(__('Maximum size of database (in messages)'), $frm->text_box('minichat_config[max_db_size]', @$minichat_config['max_db_size'], 4));
$frm->show();
?>