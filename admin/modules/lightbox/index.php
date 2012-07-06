<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['lightbox_config'])) write_ini_file($_POST['lightbox_config'], CONFIG_PATH . 'lightbox.ini');

$lightbox_config = parse_ini_file(CONFIG_PATH . 'lightbox.ini');

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Automatic resizing image (lightbox effect)'));
$frm->addrow(__('Gallery'),$frm->checkbox('lightbox_config[gallery]','1', '  '.__('Add'),@$lightbox_config['gallery']));
$frm->addrow(__('Width single image in gallery (pixels)'),$frm->text_box('lightbox_config[gal_width]', @$lightbox_config['gal_width'], 5));
$frm->addrow(__('Articles').__(' and comments (add by [img] tag)'),$frm->checkbox('lightbox_config[articles]','1', '  '.__('Add'), @$lightbox_config['articles']));
$frm->addrow(__('Width image in articles and comments (pixels)'), $frm->text_box('lightbox_config[width]', @$lightbox_config['width'], 5));
$frm->show();
?>