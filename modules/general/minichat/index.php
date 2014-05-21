<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$minichat_config = parse_ini_file(CONFIG_PATH . 'minichat.ini');

if(!LOGGED_IN && !$minichat_config['allow_guests_view']) {
} else {
	if(!empty($_POST['mctext']) && (LOGGED_IN || $minichat_config['allow_guests_post'])) {
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
		$username = $system->user['username'];
		$nickname = $system->user['nickname'];
		post_message($username, $nickname, $_POST['mctext'], RCMS_MC_DEFAULT_FILE, 'minichat.ini');
		rcms_redirect('');
}
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {
		$username = $system->user['username'];
		$nickname = $system->user['nickname'];
		post_message($username, $nickname, $_POST['mctext'], RCMS_MC_DEFAULT_FILE, 'minichat.ini');
		rcms_redirect('');
}
}

	if(isset($_POST['mcdelete']) && $system->checkForRight('MINICHAT')) {
//	var_dump($minichat_config,$_POST);
	post_remove($_POST['mcdelete'], RCMS_MC_DEFAULT_FILE);
		//rcms_redirect('');
	}

	$result = '';
	if(LOGGED_IN || $minichat_config['allow_guests_post']) {
	if (!empty($minichat_config['editor'])) $result .= rcms_show_bbcode_panel('minichat.mctext').'<br />';
	$result .= rcms_parse_module_template('minichat-form.tpl', array('allow_guests_enter_name' => $minichat_config['allow_guests_enter_name']));
	}

	$list = get_last_messages($minichat_config['messages_to_show'], true, false, RCMS_MC_DEFAULT_FILE, 'minichat.ini');

	foreach ($list as $id => $message){
		$message['id'] = $id;
		$result .= rcms_parse_module_template('minichat-mesg.tpl', $message);
	}

	$result = '<script type="text/javascript" src="' . RCMS_ROOT_PATH . 'tools/js/minmax.js"></script><div style="overflow-x: hidden; overflow-y: auto; width: 100%">' . $result . '</div>';
	show_window(__('Minichat'), $result, 'center');
}
?>
