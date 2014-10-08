<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['urls']) && !empty($_POST['names']) && is_array($_POST['urls']) && is_array($_POST['names'])){
	if(sizeof($_POST['urls']) !== sizeof($_POST['names'])){
		rcms_showAdminMessage(__('Error occurred'));
	} else {
		$result = array();
		foreach ($_POST['urls'] as $i => $url) {
			if(!empty($url)){
				if(!empty($_POST['ext'][$i])) {
					$ins['url'] = 'external:' . $url;
				} else {
					$ins['url'] = $url;
				}
				$ins['name'] = $_POST['names'][$i];
				$result[] = $ins;
			}
		}
		write_ini_file($result, CONFIG_PATH . 'navigation.ini', true) or rcms_showAdminMessage(__('Error occurred'));
	}
} 

if(!empty($_POST['dy'])) write_ini_file($_POST['dy'], CONFIG_PATH . 'dynamik.ini');

$links = parse_ini_file(CONFIG_PATH . 'navigation.ini', true);
$dyna = parse_ini_file(CONFIG_PATH . 'dynamik.ini', true);

$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'addnav');
$frm->addbreak(__('Menu options'));
$frm->addrow(__('Show icons'), $frm->checkbox('dy[ico]', '1', '', @$dyna['ico']));
$frm->addbreak(__('Navigation editor'));
$frm->addrow(__('Link'), __('Title'));
$i = 0;
$config = &$system->config;
$avaible_modules = array();
foreach ($system->modules['main'] as $module => $module_data){
	if ($module !== 'pm') {
		if ($module !== 'smiles') {
			if ($module !== 'search.result') {
			$avaible_modules[$module] = $module_data['title'];
			}
		}
	}
}
foreach ($links as $link){
	$tmp = explode(':', $link['url'], 2);
	$checked = $tmp[0] == 'external';
	if($checked){
		$link['url'] = $tmp[1];
	}
	$frm->addrow(
	$frm->text_box('urls[' . $i . ']', $link['url']), 
	'&nbsp;&darr;&uarr;&nbsp;'.$frm->text_box('names[' . $i . ']', $link['name'],20) . 
	'&nbsp;'. $frm->checkbox('ext[' . $i . ']', '1', __('Open in new window').'&nbsp;&nbsp;', $checked)
.$frm->select_tag('modules',$avaible_modules,-1,'onChange="
document.addnav[\''.'urls[' . $i . ']'.'\'].value = \'module:\'+ this.value;
">\n
	<option value="">'. __('Add link to module').'</option')
	.'<img src="'.IMAGES_PATH.'skins/neok.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;" onClick="$($(this).parents(\'tr\').get(0)).remove();">'	
	);
	$i++;
} 

$frm->addrow($frm->text_box('urls[' . $i . ']', ''), '&nbsp;&darr;&uarr;&nbsp;'.$frm->text_box('names[' . $i . ']', '',20).'&nbsp;'. $frm->checkbox('ext[' . $i . ']', '1', __('Open in new window').'&nbsp;&nbsp;')				
.$frm->select_tag('modules',$avaible_modules,-1,'onChange="
document.addnav[\''.'urls[' . $i . ']'.'\'].value = \'module:\'+ this.value;
">\n
	<option value="">'. __('Add link to module').'</option')
);
$frm->addmessage(__('If you want to remove link leave it\'s URL empty. If you want to add new item fill in the last row.'));
$frm->addmessage(__('You can use modifiers to create link to specified part of your site. Type MODIFIER:OPTIONS in "Link" column. If you want to override default title of modified link you must enter your title to "Title" column, or leave it empty to use default one. Here is a list of modifiers:'));
foreach ($system->navmodifiers as $modifier => $options){
	$frm->addrow($modifier, call_user_func($system->navmodifiers[$modifier]['h']));
}
$frm->show();
?>
<script type="text/javascript">
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
 
$("table tbody").sortable({
    helper: fixHelper
});
</script>