<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
define('RCMS_ROOT_PATH', './');

include(RCMS_ROOT_PATH . 'common.php');

//load files
if (get('download')&&$system->checkForRight('GENERAL')) {
$file=base64_decode(get('download'));
download_file($file);	
}

function rcms_loadAdminLib($lib){
	require_once(ADMIN_PATH . 'libs/' . $lib . '.php');
}

//------------------------------------------------------------------------------------------------------//
// preparations...
if (post('admin_selected_skin')) {
setcookie('admin_skin',$_POST['admin_selected_skin'], FOREVER_COOKIE);
rcms_redirect('');
}
$admin_skin = cookie('admin_skin','default');
$admin_skin = cut_text(preg_replace("/[^a-zA-Z]+/",'',$admin_skin));//only letters
define('ADMIN_SKIN', ADMIN_PATH.'skins/'.$admin_skin.'/');
$rights = &$system->rights;
$root   = &$system->root;
if(!LOGGED_IN){
	$message = __('Access denied');
	$message .= '<br />
<form method="post" action="">
<input type="hidden" name="login_form" value="1" />
<table cellpadding="2" cellspacing="1" style="width: 100%;">
<tr>
    <td class="row1">' . __('Username') . ':</td>
    <td class="row1" style="width: 100%;"><input type="text" name="username" style="text-align: left; width: 95%;" /></td>
</tr>
<tr>
    <td class="row1">' . __('Password') . ':</td>
    <td class="row1" style="width: 100%;"><input type="password" name="password" style="text-align: left; width: 95%;" /></td>
</tr>
<tr>
    <td class="row1" colspan="2">
        <input type="checkbox" name="remember" id="remember" value="1" />
        <label for="remember">' . __('Remember me') . '</label>
    </td>
</tr>
<tr>
    <td class="row2" colspan="2"><input type="submit" value="' . __('Log in') . '" /></td>
</tr>
</table>
</form>';
	include(ADMIN_PATH . 'error.php');
} elseif (empty($rights) && !$root) {
    $message = __('You are not administrator of this site');
	include(ADMIN_PATH . 'error.php');
} else {

	$categories = rcms_scandir(ADMIN_PATH . 'modules', '', 'dir');
	$MODULES = array();
	foreach ($categories as $category){
		if(file_exists(ADMIN_PATH . 'modules/' . $category . '/module.php')){
	    	include_once(ADMIN_PATH . 'modules/' . $category . '/module.php');
		}
	}
	$title = __('Administration');
	if (!empty($_GET['id'])) {
	$arr=explode('.',$_GET['id']);
	$title.=' - '.$MODULES[$arr[0]][0];
	}  
 require_once(ADMIN_SKIN . 'skin.general.php');
}
?>
