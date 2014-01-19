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
modeind = seln.mode.selectedIndex;
val = seln.mode.options[modeind].value;
var a = /^[\w\-\_]+\.(gif|jpg|png){1}$/;
	if (selNum > 0) {
	Isel = seln.files.options[selNum].text;
	if (val=='text') {
	if (Isel.search(a) !== -1) Isel='[img]<?=FILES_PATH?>'+Isel+'[/img]\n';
	else Isel='[url]<?=FILES_PATH?>'+Isel+'[/url]\n';
	document.forms['artadd'].elements['text'].value += Isel;
	} else {
	if (Isel.search(a) !== -1) Isel='<img src="<?=FILES_PATH?>'+Isel+'"/>\n';
	else Isel='<a href="<?=FILES_PATH?>'+Isel+'">'+Isel+'</a>\n';
	document.forms['artadd'].elements['text'].value += Isel;
	}
	}
}
//-->
</script> 
<?
$articles = new articles();
if(!$system->checkForRight('ARTICLES-EDITOR')) $c = '#hidden';
else $c = (empty($_POST['c'])) ? null : $_POST['c'];
$b = (empty($_POST['b'])) ? null : $_POST['b'];

if(!empty($_POST['save'])) {

$time=sql_to_unix_time($_POST['time']);

	if(!$articles->setWorkContainer($c) || !$articles->saveArticle($b, 0, $_POST['title'], $_POST['source'], $_POST['keywords'], $_POST['sef_desc'], $_POST['description'], $_POST['text'], $_POST['mode'], $_POST['comments'],$time)){
		rcms_showAdminMessage($articles->last_error);
	} elseif ($system->checkForRight('ARTICLES-EDITOR')){
		$frm = new InputForm ('?show=module&id=articles.articles', 'post', __('Edit It'));
		$frm->hidden('c', $c);
		$frm->hidden('b', $b);
		$frm->hidden('a', $_SESSION['art_id']);
		$frm->addrow('<a href="'.RCMS_ROOT_PATH.'?module=articles&c='.$c.'&b='.$b.'&a=' . $_SESSION['art_id'] . '" target="_blank">'.__('Article added').'</a>');
		$frm->show();
	} else {
		rcms_showAdminMessage('<a href="'.RCMS_ROOT_PATH.'?module=articles&c='.$c.'&b='.$b.'&a='.array_pop($articles->$index).'" target="_blank">'.__('Article added').'</a>');
	}
}


if(!empty($c)){
	if($articles->setWorkContainer($c)){
		if($c !== '#root' && $c !== '#hidden' && ($categories_list = $articles->getCategories(true, false)) === false){
			rcms_showAdminMessage($articles->last_error);
		} else {
			$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'artadd');
			$frm->addbreak(__('Post article'));
			$frm->hidden('save', '1');
			$frm->hidden('c', $c);
			if($c !== '#root' && $c !== '#hidden') 
			$frm->addrow(__('Select category'), $frm->select_tag('b', $categories_list,(!empty($b)?$b:'')), 'top');
			$frm->addrow(__('Title'), $frm->text_box('title', ''), 'top');
			$frm->addrow(__('Author/source'), $frm->text_box('source', ''), 'top');
			$frm->addrow(__('Keywords'), $frm->text_box('keywords', ''), 'top');
			$frm->addrow(__('Description for search engines'), $frm->text_box('sef_desc', ''), 'top');
			$frm->addrow('', rcms_show_bbcode_panel('artadd.description'));
			$frm->addrow(__('Short description').'<br />'.tinymce_selector('description',false), $frm->textarea('description', '', 70, 5), 'top');
			$frm->addrow('', rcms_show_bbcode_panel('artadd.text'));
			$frm->addrow(__('Text').'<br />'.tinymce_selector('text',false), $frm->textarea('text', '', 70, 25), 'top');
			$files = rcms_scandir(FILES_PATH);						//Start Insert list uploaded files
			$key_thumb=array_search('_thumb',$files);
	if ($key_thumb!==FALSE) unset($files[$key_thumb]);
	if(!empty($files))			{	
	$frm->addrow(__('Add link to file') , $frm->select_tag('files',$files,-1,'onchange="selChange(this.form)">\n
	<option value="-1">'. __('Select file').'</option')
	.'&nbsp;&nbsp;&nbsp;'.__('You entered filename of file uploaded through upload interface'), 'top');
	}																//End Insert list uploaded files
			$frm->addrow(__('Mode'), $frm->select_tag('mode', array('html' => __('HTML'), 'text' => __('Text'), 'htmlbb' => __('bbCodes') . '+' . __('HTML'), 'php' => __('PHP')), 'text'), 'top');
			$frm->addrow(__('Date').' (yyyy-mm-dd hh:mm:ss)', $frm->text_box('time', gmdate("Y-m-d H:i:s",rcms_get_time())), 'top');
			$frm->addrow(__('Allow comments'), $frm->radio_button('comments', array('yes' => __('Allow'), 'no' => __('Disallow')), 'yes'), 'top');
			$frm->show();
		}
	} else rcms_showAdminMessage($articles->last_error);
} else {
	$frm =new InputForm ('', 'post', __('Submit'));
	$frm->addrow(__('Select section'), $frm->select_tag('c', $articles->getContainers(2)), 'top');
	$frm->show();
}
?>