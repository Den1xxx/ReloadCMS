<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

//Preparations
$name_dir=DATA_PATH . 'emailing/names/';
if (is_dir($name_dir)) $skins_name=rcms_scandir($name_dir);
else rcms_mkdir($name_dir);

$mail_dir=DATA_PATH . 'emailing/emails/';
if (is_dir($mail_dir)) $skins_email=rcms_scandir($mail_dir);
else rcms_mkdir($mail_dir);


//Send emails by names
if(!empty($_POST['name'])){
    $_list = explode(',', $_POST['to']);
    $list = array();
    foreach ($_list as $user_mask){
        $user_mask = trim($user_mask);
        $users = user_get_list($user_mask);
        foreach ($users as $userdata){
            $list[] = $userdata['email'];
        }
    }
    if(!empty($list) && !empty($_POST['subj']) && !empty($_POST['body'])){
        $to = implode(';', $list);
        rcms_send_mail($to, $system->user['email'], $system->user['nickname'], $system->config['encoding'], $_POST['subj'] , $_POST['body']);
    }
}

//Send emails by email
if(!empty($_POST['email'])){
    $_list = explode(',', $_POST['_to']);
    foreach ($_list as $email){
        $email = trim($email);
		if(!empty($_POST['_subj']) && !empty($_POST['_body'])) rcms_send_mail($email, $system->user['email'], post('_from',$system->user['nickname']), $system->config['encoding'], $_POST['_subj'] , $_POST['_body']);
   }
}

// Names
$frm =new InputForm ('', 'post', __('Send e-mail'));
$frm->addbreak( __('Send e-mail'));
$frm->hidden('name', '1');
$frm->addrow(__('Users') . '<br/>' . __('You can use * in names and divide names by comma.'), $frm->text_box('to', post('to','*'), 60));
$frm->addrow(__('Subject'), $frm->text_box('subj', post('subj'), 60));
$frm->addrow(__('Body'), $frm->textarea('body', post('body'), 60, 10));
$frm->show();

//Skins by names
$frm =new InputForm ('', 'post', __('Submit'));

$frm->addbreak( __('Emailing').': '.__('Skins'));

if (!empty($skins)) {
foreach ($skins as $skin)
$frm->addrow(__('Skin') , $frm->text_box('_to', post('_to'), 60));
}
$frm->addrow(__('Skin') . '<br/>'.__('You can divide email by comma.'), $frm->text_box('_to', post('_to'), 60));
$frm->addrow(__('Sender name'), $frm->text_box('_from', post('_from'), 60));
$frm->addrow(__('Subject'), $frm->text_box('_subj', post('_subj'), 60));
$frm->addrow(__('Body'), $frm->textarea('_body', post('_body'), 60, 10));
$frm->addrow(__('New'), $frm->text_box('new_skin', post('new_skin'), 60));
$frm->show();



// Emails
$frm =new InputForm ('', 'post', __('Send e-mail'));
$frm->addbreak( __('Send e-mail'));
$frm->hidden('email', '1');
$frm->addrow(__('Emails') . '<br/>'.__('You can divide email by comma.'), $frm->text_box('_to', post('_to'), 60));
$frm->addrow(__('Sender name'), $frm->text_box('_from', post('_from'), 60));
$frm->addrow(__('Subject'), $frm->text_box('_subj', post('_subj'), 60));
$frm->addrow(__('Body'), $frm->textarea('_body', post('_body'), 60, 10));
$frm->show();


?>
