<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$config = parse_ini_file(CONFIG_PATH . 'minichat.ini');

if(!LOGGED_IN && !$config['allow_guests_view']) {
} else {
	if(!empty($_POST['mctext']) && (LOGGED_IN || $config['allow_guests_post'])) {
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
		$username = $system->user['username'];
		$nickname = $system->user['nickname'];
		post_message($username, $nickname, $_POST['mctext'], RCMS_MC_DEFAULT_FILE, 'minichat.ini');
		echo '<script type="text/javascript">document.location.href=document.location.href;</script>';
}
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {
		$username = $system->user['username'];
		$nickname = $system->user['nickname'];
		post_message($username, $nickname, $_POST['mctext'], RCMS_MC_DEFAULT_FILE, 'minichat.ini');
		echo '<script type="text/javascript">document.location.href=document.location.href;</script>';
}
	}

	if((!empty($_POST['mcdelete']) || @$_POST['mcdelete'] === '0') && $system->checkForRight('MINICHAT')) {
		post_remove((int)$_POST['mcdelete'], RCMS_MC_DEFAULT_FILE, 'minichat.ini');
	}

	$result = '';
	if(LOGGED_IN || $config['allow_guests_post']) {
		$result .= rcms_parse_module_template('minichat-form.tpl', array('allow_guests_enter_name' => $config['allow_guests_enter_name']));
	}

	$list = get_last_messages($config['messages_to_show'], true, true, RCMS_MC_DEFAULT_FILE, 'minichat.ini');
	foreach ($list as $message_id => $message){
		$result .= rcms_parse_module_template('minichat-mesg.tpl', array('id' => $message_id) + $message);
	}

	$result = '<script type="text/javascript" src="' . RCMS_ROOT_PATH . 'tools/js/minmax.js"></script><div style="overflow-x: hidden; overflow-y: auto; max-height: 450px; width: 100%">' . $result . '</div>';
	show_window(__('Minichat'), $result, 'center');
}
?>
