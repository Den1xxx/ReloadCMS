<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

global $system;

$page = ((!empty($_GET['page'])) ? (int)$_GET['page'] : 1) - 1;
$pages = get_pages_number(RCMS_GB_DEFAULT_FILE);
$pagination = rcms_pagination($pages * $system->config['perpage'], $system->config['perpage'], $page + 1, '?module=' . $module);

if(!empty($_POST['comtext'])) {
	if (isset($system->config['guestbook-guest']) and !LOGGED_IN){
		 show_window(__('Error'),__('You are not logined!'));
	} else {
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
    	post_message($system->user['username'], $system->user['nickname'], $_POST['comtext'], RCMS_GB_DEFAULT_FILE, 'guestbook.ini');
		rcms_redirect('');
}
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {
    	post_message($system->user['username'], $system->user['nickname'], $_POST['comtext'], RCMS_GB_DEFAULT_FILE, 'guestbook.ini');
		rcms_redirect('');
}
    }
}


if(isset($_POST['gbd']) && $system->checkForRight('GUESTBOOK')) {
    post_remove($_POST['gbd'], RCMS_GB_DEFAULT_FILE);
	rcms_redirect('');
}

if (!(isset($system->config['guestbook-guest']) and !LOGGED_IN)) {
	show_window(__('Post message'), rcms_parse_module_template('gb-form.tpl', array()), 'center');
} else {
	show_window(__('Error'), __('You are not logined!'), 'center');
}

$messages = get_messages($page, true, false, RCMS_GB_DEFAULT_FILE, 'guestbook.ini' );
if(!empty($pagination)) show_window('', $pagination, 'center');
foreach ($messages as $id => $message) {
    $message['id'] = $id;
	show_window('', rcms_parse_module_template('gb-mesg.tpl', $message), 'center');
}

$system->config['pagename'] = __('Guest book');
?>
