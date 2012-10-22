<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
?>
<script script type="text/javascript">
<!--
function selChange(seln) {
selNum = seln.files.selectedIndex;
Isel = seln.files.options[selNum].value;
document.forms['mainfrm'].elements['nconfig[news]'].value = Isel;
}
//-->
</script> 
<?

if(!empty($_POST['nconfig'])) {
file_write_contents(CONFIG_PATH . 'articles.ini',serialize($_POST['nconfig']));
//write_ini_file($_POST['nconfig'], CONFIG_PATH . 'articles.ini');
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
$frm->addrow(__('News container ID'), $frm->text_box('nconfig[news]', @$config['news'],24).$frm->select_tag('files', $articles->getContainers(0),@$config['news'],' onchange="selChange(this.form)"'), 'top');

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