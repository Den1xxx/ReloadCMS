<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
define('RCMS_ROOT_PATH', './');

include(RCMS_ROOT_PATH . 'common.php');

function rcms_loadAdminLib($lib){
	require_once(ADMIN_PATH . 'libs/' . $lib . '.php');
}

//------------------------------------------------------------------------------------------------------//
// preparations...

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
?>
<!doctype html>
<html>
<head>     
    <title><?=__('Administration')?></title>
    <?rcms_show_element('meta')?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$system->config['encoding']?>">
<link rel="stylesheet" href="<?=ADMIN_PATH?>style.css" type="text/css">
<style type="text/css">
#layout-center{
	position: absolute;
	left: 210px;
	top: 0px;
	bottom: 0px;
	right: 0px;
	height: 100%;
	overflow-y:auto;
}
#layout-left{
	position: absolute;
	left: 0px;
	top: 0px;
	bottom: 0px;
	right: 0px;
	width: 210px;
	height: 100%;
	overflow-y:auto;
}
</style>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
</head>
<body>
    <div id="layout-center">
	<?php
	if(!empty($_GET['show'])) {
			$module = (!empty($_GET['id'])) ? basename($_GET['id']) : '.index';
			$module = explode('.', $module, 2);
			if(!is_file(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php')) {
				$message = __('Module not found') . ': ' . $module[0] . '/' . $module[1];
				include(ADMIN_PATH . 'error.php');
			} elseif($module[1] != 'index' && empty($MODULES[$module[0]][1][$module[1]])) {
				$message = __('Access denied') . ': ' . $module[0] . '/' . $module[1];
				include(ADMIN_PATH . 'error.php');
			} else include(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php');
		} else include(ADMIN_PATH . 'modules/index.php');
	?>   
	</div>
    <div id="layout-left">
		<?php include(ADMIN_PATH . 'navigation.php'); ?>
    </div>
</body>
</html>	
<?}?>
