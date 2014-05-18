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
$frm->addbreak(__('Javascript'));
$frm->addrow(__('Editor').'<br/> elements : \'{textarea}\',',$frm->textarea('lightbox_config[editor]', @$lightbox_config['editor'], 60, 10));
$frm->addbreak(__('Automatic resizing image (lightbox effect)'));
$frm->addrow(__('Code').'<br/> (class="gallery")',$frm->textarea('lightbox_config[code]', @$lightbox_config['code'], 60, 10));
$frm->addrow(__('Gallery'),$frm->checkbox('lightbox_config[gallery]','1', '  '.__('Add'),@$lightbox_config['gallery']));
$frm->addrow(__('Width single image in gallery (pixels)'),$frm->text_box('lightbox_config[gal_width]', @$lightbox_config['gal_width'], 5));
$frm->addrow(__('Articles').__(' and comments (add by [img] tag)'),$frm->checkbox('lightbox_config[articles]','1', '  '.__('Add'), @$lightbox_config['articles']));
$frm->addrow(__('Width image in articles and comments (pixels)'), $frm->text_box('lightbox_config[width]', @$lightbox_config['width'], 5));
$frm->addbreak(__('Change uploaded images'));
$frm->addrow(__('Change uploaded images'),$frm->checkbox('lightbox_config[change_enable]','1', '  '.__('Enable'), @$lightbox_config['change_enable']));
$frm->addrow(__('Size of file'), $frm->text_box('lightbox_config[max_size]', @$lightbox_config['max_size'], 5).' Mb');
$frm->addrow(__('Maximum width'), $frm->text_box('lightbox_config[max_width]', @$lightbox_config['max_width'], 5).' px');
$frm->addrow(__('Maximum height'), $frm->text_box('lightbox_config[max_height]', @$lightbox_config['max_height'], 5).' px');
$frm->addrow(__('Watermark'), $frm->text_box('lightbox_config[watermark]', @$lightbox_config['watermark'], 35));
$frm->addbreak(__('Users'));
$lightbox_config['right_string']=empty( $lightbox_config['right_string'])?'GENERAL': $lightbox_config['right_string'];
$right=array('GENERAL'=>__('Administrator').' -'.__(' as default'),'-any-'=>'Advanced user','LOGGED_IN'=>'Only for registered users');
$frm->addrow(__('Right to upload images'),$frm->checkbox('lightbox_config[manage_enable]','1', '  '.__('Enable'), @$lightbox_config['manage_enable']).' '.$frm->select_tag('lightbox_config[right_string]', $right, $lightbox_config['right_string']));
$folders=array('0'=>'Disable','user'=>'User','user_year'=>__('User').'/'.__('Year'),'user_year_month'=>__('User').'/'.__('Year').'/'.__('Month'),'year'=>__('Year'),'year_month'=>__('Year').'/'.__('Month'));
$frm->addrow(__('Distribute images to subfolders'),$frm->checkbox('lightbox_config[distribute_enable]','1', '  '.__('Enable'), @$lightbox_config['distribute_enable']).' '.$frm->select_tag('lightbox_config[folders]', $folders, $lightbox_config['folders']));
$frm->addrow(__('Make unique image name'),$frm->checkbox('lightbox_config[unique]','1', '  '.__('Enable'), @$lightbox_config['unique']));
$frm->addrow(__('Close by clicking'),$frm->checkbox('lightbox_config[close_by_clicking]','1', '  '.__('Enable'), @$lightbox_config['close_by_clicking']));
$frm->show();
?>
