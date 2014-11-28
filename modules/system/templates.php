<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

function user_tz_select($default = 0, $select_name = 'timezone') {
	global $lang;

	$tz_select = '<select name="' . $select_name . '">';
	while(list($offset, $zone) = @each($lang['tz'])) {
		$selected = ( $offset == $default ) ? ' selected="selected"' : '';
		$tz_select .= '<option value="' . $offset . '"' . $selected . '>' . $zone . '</option>';
	}
	$tz_select .= '</select>';

	return $tz_select;
}

function user_skin_select($dir, $select_name, $default = '', $style = '', $script = '') {
    $skins = rcms_scandir($dir);
    $frm = '<select name="' . $select_name . '" style="' . $style . '" ' . $script . '>';
    foreach ($skins as $skin){
        if(is_dir($dir . $skin) && is_file($dir . $skin . '/skin_name.txt')){
            $name = file_get_contents($dir . $skin . '/skin_name.txt');
            $frm .= '<option value="' . $skin . '"' . (($default == $skin) ? ' selected="selected">' : '>') . $name . '</option>';
        }
    }
    $frm .= '</select>';
    return $frm;
}

function user_lang_select($select_name, $default = '', $style = '', $script = '') {
    global $system;
    $frm = '<select name="' . $select_name . '" style="' . $style . '" ' . $script . '>';
    foreach ($system->data['languages'] as $lang_id => $lang_name){
        $frm .= '<option value="' . $lang_id . '"' . (($default == $lang_id) ? ' selected="selected">' : '>') . $lang_name . '</option>';
    }
    $frm .= '</select>';
	return $frm;
}

function rcms_pagination($total, $perpage, $current, $link){
    $return = '';
    $link = preg_replace("/((&amp;|&)page=(\d*))/", '', $link);
    if(!empty($perpage)) {
	$arr=array(
	'total'=>$total,
	'perpage'=>$perpage,
	'current'=>$current,
	'link'=>$link
	);
	$return = rcms_parse_module_template('pagination.tpl',$arr);
    }
    return $return;
}

function show_icon ($module) {
return ((is_file(IMAGES_PATH.'icons/'.$module.'.png'))?
'<img src="'.IMAGES_PATH.'icons/'.$module.'.png" alt="'.$module.'"/>':
'<img src="'.IMAGES_PATH.'icons/default.png " alt="'.$module.'"/>');
}

function rcms_parse_menu($format) {
	global $system;
	$navigation = parse_ini_file(CONFIG_PATH . 'navigation.ini', true);
	$dyna = parse_ini_file(CONFIG_PATH . 'dynamik.ini', true);
	$result = array();
	foreach ($navigation as $link) {
		if(substr($link['url'], 0, 9) == 'external:') {
			$target = '_blank';
			$link['url'] = substr($link['url'], 9);
		} else {
			$target = '';
		}
		$tdata = explode(':', $link['url'], 2);
		if(count($tdata) == 2){
			list($modifier, $value) = $tdata;
		} else {
			$modifier = $tdata[0];
		}
		if(!empty($value) && !empty($system->navmodifiers[$modifier])){
			if($clink = call_user_func($system->navmodifiers[$modifier]['m'], $value)){
				$result[] = array($clink[0], (empty($link['name'])) ? $clink[1] : __($link['name']), $target, '');
			}
		} else {
			$result[] = array($link['url'], __($link['name']));
		}
	}
	if ($system->checkForRight('GENERAL')) $result[]=array(ADMIN_FILE.'?show=module&id=add.navigation&tab=1','<img src="'.IMAGES_PATH.'skins/edit_small.gif" title="'.__('Edit').'">');
	$menu = '';
	foreach ($result as $item){
		if(empty($item[2])) {
			$item[2] = '_top';
		}
		if (isset($dyna['ico'])) {		//enable icons?
		$icon = str_replace('?module=', '', $item[0]).'.png';
		if (is_file(IMAGES_PATH.'icons/'.$icon)) 
		$item[3] = '<img src="'.IMAGES_PATH.'icons/'.$icon.'" alt="'.@$item[3].'"/>';
		else 	$item[3] = '<img src="'.IMAGES_PATH.'icons/default.png " alt="'.@$item[3].'"/>';
		}
		else $item[3]='';
		$menu .= str_replace(array('{link}','{title}','{target}','{icon}'),$item,$format);
	}
	$result = $menu	;
	return $result;
}

function rcms_parse_module_template($module, $tpldata = array()) {
    global $system;
    ob_start();
    if(is_file(CUR_SKIN_PATH . $module . '.php')) {
        include(CUR_SKIN_PATH . $module . '.php');
    } elseif(is_file(MODULES_TPL_PATH . $module . '.php')) {
        include(MODULES_TPL_PATH . $module . '.php');
    }
    $return = ob_get_contents();
    ob_end_clean();
    return $return;
}

function rcms_open_browser_window($id, $link, $attributes = '', $return = false){
	global $system;
	$code = '<script type="text/javascript">window.open(\'' . addslashes($link) . '\', \'' . $id . '\',\'' . $attributes . '\');</script>';
	if($return){
		return $code;
	} else {
		@$system->config['meta'] .= $code;
	}
}

function rcms_parse_module_template_path($module) {
    if(is_file(CUR_SKIN_PATH . $module . '.php')) {
        return (CUR_SKIN_PATH . $module . '.php');
    } elseif(is_file(MODULES_TPL_PATH . $module . '.php')) {
        return (MODULES_TPL_PATH . $module . '.php');
    } else {
        return false;
    }
}

function rcms_show_element($element, $parameters = ''){
    global $system,$lightbox_config;
    switch($element){
        case 'title':
            if(empty($system->config['hide_title'])) echo $system->config['title'];
            echo (!empty($system->config['pagename'])) ? ' - '.$system->config['pagename'] : '';
            break;
        case 'slogan':
                if(!empty($system->config['slogan'])) echo $system->config['slogan'];
            break;
        case 'menu_point':
            list($point, $template) = explode('@', $parameters);
    		if(is_file(CUR_SKIN_PATH . 'skin.' . $template . '.php')) {
        		$tpl_path = CUR_SKIN_PATH . 'skin.' . $template . '.php';
    		} elseif(is_file(MODULES_TPL_PATH . $template . '.php')) {
        		$tpl_path = MODULES_TPL_PATH . $template . '.php';
    		}
            if(!empty($tpl_path) && !empty($system->output['menus'][$point])){
                foreach($system->output['menus'][$point] as $module){
                    $system->showWindow($module[0], $module[1], $module[2], $tpl_path);
                }
            }
            break;
        case 'main_point':
            foreach ($system->output['modules'] as $module) {
                $system->showWindow($module[0], $module[1], $module[2], CUR_SKIN_PATH . 'skin.' . substr(strstr($parameters, '@'), 1) . '.php');
            }
            break;
        case 'navigation':
				$nav = rcms_parse_menu($parameters);
				$nav = str_replace('id="{id}"','',$nav);
				echo $nav;
            break;
        case 'meta':
            if (!function_exists('rcms_loadAdminLib')) @readfile(DATA_PATH . 'meta_tags.html');
            echo '<meta http-equiv="Content-Type" content="text/html; charset=' . $system->config['encoding'] . '" />' . "\r\n";
            if(!empty($system->config['enable_rss'])){
                foreach ($system->feeds as $module => $d) {
                    echo '<link rel="alternate" type="application/rss+xml" title="RSS ' . $d[0] . '" href="' . RCMS_ROOT_PATH . 'rss.php?m=' . $module . '" />' . "\r\n";
                }
            }
echo '
<link type="text/css" href="' . SKIN_PATH . 'main.css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src="' . RCMS_ROOT_PATH . 'tools/js/vendor/jquery.1.8.3.min.js"><\/script>");</script>
<link rel="stylesheet" href="' . RCMS_ROOT_PATH . 'tools/js/vendor/jquery-ui.base.1.9.2.css">
<script src="' . RCMS_ROOT_PATH . 'tools/js/vendor/jquery-ui.1.9.2.min.js"></script>
<script type="text/javascript" src="' . RCMS_ROOT_PATH . 'tools/js/ajaxupload.js"></script>
<script type="text/javascript" src="' . RCMS_ROOT_PATH . 'tools/js/editor.js"></script>
';
if (!empty($lightbox_config['code'])) echo $lightbox_config['code'];
echo '<script type="text/javascript" src="tools/js/limit.js"></script>'. "\r\n";        //  Limit post addon 
echo '<script type="text/javascript" src="tools/js/tiny_mce/tiny_mce.js"></script>';
            if(!empty($system->config['meta'])) echo $system->config['meta'];
            break;
        case 'copyright':
            if(!defined('RCMS_COPYRIGHT_SHOWED') || !RCMS_COPYRIGHT_SHOWED){
                echo RCMS_POWERED . ' ' . RCMS_VERSION_A . '.'  . RCMS_VERSION_B . '.' . RCMS_VERSION_C . RCMS_VERSION_SUFFIX . '<br />' . RCMS_COPYRIGHT;
            }
            break;
    }
}
?>
