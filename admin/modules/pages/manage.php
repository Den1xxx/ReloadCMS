<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
//Initialize
if (!defined('PAGES_PATH')) define ('PAGES_PATH',DATA_PATH . 'pages/');
if (!is_dir(PAGES_PATH)) mkdir(PAGES_PATH,0777);

//Create page
function page_create($id, $mode='html') {
    global $system;
    $id = basename(trim($id));
	if(is_file(PAGES_PATH . $id )) return false;
    if(preg_replace("/[a-z0-9]*/i", '', $id) != '' || empty($id)) return false;
    $text = (empty($_POST['text'])) ? '' : $_POST['text'];
    $title = (empty($_POST['title'])) ? '' : htmlspecialchars($_POST['title']);
    $description = (empty($_POST['description'])) ? '' : htmlspecialchars($_POST['description']);
    $keywords = (empty($_POST['keywords'])) ? '' : htmlspecialchars($_POST['keywords']);
    $page = array(
	'title' => $title,
	'text' => $text,
	'description' => $description,
	'keywords' => $keywords,
	'mode' => $mode,
	'author_nick' => $system->user['nickname'],
	'author_name' => $system->user['username'],
	'date' => time()
	); 

    if(file_write_contents(PAGES_PATH . $id , serialize($page))){
        return true;
    } else return false;
}

//Return content of page
function page_get($id){
    $filename = basename($id);
    if(!is_file(PAGES_PATH . $filename)) return false;
    $file = unserialize(file_get_contents(PAGES_PATH . $filename));
    return $file;
}

//Change page
function page_change($id, $newid, $title, $text, $description, $keywords, $mode='html'){
    global $system;
    $id = basename($id);
    $newid = basename($newid);
    if(preg_replace("/[a-z0-9]*/i", '', $id) != '' || empty($id)) return false;
    if(preg_replace("/[a-z0-9]*/i", '', $newid) != '' || empty($newid)) return false;
    if(!is_file(PAGES_PATH . $id )) return false;
    if($id != $newid && is_file(PAGES_PATH . $newid)) return false;
	$page = array(
	'title' => $title,
	'text' => $text,
	'description' => $description,
	'keywords' => $keywords,
	'mode' => $mode,
	'author_nick' => $system->user['nickname'],
	'author_name' => $system->user['username'],
	'date' => time()
	);
    if(!file_write_contents(PAGES_PATH . $id ,serialize($page))) return false;
    rcms_rename_file(PAGES_PATH . $id , PAGES_PATH . $newid );
    return true;
}

//API
$result = '';
if(!empty($_POST['delete']) && is_array($_POST['delete'])) {
    foreach ($_POST['delete'] as $id => $cond){
        if($cond){
            if(rcms_delete_files(DATA_PATH . 'pages/' . $id)) {
                $result .= __('Article removed')  . ': ' . $id . '<br />';
            } else {
                $result .= __('Error occurred')  . ': ' . $id . '<br />';
            }
        }
    }
    unset($_POST['edit']);

} elseif (!empty($_POST['newsave'])) {
    if(page_create($_POST['id'], $_POST['mode'])){
        $result .= __('Article saved');
    } else {
        $result .= __('Error occurred');
    }
} elseif (!empty($_POST['edit']) && !empty($_POST['save'])) {
    if(page_change($_POST['edit'], $_POST['id'], $_POST['title'], $_POST['text'],$_POST['description'],$_POST['keywords'], $_POST['mode'])){
        $result .= __('File updated');
        $_POST['edit'] = $_POST['id'];
    } else {
        $result .= __('Error occurred');
    }
}
rcms_showAdminMessage($result);

// Interface generation 
if(!empty($_POST['new'])){
	$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'add');
    $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
    $frm->addbreak(__('Post article'));
    $frm->hidden('newsave', '1');
    $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', ''));
    $frm->addrow(__('Title'), $frm->text_box('title', ''));
    $frm->addrow(__('Description for search engines'), $frm->text_box('description', ''));
    $frm->addrow(__('Keywords'), $frm->text_box('keywords', ''));
	$frm->addrow('', rcms_show_bbcode_panel('add.text'));
	$frm->addrow(__('Text'), $frm->textarea('text', '', 70, 25), 'top');
$frm->addrow(__('Mode'), $frm->select_tag('mode', array('html' => __('HTML'), 'text' => __('Text'), 'htmlbb' => __('bbCodes') . '+' . __('HTML'), 'php' => __('PHP')), 'text','onchange="if (this.options[selectedIndex].value==\'html\') { 	
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
		$(\'table.bb_editor\').hide();} else {
		tinyMCE.get(\'text\').hide();
		$(\'table.bb_editor\').show();
		}"'), 'top');
    $frm->show();
} elseif(!empty($_POST['edit'])){
    if($page = page_get($_POST['edit'])){
		$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'edit');
if (!empty($_GET['page']))  $frm->addmessage('&lt;&lt;&lt; <a href="'.RCMS_ROOT_PATH.'?module=pages&id='.$_GET['page'].'">'.__('Return to').' '.__('site index').'</a>');
else  $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
        $frm->addbreak(__('Edit article'));
        $frm->hidden('edit', $_POST['edit']);
        $frm->hidden('save', '1');
if (empty($_GET['page'])) $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', $_POST['edit']));
else $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $_GET['page'].$frm->hidden('id', $_GET['page']));
        $frm->addrow(__('Title'), $frm->text_box('title', $page['title']));
    if (empty ($page['description'])) $page['description'] = $page['title'];
	$frm->addrow(__('Description for search engines'), $frm->text_box('description', $page['description']));
    if (empty ($page['keywords'])) $page['keywords'] = '';
    $frm->addrow(__('Keywords'), $frm->text_box('keywords', $page['keywords']));
			$frm->addrow('', rcms_show_bbcode_panel('edit.text'));
			$frm->addrow(__('Text'), $frm->textarea('text', $page['text'], 70, 25), 'top');
			if ($page['mode']=='html'){
			?>
			<script type="text/javascript">
			tinyMCE.init({
		mode : 'exact',
		elements : 'text',
		theme : 'advanced',
		language : 'ru',
        plugins : 'paste,table',
        theme_advanced_buttons2_add : 'pastetext,pasteword,selectall',
        theme_advanced_buttons3_add : 'tablecontrols',
		theme_advanced_toolbar_location : 'top',
        theme_advanced_toolbar_align : 'left',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        paste_auto_cleanup_on_paste : true,
		content_css: '/css/tinymce.css',
		extended_valid_elements : 'script[type|language|src]',
		forced_root_block : '', 
		force_br_newlines : true,
		force_p_newlines : false
		});
		</script>
			<?
			}
			$frm->addrow(__('Mode'), $frm->select_tag('mode', array('html' => __('HTML'), 'text' => __('Text'), 'htmlbb' => __('bbCodes') . '+' . __('HTML'), 'php' => __('PHP')), $page['mode'],'onchange="if (this.options[selectedIndex].value==\'html\') { 	
		tinyMCE.init({
		mode : \'exact\',
		elements : \'text\',
		theme : \'advanced\',
		language : \'ru\',
        plugins : \'paste,table\',
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
		tinyMCE.get(\'text\').hide();
		$(\'table.bb_editor\').show();
		}"'), 'top');

        $frm->show();
    } else rcms_showAdminMessage(__('Cannot open menu for editing'));
} else {
    $frm = new InputForm ('', 'post', __('Post article')); 
	$frm->addbreak(__('Create static pages'));
	$frm->hidden('new', '1'); 
	$frm->show();
    $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
    $frm->addbreak(__('List of articles'));
    $menus = rcms_scandir(DATA_PATH . 'pages/');
    foreach ($menus as $id => $menu){
	        $frm->addrow(__('Article') . ':  <a href="'.RCMS_ROOT_PATH.'?module=pages&amp;id=' . $menu . '" target="_blank">'.
			$menu .'</a>  ',
            $frm->checkbox('delete[' . $menu . ']', '1', __('Delete')) . ' ' .
            $frm->radio_button('edit', array($menu => __('Edit')))
			);
    }
    $frm->show();
}


?>