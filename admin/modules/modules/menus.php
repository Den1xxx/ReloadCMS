<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                  //
//   http://reloadcms.com                            //
//   This product released under GNU General Public License v2           //
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
* Interface                                     *
******************************************************************************/
$menus = parse_ini_file(CONFIG_PATH . 'menus.ini', true);
$subm = '&nbsp;&nbsp;&nbsp;> ';
include(SKIN_PATH . $system->skin . '/skin.php');
$current = array();
foreach ($menus as $column => $coldata){
    if(!empty($skin['menu_point'][$column])){
        $current['/' . $column] = '<b>'.__('Column') . ': ' . $skin['menu_point'][$column].'</b>';
        foreach ($coldata as $menu){
       if(substr($menu, 0, 4) == 'ucm:' && is_readable(DF_PATH . substr($menu, 4) . '.ucm')) {
           $current[$menu] = $subm . $menu;
       } elseif (!empty($system->modules['menu'][$menu])) {
           $current[$menu] = $subm . $system->modules['menu'][$menu]['title'];
       }
        }
    }
}
foreach ($skin['menu_point'] as $column => $text) {
    if(!isset($current['/' . $column])) {
        $unused['/' . $column] = '<b>'.__('Column') . ': ' . $text.'</b>';
    }
}

foreach ($system->modules['menu'] as $menu => $data) {
    if(!rcms_in_array_recursive($subm . $data['title'], $current)) {
        $unused[$menu] = $subm . $data['title'];
    }
}

$ucms = ucm_list();
foreach ($ucms as $menu=>$data) {
    if(!rcms_in_array_recursive($subm . 'ucm:' . $menu, $current)) {
        $unused['ucm:' . $menu] = $subm . 'ucm:' . $menu;
    }
}
?>
<style>
#menus, #unused {
	height:200px;
	overflow-y:auto;
}

#menus li, #unused li {
    font-size: 1.1em;
    margin: 0 3px 3px;
    padding: 3px;
	list-style-type: none;
	cursor: move;
}
</style>

<table cellpadding="2" cellspacing="1" border="0" align="center" width="100%">
<tr>
<th colspan="2">
<?=__('Menus management')?>
</th>
</tr>
<tr>
    <th valign="top" align="left" class='row2' width="50%">
	<?=__('Used modules')?>
	</td>
    <th valign="top" align="left" class='row2' width="50%">
    <?=__('Unused modules')?>
	</td>
</tr>
<tr>
	<td valign="top" align="left" class='row1'>	
	<form name="form1"  action="" method="POST">
	<input type="hidden" name="save" value="1" />
        <ul id="menus" class="MenuSortable" >
       <?php foreach ($current as $element => $text) echo '<li class="ui-state-highlight"><input type="hidden" name="menus[]" value="'.$element.'">'.$text.'</li>';?>
        </ul>
		<p>
		<?=__('To enable the required modules drag modules from right to left field. Elements in second field and menus before first "column" in first field will not be used.')?>
		</p>
		<center>
		<input type="submit" name="" value="<?=__('Save')?>"> <a href="<?=ADMIN_FILE?>?show=module&id=modules.menus&tab=6" class="button"><?=__('Clear')?></a>
		</center>
	</form>
    </td>
    <td valign="top" align="left" class='row1'>
        <ul  id="unused" class="MenuSortable">
       <?php foreach ($unused as $element => $text) echo '<li  class="ui-state-default"><input type="hidden" name="menus[]" value="' . $element . '">' . $text . '</li>';?>
        </ul>
    </td>
</tr>
</table>

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
."<img src=\'".IMAGES_PATH."skins/neok.gif\' title=\'".__('Delete')."\' style=\'cursor:pointer;display:table-cell;vertical-align:middle;\' onClick=\'$($(this).parents().get(0)).remove();\'>"
.'</div>';
$frm->addbreak(
__('Logic').
' <img onClick="$(\'#add_logic\').append(\''.$add_logic.'\');" title="'.__('Add').'" src="'.IMAGES_PATH.'skins/plus.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;"/>&nbsp;&nbsp;&nbsp;'
);
if (!empty($logic['modules'])) {
foreach ($logic['modules'] as $i=>$value) {
if (!empty($logic['modules'][$i]))
$frm->addrow(
__('Module').' '.$frm->text_box('logic[modules][]', @$logic['modules'][$i], 15)
.stripslashes($select_menus)
,
' '.__('Logic').' '.$frm->text_box('logic[expression][]', @$logic['expression'][$i], 35)
.'<img src="'.IMAGES_PATH.'skins/neok.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;" onClick="$($(this).parents(\'tr\').get(0)).remove();">'
);}
}
$frm->addmessage('<div id="add_logic"></div>'.$frm->hidden('settingschange', true));
$frm->show();?>
<script type="text/javascript">
$(function(){
     $("#menus, #unused").sortable({
       connectWith: ".MenuSortable",
       tolerance: "pointer"
	   });
});
</script>
