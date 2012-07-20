<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Initializations                                                            //
define('RCMS_ROOT_PATH', './');
require_once(RCMS_ROOT_PATH . 'common.php');
mb_internal_encoding($system->config['encoding']);
 
//API
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
include_once(CUR_SKIN_PATH . 'skin.php');
if(!empty($menu_points)){
   	foreach($menu_points as $point => $menus){
       	$system->setCurrentPoint($point);
       	if(!empty($menus) && isset($skin['menu_point'][$point])){
           	foreach ($menus as $menu){
               	if(substr($menu, 0, 4) == 'ucm:' && is_readable(DF_PATH . substr($menu, 4) . '.ucm')) {
                   	$file = file(DF_PATH . substr($menu, 4) . '.ucm');
                   	$title = preg_replace("/[\n\r]+/", '', $file[0]);
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