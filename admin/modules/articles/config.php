<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['nconfig'])) {
file_write_contents(CONFIG_PATH . 'articles.ini',serialize($_POST['nconfig']));
rcms_showAdminMessage(__('Configuration updated'));
}
$file = file_get_contents(CONFIG_PATH . 'articles.ini');
if (substr($file,0,2)!='a:') $articles->config = parse_ini_file(CONFIG_PATH . 'articles.ini');
else $articles->config = unserialize($file);
$config = &$articles->config;

// Interface generation
$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');

//Containers configuration
$frm->addbreak(__('Containers'));
$articles = new articles();
$containers = $articles->getContainers(0);
$frm->addrow(__('News container ID'), $frm->text_box('nconfig[news]', @$config['news'],24).$frm->select_tag('files', $containers,@$config['news'],' onchange="$(\'input[name=\\\'nconfig[news]\\\']\').val(this.value);"'), 'top');
$frm->addrow(__('Section').' '.__('Most readable articles'), $frm->text_box('nconfig[rpop]', @$config['rpop'],24).$frm->select_tag('files', $containers,@$config['rpop'],' onchange="$(\'input[name=\\\'nconfig[rpop]\\\']\').val(this.value);"'), 'top');
$frm->addrow(__('Section').' '.__('Most commented articles'), $frm->text_box('nconfig[cpop]', @$config['cpop'],24).$frm->select_tag('files', $containers,@$config['cpop'],' onchange="$(\'input[name=\\\'nconfig[cpop]\\\']\').val(this.value);"'), 'top');
$frm->addrow(__('Section').' '.__('Recently commented articles'), $frm->text_box('nconfig[lcmt]', @$config['lcmt'],24).$frm->select_tag('files', $containers,@$config['lcmt'],' onchange="$(\'input[name=\\\'nconfig[lcmt]\\\']\').val(this.value);"'), 'top');

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
//Send mail when user write comment
$frm->addrow(__('E-mail for notification of the comments') , $frm->text_box('nconfig[email]', @$config['email']));
$frm->addrow(__('Length limit for title') . ' ' .  __('Categories'), $frm->text_box('nconfig[category]', @$config['category'],4));
$frm->addrow(__('Length limit for title') . ' ' . __('Articles'), $frm->text_box('nconfig[title]', @$config['title'],4));
$frm->addrow('<a href="http://rating-widget.com/" title="Get new code" target="_blank">'.__('Rating').'</a> ', $frm->textarea('nconfig[code_rating]', @$config['code_rating'],80,7));
$frm->addrow(__('Code of social networks'), $frm->textarea('nconfig[social]', @$config['social'], 80, 7), 'top');
$frm->show();
?>