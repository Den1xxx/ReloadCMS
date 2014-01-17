<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
rcms_loadAdminLib('ucm');

if(!empty($_POST['save'])){
    $content = '';
    $i = -1;
    if(!empty($_POST['menus'])){
    	foreach ($_POST['menus'] as $element){
        	if(substr($element, 0, 1) == '/') {
	            $content .= '[' . substr($element, 1) . "]\n";
    	        $i = 0;
	        } elseif($i !== -1) {
            	$content .= $i . '=' . $element . "\n";
            	$i++;
        	}
    	}
    }
    file_write_contents(CONFIG_PATH . 'menus.ini', $content);
}

/******************************************************************************
* Interface                                                                   *
******************************************************************************/
$menus = parse_ini_file(CONFIG_PATH . 'menus.ini', true);
include(SKIN_PATH . $system->skin . '/skin.php');
$current = array();
$usused = array();
foreach ($menus as $column => $coldata){
    if(!empty($skin['menu_point'][$column])){
        $current['/' . $column] = __('Column') . ': ' . $skin['menu_point'][$column];
        foreach ($coldata as $menu){
            if(substr($menu, 0, 4) == 'ucm:' && is_readable(DF_PATH . substr($menu, 4) . '.ucm')) {
                $current[$menu] = ' > ' . $menu;
            } elseif (!empty($system->modules['menu'][$menu])) {
                $current[$menu] = ' > ' . $system->modules['menu'][$menu]['title'];
            }
        }
    }
}
foreach ($skin['menu_point'] as $column => $text) {
    if(!isset($current['/' . $column])) {
        $unused['/' . $column] = __('Column') . ': ' . $text;
    }
}

foreach ($system->modules['menu'] as $menu => $data) {
    if(!rcms_in_array_recursive(' > ' . $data['title'], $current)) {
        $unused[$menu] = ' > ' . $data['title'];
    }
}

$ucms = ucm_list();
foreach ($ucms as $menu=>$data) {
    if(!rcms_in_array_recursive(' > ucm:' . $menu, $current)) {
        $unused['ucm:' . $menu] = ' > ucm:' . $menu;
    }
}
?>
<script type="text/javascript" src="<?=ADMIN_PATH?>skins/slmv.js"></script>
<form name="form1" onsubmit="on_submit_prepare(document.form1.elements['menus[]'])" action="" method="POST">
<input type="hidden" name="save" value="1" />
<table cellpadding="2" cellspacing="1" border="0" align="center" width="100%">
<tr>
    <td valign="top" align="center" class='row1' width="45%">
        <select name="menus[]" size="15" style="width:100%" multiple>
            <?php foreach ($current as $element => $text) echo '<option value="' . $element . '">' . $text . '</option>';?>
        </select>
    </td>
    <td valign="top" align="center" class='row1'>
        <input type="button" name="left_up" value="<?=__('Up')?>" onclick="select_move_up_selected_el(document.form1.elements['menus[]'])">
        <input type="button" name="left_down" value="<?=__('Down')?>" onclick="select_move_down_selected_el(document.form1.elements['menus[]'])">
        <input type="button" name="add" value="« <?=__('Add')?>" onclick="add_to_select_from_another(document.form1.elements['usused'], document.form1.elements['menus[]'])">
        <input type="button" name="remove" value="<?=__('Delete')?> »" onclick="add_to_select_from_another(document.form1.elements['menus[]'], document.form1.elements['usused'])">
    </td>
    <td valign="top" align="center" class='row1' width="45%">
        <?=__('Unused modules')?>
        <select name="usused" size="14" style="width:100%">
            <?php foreach ($unused as $element => $text) echo '<option value="' . $element . '">' . $text . '</option>';?>
        </select>
    </td>
</tr>
<tr>
    <td colspan="3" align="center" class='row2'><?=__('You can define position of menu modules in this form. There are two type of elements in fields: "columns" (Element of this type is followed by menu modules, that will be show in this field, until another "column" element) and menu modules. Elements in second field and menus before first "column" in first field will not be used.')?></td>
</tr>
<tr>
    <td colspan="3" align="center" class='row2'><input type="submit" name="" value="<?=__('Submit')?>"></td>
</tr>
</table>
</form>
<?php
//Analog Widget Logic from WP
$select_menus="<select name=\'modules[]\' onChange=\'if ($(this).val()!=0) $(this).prev().val($(this).val());\'>"
."<option value=\'0\'>" . __('Select') . '</option>';
foreach ($current as $element => $text) {
if (substr($element , 0, 1) != '/') $select_menus.="<option value=\'" . $element . "\'>" . $text . '</option>';
}
$select_menus.='</select> ';

if (!empty($_POST['logic'])) {
//delete self host
$_POST['logic']['modules']=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/','',$_POST['logic']['modules']);
$_POST['logic']['modules']=str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$_POST['logic']['modules']);
//safe config to logicing
file_write_contents(CONFIG_PATH.'logic.ini',serialize(str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$_POST['logic'])));
} elseif (post('settingschange')) rcms_delete_files(CONFIG_PATH.'logic.ini');

if (is_file(CONFIG_PATH.'logic.ini')) $logic = unserialize(file_get_contents(CONFIG_PATH.'logic.ini'));

$frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'addlogic');
//logic
$add_logic ='<div>+ '.__('Module')." <input type=\'text\' name=\'logic[modules][]\' size=\'15\' /> "
.$select_menus
.__('Logic')."<input type=\'text\' name=\'logic[expression][]\' size=\'35\'/> "
."<img src=\'".SKIN_PATH."neok.gif\' title=\'".__('Delete')."\' style=\'cursor:pointer;display:table-cell;vertical-align:middle;\' onClick=\'$($(this).parents().get(0)).remove();\'>"
.'</div>';
$frm->addbreak(
__('Logic').
' <img onClick="$(\'#add_logic\').append(\''.$add_logic.'\');" title="'.__('Add').'" src="'.SKIN_PATH.'plus.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;"/>&nbsp;&nbsp;&nbsp;'
);
if (!empty($logic['modules'])) {
foreach ($logic['modules'] as $i=>$value) {
if (!empty($logic['modules'][$i]))
$frm->addrow(
__('Module').' '.$frm->text_box('logic[modules][]', @$logic['modules'][$i], 15)
.stripslashes($select_menus)
,
' '.__('Logic').' '.$frm->text_box('logic[expression][]', @$logic['expression'][$i], 35)
.'<img src="'.SKIN_PATH.'neok.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;" onClick="$($(this).parents(\'tr\').get(0)).remove();">'
);}
}
$frm->addmessage('<div id="add_logic"></div>'.$frm->hidden('settingschange', true));
$frm->show();?>