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
				$ins['desc'] = $_POST['desc'][$i];
				$result[] = $ins;
			}
		}
		write_ini_file($result, CONFIG_PATH . 'navigation.ini', true) or rcms_showAdminMessage(__('Error occurred'));
	}
} elseif (!empty($_POST['addlink']) && !empty($_POST['addlink']['url'])) {
	$links = parse_ini_file(CONFIG_PATH . 'navigation.ini', true);
	$links[] = $_POST['addlink'];
	write_ini_file($links, CONFIG_PATH . 'navigation.ini', true) or rcms_showAdminMessage(__('Error occurred'));
}

if(!empty($_POST['dy'])) write_ini_file($_POST['dy'], CONFIG_PATH . 'dynamik.ini');

$links = parse_ini_file(CONFIG_PATH . 'navigation.ini', true);
$dyna = parse_ini_file(CONFIG_PATH . 'dynamik.ini', true);

$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'addnav');
$frm->addbreak(__('Menu options'));
$frm->addrow(__('Show icons'), $frm->checkbox('dy[ico]', '1', '', @$dyna['ico']));
$frm->addbreak(__('Dynamik menu options'));
$frm->addrow(__('Use'), $frm->checkbox('dy[use]', '1', '', @$dyna['use']));
$frm->addrow(__('Min cascading'), $frm->checkbox('dy[min]', '1', '', @$dyna['min']));
$frm->addrow(__('Max subitems'), $frm->text_box('dy[max]',@$dyna['max']));
$frm->addrow(__('Off for ').'"'. __('Articles').'"',$frm->checkbox('dy[use_art]', '1', '', @$dyna['use_art']));
$frm->addrow(__('Off for ').'"'. __('Gallery').'"',$frm->checkbox('dy[use_gal]', '1', '', @$dyna['use_gal']));
$frm->addrow(__('Off for ').'"'. __('Member list').'"',$frm->checkbox('dy[use_mem]', '1', '', @$dyna['use_mem']));
$frm->addrow(__('Off for ').'"'. __('FilesDB').'"',$frm->checkbox('dy[use_fdb]', '1', '', @$dyna['use_fdb']));
$frm->addrow(__('Off for ').'"'. __('Forum').'"',$frm->checkbox('dy[use_for]', '1', '', @$dyna['use_for']));
$frm->addbreak(__('Navigation editor'));
$frm->addrow(__('Link'), __('Title').', '.__('Description'));
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
	$frm->text_box('urls[' . $i . ']', $link['url']).'&nbsp;&darr;&uarr;&nbsp;', 
	$frm->text_box('names[' . $i . ']', $link['name']) . 
	@$frm->text_box('desc[' . $i . ']', $link['desc']) . 
	//@$frm->textarea('desc[' . $i . ']', $link['desc'], 30, 1) .
	$frm->checkbox('ext[' . $i . ']', '1', __('Open in new window').'&nbsp;&nbsp;&nbsp;', $checked)
.$frm->select_tag('modules',$avaible_modules,-1,'onChange="
document.addnav[\''.'urls[' . $i . ']'.'\'].value = \'module:\'+ this.value;
">\n
	<option value="">'. __('Add link to module').'</option')	
	);
	$i++;
} 

$frm->addrow($frm->text_box('urls[' . $i . ']', ''), $frm->text_box('names[' . $i . ']', '') . $frm->text_box('desc[' . $i . ']', '') . $frm->checkbox('ext[' . $i . ']', '1', __('Open in new window').'&nbsp;&nbsp;&nbsp;')				
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