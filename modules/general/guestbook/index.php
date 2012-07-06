<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

$config = parse_ini_file(CONFIG_PATH . 'guestbook.ini');
$sysconfig = parse_ini_file(CONFIG_PATH . 'config.ini');

$page = ((!empty($_GET['page'])) ? (int)$_GET['page'] : 1) - 1;
$pages = guestbook_get_pages_num();
$pagination = rcms_pagination($pages * $system->config['perpage'], $system->config['perpage'], $page + 1, '?module=' . $module);

if(!empty($_POST['comtext'])) {
	if (isset($sysconfig['guestbook-guest']) and !LOGGED_IN){
		 show_window(__('Error'),__('You are not logined!'));
	} else {
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
    	post_message($system->user['username'], $system->user['nickname'], $_POST['comtext']);
		echo '<script type="text/javascript">document.location.href=document.location.href;</script>';
}
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {
    	post_message($system->user['username'], $system->user['nickname'], $_POST['comtext']);
		echo '<script type="text/javascript">document.location.href=document.location.href;</script>';
}
    }
}

if((!empty($_POST['gbd']) || @$_POST['gbd'] === '0') && $system->checkForRight('GUESTBOOK')) {
    guestbook_post_remove($_POST['gbd']);
}

if (!(isset($sysconfig['guestbook-guest']) and !LOGGED_IN)) {
	show_window(__('Post message'), rcms_parse_module_template('gb-form.tpl', @$config['bbcodes']), 'center');
} else {
	show_window(__('Post comment'), __('You are not logined!'), 'center');
}

$messages = guestbook_get_msgs($page, true, @!$config['bbcodes']);
if(!empty($pagination)) show_window('', $pagination, 'center');
foreach ($messages as $id => $message) {
    $message['id'] = $id;
	show_window('', rcms_parse_module_template('gb-mesg.tpl', $message), 'center');
}

$system->config['pagename'] = __('Guest book');
?>
