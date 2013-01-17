<script src="tools/js/colorpicker/spectrum.js"></script>
<link rel="stylesheet" href="tools/js/colorpicker/spectrum.css" />
<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

//Initialisation
$skin = str_replace('.php','',basename(__FILE__));
$css_file = DATA_PATH.'skins/'.$skin.'.ini';
$system->addInfoToHead(
'<script src="'.RCMS_ROOT_PATH.'tools/js/colorpicker/spectrum.js"></script>
<link rel="stylesheet" href="'.RCMS_ROOT_PATH.'tools/js/colorpicker/spectrum.css" />'
);

//API
if(!empty($_POST['css_config'])) {
file_write_contents($css_file,serialize($_POST['css_config']));
rcms_showAdminMessage(__('Configuration updated'));
}

if(!empty($_POST['reset'])) {
rcms_delete_files($css_file);
}

if (is_file($css_file)) $css_config = unserialize(file_get_contents($css_file));
else include(SKIN_PATH.$skin.'/style.default.php');

// Interface generation
?>

<div id="dialog" title="<?=__('Skin').' '.$skin.' &mdash; '.__('Configuration');?>" style="font-size:0.8em;">
<?
//Skin configuration
$frm =new InputForm ('', 'post', __('Save'));
$frm->hidden('edit', $skin.'.php');
foreach ($css_config as $key=>$value) {
$frm->addrow(__($key), $frm->text_box('css_config['.$key.']', $css_config[$key],9));
}
$frm->show();

//Reset to default settings
$frm =new InputForm ('', 'post', __('Save'));
$frm->addbreak(__('Skin').' '.__('Reset'));
$frm->hidden('edit', $skin.'.php');
$frm->hidden('reset', $css_file);
$frm->show();
?>
</div>

<hr/>
<iframe id="site" name="site" src="<?=RCMS_ROOT_PATH?>index.php?module=index&skin=<?=$skin?>" width="100%" style='width: 100%;' frameborder=0 marginheight=0 marginwidth=0 onload="$(this).height($(this).contents().find('html').height());" >
<?=__('Your browser don\'t support frames');?>
</iframe>


<script type="text/javascript">
$("#dialog input[name*='color']").spectrum({
cancelText: "<?=__('Cancel')?>",
chooseText: "<?=__('Select')?>",
showInitial: true,
showInput: true
});
$("input:not([name*='color'])").each(function(){
if (parseInt($(this).val())) $(this).css('border','none').css('box-shadow','none').css('width','50px').spinner({min:0});
});
$( "#dialog" ).dialog({width:400,height:500});
$("#navigation").resizable();

$("#dialog input[type=text]").change(function(){
var body_bg_color=$('#dialog input[name="css_config[Body background color]"]').val();
$('#site').contents().find('body').css('background',body_bg_color);
var body_text_color=$('#dialog input[name="css_config[Text color]"]').val();
$('#site').contents().find('body').css('color',body_text_color);
var title_text_color=$('#dialog input[name="css_config[Title color]"]').val();
$('#site').contents().find('#page div.post h2.window-title').css('color',title_text_color);
var menu_text_color=$('#dialog input[name="css_config[Menu text color]"]').val();
$('#site').contents().find('#menu a').css('color',menu_text_color);
$('#site').contents().find('#sidebar h2.window-title').css('color',menu_text_color);


});

$("#dialog .ui-icon").click(function(){
var font_size=$('#dialog input[name="css_config[Primary font size, px]"]').val();
$('#site').contents().find('body').css('font-size',font_size+'px');
var menu_width=$('#dialog input[name="css_config[Skin width, px]"]').val();
$('#site').contents().find('#menu').css('width',menu_width+'px');
var sidebar_width=$('#dialog input[name="css_config[Sidebar width, px]"]').val();
$('#site').contents().find('#sidebar').css('width',sidebar_width+'px');
var menu_font_size=$('#dialog input[name="css_config[Menu font size, px]"]').val();
$('#site').contents().find('#menu a').css('font-size',menu_font_size+'px');
var menu_border_width=$('#dialog input[name="css_config[The thickness of the border of the menu, px]"]').val();
$('#site').contents().find('#menu').css('border-width',menu_border_width+'px');
$('#site').contents().find('#sidebar h2').css('border-width',menu_border_width+'px');
var menu_border_radius=$('#dialog input[name="css_config[Menu radius, px]"]').val();
$('#site').contents().find('#menu').css('border-radius',menu_border_radius+'px');
var menu_padding=$('#dialog input[name="css_config[Padding links menu, px]"]').val();
$('#site').contents().find('#menu a')
.css('padding',menu_padding+'px '+Math.floor(menu_padding*1.7)+'px 0px '+menu_padding+'px ')
.css('height',Math.floor(menu_padding*2.6)+'px');
$('#site').contents().find('#menu').css('height',Math.floor(menu_padding*3.6)+'px');
$('#site').contents().find('#menu ul').css('margin-left',(menu_border_radius-menu_border_width)+'px');


});
//$("input[type='text']:not([name*='color'])").add(this).css('border','none').css('box-shadow','none').css('width','50px').spinner();
</script>