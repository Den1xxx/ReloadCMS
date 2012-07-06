<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
?>

<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language : "ru",
        plugins : "paste,table",
        theme_advanced_buttons2_add : "pastetext,pasteword,selectall",
        theme_advanced_buttons3_add : "tablecontrols",
		theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
        paste_auto_cleanup_on_paste : true,
		content_css: "/css/tinymce.css",
		extended_valid_elements : "script[type|language|src]",
		forced_root_block : "", 
		force_br_newlines : true,
		force_p_newlines : false
		});
</script>

<?
//Initialize
if (!defined('SENDMAIL_PATH')) define ('SENDMAIL_PATH',DATA_PATH . 'sendmail/');
if (!is_dir(SENDMAIL_PATH)) mkdir(SENDMAIL_PATH,0777);

//Create page
function tpl_create($id, $mode='html') {
    $id = basename(trim($id));
	if(is_file(SENDMAIL_PATH . $id )) return false;
    if(preg_replace("/[a-z0-9]*/i", '', $id) != '' || empty($id)) return false;
    $title = (empty($_POST['title'])) ? '' : $_POST['title'];
    $comment = (empty($_POST['comment'])) ? '' : $_POST['comment'];
    $sender_name = (empty($_POST['sender_name'])) ? '' : $_POST['sender_name'];
    $sender_email = (empty($_POST['sender_email'])) ? '' : $_POST['sender_email'];
    $subject = (empty($_POST['subject'])) ? '' : $_POST['subject'];
    $letter = (empty($_POST['letter'])) ? '' : $_POST['letter'];
    $important = (empty($_POST['important'])) ? '' : $_POST['important'];
    $important_text = (empty($_POST['important_text'])) ? '' : $_POST['important_text'];
    $page = array(
	'title' => $title,
	'comment'=>$comment,
	'sender_name'=>$sender_name,
	'sender_email'=>$sender_email,
	'subject'=>$subject,
	'letter'=>$letter,
	'important'=>$important,
	'important_text'=>$important_text
	); 

    if(file_write_contents(SENDMAIL_PATH . $id , serialize($page))){
        return true;
    } else return false;
}

//Return content of template
function tpl_get($id){
    $filename = basename($id);
    if(!is_file(SENDMAIL_PATH . $filename)) return false;
    $file = unserialize(file_get_contents(SENDMAIL_PATH . $filename));
    return $file;
}

//Change template
function tpl_change($id, $newid, $title, $comment, $sender_name, $sender_email, $subject, $letter, $important, $important_text){
    global $system;
    $id = basename($id);
    $newid = basename($newid);
    if(preg_replace("/[a-z0-9]*/i", '', $id) != '' || empty($id)) return false;
    if(preg_replace("/[a-z0-9]*/i", '', $newid) != '' || empty($newid)) return false;
    if(!is_file(SENDMAIL_PATH . $id )) return false;
    if($id != $newid && is_file(SENDMAIL_PATH . $newid)) return false;
	$page = array(
	'title' => $title,
	'comment'=>$comment,
	'sender_name'=>$sender_name,
	'sender_email'=>$sender_email,
	'subject'=>$subject,
	'letter'=>$letter,
	'important'=>$important,
	'important_text'=>$important_text
	);
    if(!file_write_contents(SENDMAIL_PATH . $id ,serialize($page))) return false;
    rcms_rename_file(SENDMAIL_PATH . $id , SENDMAIL_PATH . $newid );
    return true;
}

//API
$result = '';
if(!empty($_POST['delete']) && is_array($_POST['delete'])) {
    foreach ($_POST['delete'] as $id => $cond){
        if($cond){
            if(rcms_delete_files(SENDMAIL_PATH . $id)) {
                $result .= __('File removed')  . ': ' . $id . '<br />';
            } else {
                $result .= __('Error occurred')  . ': ' . $id . '<br />';
            }
        }
    }
    unset($_POST['edit']);

} elseif (!empty($_POST['newsave'])) {
    if(tpl_create($_POST['id'])){
        $result .= __('Module created');
    } else {
        $result .= __('Error occurred');
    }
} elseif (!empty($_POST['edit']) && !empty($_POST['save'])) {
    if(tpl_change(
	$_POST['edit'],
	$_POST['id'],
	$_POST['title'],
	$_POST['comment'],
	$_POST['sender_name'],
	$_POST['sender_email'],
	$_POST['subject'],
	$_POST['letter'],
	$_POST['important'],
	$_POST['important_text']
	)){
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
    $frm->addbreak(__('Create sendmail template'));
    $frm->hidden('newsave', '1');
    $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', ''));
    $frm->addrow(__('Title'), $frm->text_box('title', __('The letter to admin'),45));
    $frm->addrow(__('Comments').': ', $frm->textarea('comment', ''), 55, 15);
    $frm->addrow(__('Sender name'), $frm->text_box('sender_name', __('Your name'),45));
    $frm->addrow(__('Sender email'), $frm->text_box('sender_email', __('Your e-mail for answer'),45));
    $frm->addrow(__('Subject'), $frm->text_box('subject', __('Subject of your letter'),45));
    $frm->addrow(__('Text'), $frm->text_box('letter', __('Text of your letter'),45));
    $frm->addrow(__('Important'), $frm->text_box('important', __('It is important!')));
    $frm->addrow(__('Important').': '.__('Text'), $frm->textarea('important_text', __('Your letter will be sent to admin and it will not be kept on a site. Admin will answer to you as soon as he will have a possibility.'), 55, 15));
    $frm->show();
} elseif(!empty($_POST['edit'])){
    if($page = tpl_get($_POST['edit'])){
		$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'edit');
        $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
        $frm->addbreak(__('Edit sendmail template'));
        $frm->hidden('edit', $_POST['edit']);
        $frm->hidden('save', '1');
        $frm->addrow('<abbr title="' . __('Use only small Latin letters and digits') . '">' . __('MenuID') . '</abbr>', $frm->text_box('id', $_POST['edit']));
		$frm->addrow(__('Title'), $frm->text_box('title', $page['title'],45));
		$frm->addrow(__('Comments').': ', $frm->textarea('comment', $page['comment'], 55, 15));
		$frm->addrow(__('Sender name'), $frm->text_box('sender_name', $page['sender_name'],45));
		$frm->addrow(__('Sender email'), $frm->text_box('sender_email', $page['sender_email'],45));
		$frm->addrow(__('Subject'), $frm->text_box('subject', $page['subject'],45));
		$frm->addrow(__('Text'), $frm->text_box('letter', $page['letter']));
		$frm->addrow(__('Important'), $frm->text_box('important', $page['important'],45));
		$frm->addrow(__('Important').': '.__('Text'), $frm->textarea('important_text', $page['important_text'], 55, 15));
		$frm->show();
    } else rcms_showAdminMessage(__('Cannot open template for editing'));
} else {
    $frm = new InputForm ('', 'post', __('Submit')); 
	$frm->addbreak(__('Build new'));
	$frm->hidden('new', '1'); 
	$frm->show();
    $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
    $frm->addbreak(__('List'));
    $menus = rcms_scandir(SENDMAIL_PATH);
    foreach ($menus as $id => $menu){
	        $frm->addrow(__('File') . ':  <a href="'.RCMS_ROOT_PATH.'?module=sendmail&amp;get=' . $menu . '" target="_blank">'.
			$menu .'</a>  ',
            $frm->checkbox('delete[' . $menu . ']', '1', __('Delete')) . ' ' .
            $frm->radio_button('edit', array($menu => __('Edit')))
			);
    }
    $frm->show();
}


?>