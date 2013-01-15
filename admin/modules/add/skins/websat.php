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
<iframe id="site" src="<?=RCMS_ROOT_PATH?>index.php?module=index&skin=<?=$skin?>" width="100%" style='width: 100%;' frameborder=0 marginheight=0 marginwidth=0 onload="$(this).height($(this).contents().find('html').height());">
<?=__('Your browser don\'t support frames');?>
</iframe>


<script type="text/javascript">
$("input[name*='color']").spectrum({
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
//$("input[type=text]").change(function(){});
//$(".ui-icon").click(function(){});
//$("input[type='text']:not([name*='color'])").add(this).css('border','none').css('box-shadow','none').css('width','50px').spinner();
</script>