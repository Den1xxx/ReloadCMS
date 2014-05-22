<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

/*
* Minichat
*
* Module type of menu
*
* @minichat_config array Minichat configuration
*/

$minichat_config = parse_ini_file(CONFIG_PATH . 'minichat.ini');

if(!LOGGED_IN && empty($minichat_config['allow_guests_view'])) return false;

/*
* If user posted comment
*/
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

/*
* If admin delete comment
*/
	if(isset($_POST['mcdelete']) && $system->checkForRight('MINICHAT')) {
	post_remove($_POST['mcdelete'], RCMS_MC_DEFAULT_FILE);
	rcms_redirect('');
	}

/*
* Minichat post form
*/
	$result = '';
	if(LOGGED_IN || $minichat_config['allow_guests_post']) {

/*
* BB-codes editor in minichat — for really kamikaze
*/
	if (!empty($minichat_config['editor'])) $result .= rcms_show_bbcode_panel('minichat.mctext').'<br />';
	$result .= rcms_parse_module_template('minichat-form.tpl', array('allow_guests_enter_name' => $minichat_config['allow_guests_enter_name']));
	}

/*
* Minichat comments
*/
	$list = get_last_messages($minichat_config['messages_to_show'], true, false, RCMS_MC_DEFAULT_FILE, 'minichat.ini');

	foreach ($list as $id => $message){
		$message['id'] = $id;
		$result .= rcms_parse_module_template('minichat-mesg.tpl', $message);
	}
	
/*
* Show all
*/
	$result = '<div style="overflow-x: hidden; overflow-y: auto; width: 100%">' . $result . '</div>';
	show_window(__('Minichat'), $result, 'center');

?>
