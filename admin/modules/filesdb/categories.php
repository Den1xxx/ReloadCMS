<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$filesdb = new linksdb(DOWNLOADS_DATAFILE);

if (!empty($_POST['newsave'])){
	if($filesdb->createCategory(@$_POST['ctitle'], @$_POST['cdesc'], (int)@$_POST['clevel'])) {
		rcms_showAdminMessage(__('Category created'));
	} else rcms_showAdminMessage(__('Error occurred'));
} elseif(!empty($_POST['delete']) && is_array($_POST['delete'])) {
	$result = '';
	foreach ($_POST['delete'] as $id => $cond){
		if(!empty($cond)){
			if($filesdb->deleteCategory($id)) {
				$result .= __('Category removed') . ' (' . $id . ')<br/>';
			} else {
				$result .= __('Error occurred') . ' (' . $id . ')<br/>';
			}
		}
	}
	rcms_showAdminMessage($result);
	$_POST['edit'] = 0;
} elseif (!empty($_POST['edit']) && !empty($_POST['save'])){
	if($filesdb->updateCategory($_POST['edit']-1, @$_POST['ctitle'], @$_POST['cdesc'], (int)@$_POST['clevel'])){
		rcms_showAdminMessage(__('Category updated'));
	} else rcms_showAdminMessage(__('Error occurred'));
}

if(!empty($_POST['new'])){
	$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');
	$frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
	$frm->addbreak(__('Add category'));
	$frm->hidden('newsave', '1');
	$frm->addrow(__('Title'), $frm->text_box('ctitle', ''));
	$frm->addrow(rcms_show_bbcode_panel('mainfrm.cdesc'));
	$frm->addrow(__('Description'), $frm->textarea('cdesc', '', 70, 5), 'top');
	$frm->addrow(__('Minimum access level'), $frm->text_box('clevel', '0'));
	$frm->show();
} elseif(!empty($_POST['edit'])){
	if(!empty($filesdb->data[$_POST['edit']-1])){
		$category = &$filesdb->data[$_POST['edit']-1];
		$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');
		$frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
		$frm->addbreak(__('Edit category'));
		$frm->hidden('save', '1');
		$frm->hidden('edit', $_POST['edit']);
		$frm->addrow(__('Title'), $frm->text_box('ctitle', $category['name']));
		$frm->addrow(rcms_show_bbcode_panel('mainfrm.cdesc'));
		$frm->addrow(__('Description'), $frm->textarea('cdesc', $category['desc'], 70, 5), 'top');
		$frm->addrow(__('Minimum access level'), $frm->text_box('clevel', @$category['accesslevel']));
		$frm->show();
	} else rcms_showAdminMessage(__('Error occurred'));
} else {
	$frm = new InputForm ('', 'post', __('Add category'));
	$frm->hidden('new', '1');
	$frm->show();
	$frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
	if(!empty($filesdb->data)){
		foreach ($filesdb->data as $cid => $cdata){
			$frm->addrow($cdata['name'] . ': ' . __('Files in category') . ': ' . sizeof($cdata['files']),
			$frm->checkbox('delete[' . $cid . ']', '1', __('Delete')) . ' ' .
			$frm->radio_button('edit', array($cid+1 => __('Edit')), 0)
			);
		}
	}
	$frm->show();
}
$filesdb->close();
?>