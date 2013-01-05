<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
rcms_loadAdminLib('ucm');

////////////////////////////////////////////////////////////////////////////////
// Menus control                                                              //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['delete']) && is_array($_POST['delete'])) {
    $msg = '';
    foreach ($_POST['delete'] as $id => $cond){
        if($cond){
            if(ucm_delete($id)) {
                $msg .= __('Module removed')  . ': ' . $id . '<br />';
            } else {
                $msg .= __('Error occurred')  . ': ' . $id . '<br />';
            }
        }
    }
    rcms_showAdminMessage($msg);
    unset($_REQUEST['edit']);
} elseif (!empty($_POST['newsave'])) {
    if(ucm_create($_POST['id'], $_POST['title'], $_POST['text'], $_POST['align'])){
        rcms_showAdminMessage(__('Module created'));
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
} elseif (!empty($_REQUEST['edit']) && !empty($_POST['save'])) {
    if(ucm_change($_REQUEST['edit'], $_POST['id'], $_POST['title'], $_POST['text'], $_POST['align'])){
        rcms_showAdminMessage(__('Module updated'));
        $_REQUEST['edit'] = $_POST['id'];
    } else {
        rcms_showAdminMessage(__('Error occurred'));
    }
}

////////////////////////////////////////////////////////////////////////////////
// Interface generation                                                       //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['new'])){
    $frm = new InputForm ('', 'post', __('Submit'));
    $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
    $frm->addbreak(__('Create menu'));
    $frm->hidden('newsave', '1');
    $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', ''));
    $frm->addrow(__('Title'), $frm->text_box('title', ''));
    $frm->addrow(__('Alignment'), $frm->select_tag('align', array('center' => __('Center'), 'left' => __('Left'), 'right' => __('Right'), 'justify' => __('Justify'))));
    $frm->addrow(__('Text') . '<br/>' .  __('All HTML is allowed in this field and line breaks will not be transformed to &lt;br&gt; tags!'), $frm->textarea('text', '', 70, 25), 'top');
		$frm->addrow(__('Editor'), $frm->select_tag('mode', array('html' => __('Enable'), 'text' => __('Disable')), 'text','onchange="if (this.options[selectedIndex].value==\'html\') { 	
		tinyMCE.init({
		mode : \'exact\',
		elements : \'text\',
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
		} else {
		tinyMCE.get(\'text\').hide();
		}"'), 'top');		
    $frm->show();
} elseif(!empty($_REQUEST['edit'])){
    if($menu = ucm_get($_REQUEST['edit'])){
        $frm = new InputForm ("", "post", __('Submit'));
if (!empty($_GET['edit']))  $frm->addmessage('&lt;&lt;&lt; <a href="'.RCMS_ROOT_PATH.'">'.__('Return to').' '.__('site index').'</a>');
else  $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
        $frm->addbreak(__('Menu editing'));
        $frm->hidden('edit', $_REQUEST['edit']);
        $frm->hidden('save', '1');
if (empty($_GET['edit'])) $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', $_REQUEST['edit']));
else $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $_REQUEST['edit'].$frm->hidden('id', $_REQUEST['edit']));
        $frm->addrow(__('Title'), $frm->text_box('title', $menu[0]));
        $frm->addrow(__('Alignment'), $frm->select_tag('align', array('center' => __('Center'), 'left' => __('Left'), 'right' => __('Right'), 'justify' => __('Justify')), $menu[2]));
        $frm->addrow(__('Text') . '<br/>' . __('All HTML is allowed in this field and line breaks will not be transformed to &lt;br&gt; tags!'), $frm->textarea('text', $menu[1], 70, 25), 'top');
	$frm->addrow(__('Editor'), $frm->select_tag('mode', array('html' => __('Enable'), 'text' => __('Disable')), 'text','onchange="if (this.options[selectedIndex].value==\'html\') { 	
		tinyMCE.init({
		mode : \'exact\',
		elements : \'text\',
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
		} else {
		tinyMCE.get(\'text\').hide();
		}"'), 'top');		
        $frm->show();
    } else rcms_showAdminMessage(__('Cannot open menu for editing'));
} else {
    $frm = new InputForm ('', 'post', __('Create menu')); $frm->hidden('new', '1'); $frm->show();
    $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
    $frm->addbreak(__('User-Created-Menus'));
    $menus = ucm_list();
    foreach ($menus as $id => $menu){
        $frm->addrow(__('Menu module') . ': "ucm:' . $id . '", ' . __('Title') . ': ' . $menu[0],
            $frm->checkbox('delete[' . $id . ']', '1', __('Delete')) . ' ' .
            $frm->radio_button('edit', array($id => __('Edit')))
        );
    }
    $frm->show();
}
?>
