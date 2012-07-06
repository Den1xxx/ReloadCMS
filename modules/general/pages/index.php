<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if (isset($_GET['id']))
	if (is_file(DATA_PATH . 'pages/' . $_GET['id'])) {
	$page = unserialize(file_get_contents(DATA_PATH . 'pages/' . $_GET['id']));
		if ($page['mode']!='php') $text = rcms_parse_text_by_mode($page['text'], $page['mode']);
		else {
		ob_start();
		eval($page['text']);
		$text = ob_get_contents();
		ob_end_clean();
		}
		if(!empty($page['description'])) {
			$system->addInfoToHead('<meta name="Description" content="' . $page['description'] . '">' . "\n");
		}
		if(!empty($page['keywords'])) {
			$system->addInfoToHead('<meta name="Keywords" content="' . $page['keywords'] . '">' . "\n");
		}
	if (!empty($page['title'])) $title = $page['title']; else $title = '';
	if (!empty($page['text']))
	show_window ($title, rcms_parse_module_template('pages.tpl', array('text'=>$text,'author_name'=>$page['author_name'], 'author_nick'=>$page['author_nick'], 'date'=>$page['date'])));
	} else show_window(__('Error'), __('There are no article with this ID'));
?>
