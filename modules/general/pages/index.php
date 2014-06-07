<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if (isset($_GET['id']))
	if (is_file(DATA_PATH . 'pages/' . $_GET['id'])) {
	$page = unserialize(file_get_contents(DATA_PATH . 'pages/' . $_GET['id']));
		$text = rcms_parse_text_by_mode($page['text'], $page['mode']);
		if(!empty($page['description'])) {
			$system->addInfoToHead('<meta name="Description" content="' . $page['description'] . '">' . "\n");
		}
		if(!empty($page['keywords'])) {
			$system->addInfoToHead('<meta name="Keywords" content="' . $page['keywords'] . '">' . "\n");
		}
		$title = (!empty($page['title']))? 
		(($system->checkForRight('GENERAL')) ? ' 
		<form name="" method="post" action="'.ADMIN_FILE.'?show=module&id=articles.pages&tab=2&page=' . $_GET['id'] . '" title="'.__('Edit').'">'.
		$page['title'].'
		<input type="hidden" name="edit" value="' . $_GET['id'] . '">
		<input type="image" src="' . SKIN_PATH . 'edit_small.gif" title="'.__('Edit').'">
		</form>
		' : $page['title'])
		:'';
	if (!empty($page['text']))
	show_window ($title, rcms_parse_module_template('pages.tpl', array('text'=>$text,'author_name'=>$page['author_name'], 'author_nick'=>$page['author_nick'], 'date'=>$page['date'])));
	} else show_window(__('Error'), __('There are no article with this ID'));
?>