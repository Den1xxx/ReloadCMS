<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['lightbox_config'])) {
file_write_contents(CONFIG_PATH . 'lightbox.ini',serialize($_POST['lightbox_config']));
rcms_showAdminMessage(__('Configuration updated'));
}

$lightbox_config = unserialize(@file_get_contents(CONFIG_PATH . 'lightbox.ini'));

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Automatic resizing image (lightbox effect)'));
$frm->addrow(__('Code').'<br/> (class="gallery")',$frm->textarea('lightbox_config[code]', @$lightbox_config['code'], 60, 10));
$frm->addrow(__('Gallery'),$frm->checkbox('lightbox_config[gallery]','1', '  '.__('Add'),@$lightbox_config['gallery']));
$frm->addrow(__('Width single image in gallery (pixels)'),$frm->text_box('lightbox_config[gal_width]', @$lightbox_config['gal_width'], 5));
$frm->addrow(__('Articles').__(' and comments (add by [img] tag)'),$frm->checkbox('lightbox_config[articles]','1', '  '.__('Add'), @$lightbox_config['articles']));
$frm->addrow(__('Width image in articles and comments (pixels)'), $frm->text_box('lightbox_config[width]', @$lightbox_config['width'], 5));
$frm->show();
?>
