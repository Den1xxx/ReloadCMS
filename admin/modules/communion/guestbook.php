<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Update guestbook configuration                                              //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['config']) && is_array ($_POST['config'])){
    if(write_ini_file($_POST['config'], CONFIG_PATH . 'guestbook.ini')){
        rcms_showAdminMessage(__('Configuration updated'));
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
}

////////////////////////////////////////////////////////////////////////////////
// Interface generation                                                       //
////////////////////////////////////////////////////////////////////////////////
$config = parse_ini_file(CONFIG_PATH . 'guestbook.ini');
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Guest book configuration'));
$frm->addrow(__('Maximum message length'), $frm->text_box('config[max_message_len]', $config['max_message_len'], 5));
$frm->addrow(__('Maximum word length'), $frm->text_box('config[max_word_len]', $config['max_word_len'], 4));
$frm->addrow(__('Maximum size of database (in messages)'), $frm->text_box('config[max_db_size]', $config['max_db_size'], 5));
$frm->addbreak(__('Configuration').' bbcodes' );
$frm->addrow(__('Enable nl2br and bbCodes').__(' (except images)'), $frm->checkbox('config[bbcodes]', '1', '', @$config['bbcodes'], 4));
$frm->addrow(__('Enable all').' bbcodes', $frm->checkbox('config[links]', '1', '', @$config['links'], 4));
$frm->show();
?>