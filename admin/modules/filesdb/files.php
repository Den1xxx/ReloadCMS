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
Isel = seln.files.options[selNum].text;
document.forms['mainfrm'].elements['link'].value = Isel;
}
//-->
</script> 
<?


$filesdb = new linksdb(DOWNLOADS_DATAFILE);

// Creating new file
if(!empty($_POST['newsave'])){
	// Defining type of file
	if(!empty($_POST['type']) && $_POST['type'] == 0) {
		$_POST['link'] = basename($_POST['link']);
		$size = (int) @filesize(FILES_PATH . $_POST['link']);	
	} else {
		$size = '-';
	}
	// And trying to save it to database
	if($filesdb->createFile($_POST['newsave'] - 1, @$_POST['title'], @$_POST['desc'], @$_POST['link'], $size, @$_POST['author'])) {
		rcms_showAdminMessage(__('File added'));
	} else rcms_showAdminMessage(__('Error occurred'));
} elseif(!empty($_POST['delete']) && is_array($_POST['delete']) && !empty($_POST['cid'])) {
	$result = '';
	$cid = (int) $_POST['cid'] - 1;
	foreach ($_POST['delete'] as $fid => $cond){
		if(!empty($cond)){
			if($filesdb->deleteFile($cid, $fid)) {
				$result .= __('File removed') . ' (' . $cid . ':' . $fid . ')<br/>';
			} else {
				$result .= __('Error occurred') . ' (' . $cid . ':' . $fid . ')<br/>';
			}
		}
	}
	rcms_showAdminMessage($result);
	unset($_POST['edit']);
} elseif (!empty($_POST['edit']) && !empty($_POST['save']) && !empty($_POST['cid'])){
	$_POST['edit'] = $_POST['edit'] - 1;
	if(!empty($_POST['type']) && $_POST['type'] == 1) {
		$_POST['link'] = basename($_POST['link']);
		$size = (int) @filesize(FILES_PATH . $_POST['link']);		
	} else {
		$size = '-';
	}
	if($filesdb->updateFile($_POST['cid']-1, $_POST['edit'], @$_POST['title'], @$_POST['desc'], @$_POST['link'], $size, @$_POST['author'])) {
		rcms_showAdminMessage(__('File updated'));
	} else rcms_showAdminMessage(__('Error occurred'));
}

////////////////////////////////////////////////////////////////////////////////
// Interface generation                                                       //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['new'])){
	$frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back')); $frm->hidden('cid', $_POST['new']); $frm->show();
	$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');
	$frm->addbreak(__('New file'));
	$frm->hidden('newsave', $_POST['new']);
	$frm->hidden('cid', $_POST['new']);
	$frm->addrow(__('Title'), $frm->text_box('title', ''));
	$frm->addrow(__('Author'), $frm->text_box('author', ''));
	$frm->addrow(rcms_show_bbcode_panel('mainfrm.desc'));
	$frm->addrow(__('Description'), $frm->textarea('desc', '', 70, 5), 'top');
	$frm->addrow(__('Filename or link to remote file'), $frm->text_box('link', ''));
	$frm->addrow(__('Select type of link you specified in previous field'), $frm->select_tag('type', array(__('You entered the link to remote file'), __('You entered filename of file uploaded through upload interface'))));
	$files = rcms_scandir(FILES_PATH);						//Start Insert list uploaded files
	if(!empty($files))			{	
	$frm->addrow(__('You entered filename of file uploaded through upload interface') , $frm->select_tag('files',$files,'0',' onClick="selChange(this.form)"').'&nbsp;&nbsp;&nbsp;'.__('Add link to file'), 'top');
	}
		$frm->show();
} elseif(!empty($_POST['edit']) && !empty($_POST['cid']) && !empty($filesdb->data[$_POST['cid'] - 1]['files'][$_POST['edit'] - 1])){
	$cid = $_POST['cid'] - 1;
	$fid = $_POST['edit'] - 1;
	$mode = $filesdb->data[$cid]['files'][$fid]['link'] == basename($filesdb->data[$cid]['files'][$fid]['link']);
	$frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back')); $frm->hidden('cid', $_POST['cid']); $frm->show();
	$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');
	$frm->addbreak(__('Edit file'));
	$frm->hidden('save', '1');
	$frm->hidden('edit', $_POST['edit']);
	$frm->hidden('cid', $_POST['cid']);
	$frm->addrow(__('Title'), $frm->text_box('title', $filesdb->data[$cid]['files'][$fid]['name']));
	$frm->addrow(rcms_show_bbcode_panel('mainfrm.desc'));
	$frm->addrow(__('Description'), $frm->textarea('desc', $filesdb->data[$cid]['files'][$fid]['desc'], 70, 5), 'top');
	$frm->addrow(__('Author'), $frm->text_box('author', $filesdb->data[$cid]['files'][$fid]['author']));
	$frm->addrow(__('Filename or link to remote file'), $frm->text_box('link', $filesdb->data[$cid]['files'][$fid]['link']));
	$frm->addrow(__('Select type of link you specified in previous field'), $frm->select_tag('type', array(__('You entered the link to remote file'), __('You entered filename of file uploaded through upload interface')), $mode));
	$files = rcms_scandir(FILES_PATH);						//Start Insert list uploaded files
	if(!empty($files))			{	
	$frm->addrow(__('You entered filename of file uploaded through upload interface') , $frm->select_tag('files',$files,'0',' onClick="selChange(this.form)"').'&nbsp;&nbsp;&nbsp;'.__('Add link to file'), 'top');
	}	
	$frm->show();
} elseif(!empty($_POST['cid'])) {
	$frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back')); $frm->show();
	$frm = new InputForm ('', 'post', __('New file')); $frm->hidden('new', $_POST['cid']); $frm->show();
	if(!empty($filesdb->data[$_POST['cid']-1]['files'])){
		$frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
		$frm->hidden('cid', $_POST['cid']);
		foreach ($filesdb->data[$_POST['cid']-1]['files'] as $fid => $fdata){
			$frm->addrow($fdata['link'] . '<br/>' . $fdata['name'] . ' (' . $fdata['desc'] . '). ' . __('Size of file') . ': ' . $fdata['size'],
			$frm->checkbox('delete[' . $fid . ']', '1', __('Delete')) . ' ' .
			$frm->radio_button('edit', array($fid + 1 => __('Edit')), 0), 'top'
			);
		}
		$frm->show();
	}
} else {
	$clist = array();
	foreach ($filesdb->data as $cid => $cdata) $clist[$cid+1] = $cdata['name'];
	if(!empty($clist)){
		$frm =new InputForm ('', 'post', __('Browse'));
		$frm->addrow(__('Select category'), $frm->select_tag('cid', $clist));
		$frm->show();
	} else rcms_showAdminMessage( __('There is no categories of files'));
}
$filesdb->close();
?>