<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
?>
<script type="text/javascript">
<!--
function selChange(seln) {
selNum = seln.files.selectedIndex;
var a = /^\w+\.(gif|jpg|png){1}$/;
	if (selNum > 0) {
	Isel = seln.files.options[selNum].text;
	if (Isel.search(a) !== -1) Isel='[img]<?=FILES_PATH?>'+Isel+'[/img]\n';
	else Isel='[url]<?=FILES_PATH?>'+Isel+'[/url]\n';
	document.forms['arted'].elements['text'].value += Isel;
	}
}
//-->
</script> 
<?
$articles = new articles();
if(!$system->checkForRight('ARTICLES-EDITOR')) $c = '#hidden';
$c = (empty($_POST['c'])) ? null : $_POST['c'];
$b = (empty($_POST['b'])) ? null : $_POST['b'];
$nb = (empty($_POST['nb'])) ? null : $_POST['nb'];
$a = (empty($_POST['a'])) ? null : $_POST['a'];

/******************************************************************************
* Perform deletion of articles                                                *
******************************************************************************/
if(!empty($_POST['delete'])) {
	foreach ($_POST['delete'] as $id => $chk) {
		if($chk && $articles->setWorkContainer($c) && $articles->deleteArticle($b, $id)){
			rcms_showAdminMessage(__('Article removed') . ': ' . $c . '/' . $b . '/' . $id);
		} else {
			rcms_showAdminMessage($articles->last_error . ': ' . $c . '/' . $b . '/' . $id);
		}
	}
}

/******************************************************************************
* Perform changing of article                                                 *
******************************************************************************/
if((!empty($_POST['save']) && !empty($c) && (!empty($b) || $c == '#hidden' || $c == '#root') && !empty($a) && $articles->setWorkContainer($c) && ($article = $articles->getArticle($b, $a, false, true, true, false))!== false)) {
	if(!@$articles->saveArticle($b, $a, 
					$_POST['title'], 
					$_POST['source'], 
					$_POST['keywords'], 
					$_POST['sef_desc'], 
					$_POST['description'], 
					$_POST['text'], $_POST['mode'], 
					$_POST['comments'])){
			rcms_showAdminMessage($articles->last_error);
	} else {
		rcms_showAdminMessage(__('Article saved'));
		sleep(1);
		if(!empty($nb) && $nb != $b){
			if(!$articles->moveArticle($b, $a, $nb)){
				rcms_showAdminMessage($articles->last_error);
			} else {
				rcms_showAdminMessage(__('Article moved'));
			}
		} else rcms_redirect ('http://'.$_SERVER['HTTP_HOST'].'/?module=articles&c='.$c.'&b='.$b.'&a='.$a);
	}
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
// Show container selector
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addrow(__('Select section'), $frm->select_tag('c', $articles->getContainers(2), $c), 'top');
// Show category selector
if(!empty($c) && $c != '#hidden' && $c != '#root'){
	if($articles->setWorkContainer($c)){
		$frm->addrow(__('Select category'), $frm->select_tag('b', $articles->getCategories(true, false), $b), 'top');
	} else rcms_showAdminMessage($articles->last_error);
}
$frm->show();

if(!empty($_POST['tc']) && !empty($_POST['tb']) && !empty($_POST['ms']) && $_POST['ms'] == '2'){
	if(!$articles->moveArticleToContainer($_POST['mc'], $_POST['mb'], $_POST['ma'], $_POST['tc'], $_POST['tb'])){
		rcms_showAdminMessage($articles->last_error);
	} else {
		rcms_showAdminMessage(__('Article moved'));
	}
} elseif(!empty($_POST['tc']) && !empty($_POST['ms']) && $_POST['ms'] == '1'){
	if($_POST['mc'] == $_POST['tc']) {
		rcms_showAdminMessage(__('Cannot move article to source section'));
	} elseif(!$articles->setWorkContainer($_POST['tc'])) {
		rcms_showAdminMessage($articles->last_error);
	} elseif ($_POST['tc'] == '#hidden' || $_POST['tc'] == '#root') {
		if(!$articles->moveArticleToContainer($_POST['mc'], $_POST['mb'], $_POST['ma'], $_POST['tc'])){
			rcms_showAdminMessage($articles->last_error);
		} else {
			rcms_showAdminMessage(__('Article moved'));
		}
	} else {
		$containers = $articles->getContainers(1);
		$frm = new InputForm ('', 'post', __('Submit'));
		$frm->addbreak(__('Target where we will place article'));
		$frm->hidden('ms', '2');
		$frm->hidden('mc', $_POST['mc']);
		$frm->hidden('mb', $_POST['mb']);
		$frm->hidden('ma', $_POST['ma']);
		$frm->hidden('tc', $_POST['tc']);
		$frm->addrow(__('Section'), $containers[$_POST['tc']], 'top');
		$frm->addrow(__('Select category'), $frm->select_tag('tb', $articles->getCategories(true, false), $b), 'top');
		$frm->show();
	}
} elseif(!empty($c) && (!empty($b) || $c == '#hidden' || $c == '#root') && !empty($_POST['move'])){
	$frm = new InputForm ('', 'post', __('Submit'));
	$frm->addbreak(__('Target where we will place article'));
	$frm->hidden('ms', '1');
	$frm->hidden('mc', $c);
	$frm->hidden('mb', $b);
	$frm->hidden('ma', $_POST['move']);
	$frm->addrow(__('Select section'), $frm->select_tag('tc', $articles->getContainers(1), $c), 'top');
	$frm->show();
} elseif(!empty($c) && (!empty($b) || $c == '#hidden' || $c == '#root') && !empty($a) && $articles->setWorkContainer($c) && ($article = $articles->getArticle($b, $a, false, true, true, false))!== false){
	$categories_list = $articles->getCategories(true, false);
	$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'arted');
	$frm->addbreak(__('Edit article') . ' - ' . $article['title']);
	$frm->hidden('save', '1');
	$frm->hidden('c', $c);
	$frm->hidden('a', $a);
	$frm->hidden('b', $b);
	if($c !== '#root' && $c !== '#hidden') $frm->addrow(__('Select category'), $frm->select_tag('nb', $categories_list, $article['catid']), 'top');
	$frm->addrow(__('Title'), $frm->text_box('title', $article['title']), 'top');
	$frm->addrow(__('Author/source'), $frm->text_box('source', $article['src']), 'top');
	$frm->addrow(__('Keywords'), $frm->text_box('keywords', @$article['keywords']), 'top');
	$frm->addrow(__('Description for search engines'), $frm->text_box('sef_desc', @$article['sef_desc']), 'top');
	$frm->addrow('', rcms_show_bbcode_panel('arted.description'));
	$frm->addrow(__('Short description'), $frm->textarea('description', $article['desc'], 70, 5), 'top');
	$frm->addrow('', rcms_show_bbcode_panel('arted.text'));
	$frm->addrow(__('Text'), $frm->textarea('text', $article['text'], 70, 25), 'top');
	$files = rcms_scandir(FILES_PATH);										//Start Insert list uploaded files
	if(!empty($files))			{	
	$frm->addrow(__('Add link to file') , $frm->select_tag('files',$files,-1,'onChange="selChange(this.form)">\n
	<option value="-1">'. __('Select file').'</option')
	.'&nbsp;&nbsp;&nbsp;'.__('You entered filename of file uploaded through upload interface'), 'top');
	}																		//End Insert list uploaded files
	$frm->addrow(__('Mode'), $frm->select_tag('mode', array('html' => __('HTML'), 'text' => __('Text'), 'htmlbb' => __('bbCodes') . '+' . __('HTML')), 'text','onchange="if (this.options[selectedIndex].value==\'html\') { 	
		tinyMCE.init({
		mode : \'exact\',
		elements : \'description,text\',
		theme : \'advanced\',
		language : \'ru\',
        plugins : \'paste,table,cyberim\',
        theme_advanced_buttons2_add : \'pastetext,pasteword,selectall\',
        theme_advanced_buttons3_add : \'tablecontrols\',
		theme_advanced_toolbar_location : \'top\',
        theme_advanced_toolbar_align : \'left\',
        theme_advanced_statusbar_location : \'bottom\',
        theme_advanced_resizing : true,
        paste_auto_cleanup_on_paste : true,
		content_css: \'/css/tinymce.css\',
		extended_valid_elements : \'script[type|language|src]\',
		forced_root_block : \'\', 
		force_br_newlines : true,
		force_p_newlines : false
		});
		$(\'table.bb_editor\').hide();} else {
		tinyMCE.get(\'description\').hide();
		tinyMCE.get(\'text\').hide();
		$(\'table.bb_editor\').show();
		}"'), 'top');
	
	
	$frm->addrow(__('Allow comments'), $frm->radio_button('comments', array('yes' => __('Allow'), 'no' => __('Disallow')), $article['comments']), 'top');
	$frm->show();
} elseif(!empty($b) || $c == '#hidden' || $c == '#root'){
	if($articles->setWorkContainer($c)){
		$frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
		$frm->addbreak(__('List of articles'));
		$frm->hidden('c', $c);
		$frm->hidden('b', $b);
		if(($list = $articles->getArticles($b, false, false, false)) !== false){
			foreach (array_reverse($list, true) as $id => $article){
			$frm->addrow('<a href="' . RCMS_ROOT_PATH . '?module=articles&c=' . $c . '&b=' . $b . '&a=' . $article['id'] . '" target="_blank">' . $article['title'] . '</a>' . ' [' . user_create_link($article['author_name'], $article['author_nick'], '_blank') . '] [' . rcms_format_time('d F Y H:i:s', $article['time']) . ']',
				$frm->checkbox('delete[' . $article['id'] . ']', '1', __('Delete')) . $frm->radio_button('a', array($article['id'] => __('Edit'))) . $frm->radio_button('move', array($article['id'] => __('Move')))
				);
			}
		} else rcms_showAdminMessage($articles->last_error);
		$frm->show();
	} else rcms_showAdminMessage($articles->last_error);
}
?>