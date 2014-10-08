<script type="text/javascript" src="tools/js/colorpicker/spectrum.js"></script>
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
switch($key){
		case 'Skin width, px': $frm->addbreak(__('General configuration')); 	break;
		case 'Menu text color': $frm->addbreak(__('Menu')); 	break;
		case 'Sidebar position': $frm->addbreak(__('Sidebar')); 	break;
		case 'Input text color': $frm->addbreak(__('Forms')); 	break;
		default: break;
		}
$help = (
strpos($key,'hover')===false)?''
:' <a name="'.$key.'" onclick="alert(\''.__('This option can not be changed interactively').'. '.
__('Changes can be seen only after saving configuration and reloading the page').'!\');" style="cursor: pointer; cursor:hand;" title="'.__('This option can not be changed interactively').'"><img src="'.IMAGES_PATH.'skins/refresh.png" alt="?"></a> ';
$frm->addrow(__($key), $frm->text_box('css_config['.$key.']', $css_config[$key],9).$help);
}
$frm->show();

//Reset to default settings
$frm =new InputForm ('', 'post', __('Skin').' '.__('Reset'));
$frm->hidden('edit', $skin.'.php');
$frm->hidden('reset', $css_file);
$frm->show();
?>
</div>

<hr/>
<iframe id="site" name="site" src="<?=RCMS_ROOT_PATH?>?skin=<?=$skin?>" width="100%" style='width: 100%;' frameborder=0 marginheight=0 marginwidth=0 onload="$(this).height($(this).contents().find('html').height());" >
<?=__('Your browser don\'t support frames');?>
</iframe>


<script type="text/javascript">
//Preparation
function show_gradient(selector,start_color,end_color){
$('#site').contents().find(selector)
.css('background-image', '-webkit-linear-gradient('+start_color+', '+end_color+')')
.css('background-image', '-moz-linear-gradient('+start_color+', '+end_color+')')
.css('background-image', '-o-linear-gradient('+start_color+', '+end_color+')')
.css('background-image', 'linear-gradient('+start_color+', '+end_color+')')
.css('border-color', end_color);
}

$("#dialog input[name*='color']").spectrum({
cancelText: "<?=__('Cancel')?>",
chooseText: "<?=__('Select')?>",
showInitial: true,
showInput: true
});
$("input:not([name*='color'])").each(function(){
if (parseInt($(this).val())) $(this).css('border','none').css('box-shadow','none').css('width','50px').spinner({min:0});
});
$( "#dialog" ).dialog({width:420,height:500});
$("#navigation").resizable();

//Interaction
//color and text manipulation
$("#dialog input[type=text]").change(function(){
//vars
var body_bg_color=$('#dialog input[name="css_config[Body background color]"]').val();
var body_text_color=$('#dialog input[name="css_config[Text color]"]').val();
var title_text_color=$('#dialog input[name="css_config[Title color]"]').val();
var link_color=$('#dialog input[name="css_config[Link color]"]').val();
var menu_text_color=$('#dialog input[name="css_config[Menu text color]"]').val();
var start_gr_menu=$('#dialog input[name="css_config[Start menu gradient color]"]').val();
var end_gr_menu=$('#dialog input[name="css_config[End menu gradient color]"]').val();
var sidebar_position=$('#dialog input[name="css_config[Sidebar position]"]').val();
var content_position='';
var font_family=$('#dialog input[name="css_config[Font family]"]').val();
var input_text_color=$('#dialog input[name="css_config[Input text color]"]').val();
var input_border_color=$('#dialog input[name="css_config[Input border color]"]').val();
var buttons_selector='input[type=submit],input[type=reset],input[type=button],input[type=file]';
var button_start_color=$('#dialog input[name="css_config[Button start gradient color]"]').val();
var button_end_color=$('#dialog input[name="css_config[Button end gradient color]"]').val();
var button_text_color=$('#dialog input[name="css_config[Button text color]"]').val();

//general
$('#site').contents().find('body').css('background',body_bg_color).css('color',body_text_color).css('font-family',font_family);
$('#site').contents().find('#page div.post h2.window-title').css('color',title_text_color);
$('#site').contents().find('#logo a').css('color',title_text_color);
$('#site').contents().find('#content a').css('color',link_color);

//menu&sidebar
$('#site').contents().find('#menu a').css('color',menu_text_color);
$('#site').contents().find('#sidebar h2.window-title').css('color',menu_text_color);
show_gradient('#menu',start_gr_menu,end_gr_menu);
show_gradient('#sidebar h2.window-title',start_gr_menu,end_gr_menu);
if (sidebar_position=='left') {sidebar_position='left';content_position='right'} else {sidebar_position='right';content_position='left'};
$('#site').contents().find('#content').css('float',content_position);
$('#site').contents().find('#sidebar').css('float',sidebar_position);

//forms
$('#site').contents().find('input').css('color',input_text_color).css('border-color',input_border_color);
$('#site').contents().find('textarea').css('color',input_text_color).css('border-color',input_border_color);
$('#site').contents().find('select').css('color',input_text_color).css('border-color',input_border_color);
$('#site').contents().find(buttons_selector).css('color',button_text_color);
show_gradient(buttons_selector,button_start_color,button_end_color);

});

//digits manipulations
$("#dialog .ui-icon").click(function(){
//vars
var font_size=$('#dialog input[name="css_config[Primary font size, px]"]').val();
var sidebar_width=$('#dialog input[name="css_config[Sidebar width, px]"]').val();
var header_height=$('#dialog input[name="css_config[Header height, px]"]').val();
var logo_padding=$('#dialog input[name="css_config[Logo padding, px]"]').val();
var menu_width=$('#dialog input[name="css_config[Skin width, px]"]').val();
var menu_font_size=$('#dialog input[name="css_config[Menu font size, px]"]').val();
var menu_border_width=$('#dialog input[name="css_config[The thickness of the border of the menu, px]"]').val();
var menu_border_radius=$('#dialog input[name="css_config[Menu radius, px]"]').val();
var menu_padding=$('#dialog input[name="css_config[Padding links menu, px]"]').val();
var sidebar_font_size=$('#dialog input[name="css_config[Sidebar title font size, px]"]').val();
var sidebar_title_radius=$('#dialog input[name="css_config[Sidebar title radius, px]"]').val();
var sidebar_title_padding=$('#dialog input[name="css_config[Sidebar title padding, px]"]').val();
var input_width=$('#dialog input[name="css_config[Input border thickness, px]"]').val();
var input_border_radius=$('#dialog input[name="css_config[Input border radius, px]"]').val();
var input_padding=$('#dialog input[name="css_config[Input padding, px]"]').val();


//general
$('#site').contents().find('body').css('font-size',font_size+'px');
$('#site').contents().find('#header').css('height',header_height+'px');
$('#site').contents().find('#logo').css('padding-top',logo_padding+'px');
$('#site').contents().find('#menu,#header,#page').css('width',menu_width+'px');
$('#site').contents().find('#content').css('width',(parseInt(menu_width)-parseInt(sidebar_width)-40)+'px');

//menu
$('#site').contents().find('#menu').css('height',Math.floor(menu_padding*3.6)+'px').css('border-width',menu_border_width+'px').css('border-radius',menu_border_radius+'px');
$('#site').contents().find('#menu ul').css('margin-left',(menu_border_radius-menu_border_width)+'px');
$('#site').contents().find('#menu a').css('font-size',menu_font_size+'px').css('height',Math.floor(menu_padding*2.6)+'px')
.css('padding',menu_padding+'px '+Math.floor(menu_padding*1.7)+'px 0px '+menu_padding+'px ');

$('#site').contents().find('#sidebar').css('width',sidebar_width+'px');
$('#site').contents().find('#sidebar h2').css('border-width',menu_border_width+'px').css('font-size',sidebar_font_size+'px')
.css('border-radius',sidebar_title_radius+'px')
.css('padding',sidebar_title_padding+'px '+'0px '+sidebar_title_padding+'px '+(parseInt(sidebar_title_padding)+parseInt(sidebar_title_radius))+'px');

//forms
$('#site').contents().find('input').css('border-width',input_width+'px').css('border-radius',input_border_radius+'px').css('padding',input_padding+'px');
$('#site').contents().find('textarea,select,button').css('border-width',input_width+'px').css('border-radius',input_border_radius+'px');

});
</script>