<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Initializations                                                            //
define('RCMS_ROOT_PATH', './');
/*if (date_default_timezone_set(date_default_timezone_get()) === FALSE)
    date_default_timezone_set('Europe/Moscow');
*/
require_once(RCMS_ROOT_PATH . 'common.php');
mb_internal_encoding($system->config['encoding']);

//Main function
function rcms_start(){
global $menu_points, $starttime, $system;
$menu_points = parse_ini_file(CONFIG_PATH . 'menus.ini', true);

// Page gentime start 
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
// Send main headers
header('Last-Modified: ' . gmdate('r')); 
header('Content-Type: text/html; charset=' . $system->config['encoding']);
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); // HTTP/1.1 
header("Pragma: no-cache");
// Loading main module
$system->setCurrentPoint('__MAIN__');
if(!empty($_GET['module'])) $module = basename($_GET['module']); else $module = 'index';
if(!empty($system->modules['main'][$module])) include_once(MODULES_PATH . $module . '/index.php');

// Load menu modules
if (is_file(CONFIG_PATH.'logic.ini'))  $logic = unserialize(file_get_contents(CONFIG_PATH.'logic.ini'));
include_once(CUR_SKIN_PATH . 'skin.php');
if(!empty($menu_points)){
   	foreach($menu_points as $point => $menus){
       	$system->setCurrentPoint($point);
       	if(!empty($menus) && isset($skin['menu_point'][$point])){
           	foreach ($menus as $menu){
			if (isset($logic)&&is_array($logic)) {
				if (in_array($menu,$logic['modules'])){
				$expr=array_search($menu,$logic['modules']);
				$expression=trim($logic['expression'][$expr]);
				if (stristr($expression,"return")===false) $expression="return (" . $expression . ");";
				if(!eval($expression)) continue;
				}
			}
               	if(substr($menu, 0, 4) == 'ucm:' && is_readable(DF_PATH . substr($menu, 4) . '.ucm')) {
                   	$file = file(DF_PATH . substr($menu, 4) . '.ucm');
                   	$title = preg_replace("/[\n\r]+/", '', $file[0]);
                   	if($system->checkForRight('GENERAL'))  {
					$add = '&nbsp;<a href="'.ADMIN_FILE.'?show=module&id=modules.ucm&edit='.substr($menu, 4).'&tab=6" style="position:relative;z-index:999;" onclick="window.location=this.href"><img class="edit" src="'.IMAGES_PATH.'skins/edit_small.gif" title="'.__('Edit').'"></a>';
					if (!empty($title)) $title .= $add; 
					else $file[] = $add;
					}
                   	$align = preg_replace("/[\n\r]+/", '', $file[1]);
                   	unset($file[0]);
                   	unset($file[1]);
                   	show_window($title, implode('', $file), $align);
               	} elseif (!empty($system->modules['menu'][$menu])){
                   	$module = $menu;
                   	$module_dir = MODULES_PATH . $menu;
                   	require(MODULES_PATH . $menu . '/index.php');
               	} else {
                   	show_window('', __('Module not found').': '.$menu, 'center');
               	}
           	}
       	}
   	}
}

// Start output
 require_once(CUR_SKIN_PATH . 'skin.general.php');
 }
 
//Main process

if (post('lang_form')) rcms_redirect(RCMS_ROOT_PATH);

if (is_file(CONFIG_PATH.'redirect.ini')) {
$redirect = unserialize(file_get_contents(CONFIG_PATH.'redirect.ini'));
if (in_array('?'.$_SERVER['QUERY_STRING'],$redirect['from_arr'])) {
$key=array_search('?'.$_SERVER['QUERY_STRING'],$redirect['from_arr']);
if (!empty($redirect['to_arr'][$key])) {
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.$redirect['to_arr'][$key]);
exit(0);
}
}
}
if (isset($_GET['module'])) $mod = $_GET['module']; else $mod = '';
if (empty($system->config['disable_cache'])) $system->config['disable_cache'] = '';
if ($system->user['username'] == 'guest' && !empty($system->config['cache']) && !in_array($mod,explode("\n",str_replace("\r", '', $system->config['disable_cache'])))){
 $cache = new CACHE; 
 $cache -> start_cache();
 $cache -> time_file_cache = $system->config['cache'];//
 if (!$cache -> is_fresh())	{
 rcms_start();
 $cache -> save_cache();
 } 
 $cache -> clear_cache();
 if ($cache -> show_cache()) echo $cache -> CONTENT;
 } else {
 rcms_start();
 }
 ?>