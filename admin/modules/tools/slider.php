<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
//Initialize
if (!defined('SLIDER_PATH')) define ('SLIDER_PATH',DATA_PATH . 'slider/');

rcms_loadAdminLib('file-uploads');

$config = (file_exists(CONFIG_PATH.'slider.ini')?unserialize(file_get_contents(CONFIG_PATH.'slider.ini')):array());


//deleting slides
if (!empty($_POST['delete'])) {
$result = '';
foreach ($_POST['delete'] as $key=>$value){
rcms_remove_index ($key,$config['link'],true);
rcms_remove_index ($key,$_POST['link'],true);
rcms_remove_index ($key,$config['address'],true);
rcms_remove_index ($key,$_POST['address'],true);
rcms_remove_index ($key,$config['description'],true);
rcms_remove_index ($key,$_POST['description'],true);
if (fupload_delete($key,SLIDER_PATH)) 
$result .= __('File removed') . ': ' . $key . '<br/>';
else $result .= __('Error occurred') . ': ' . SLIDER_PATH. $key . '<br/>';
}
if(!empty($result)) rcms_showAdminMessage($result);
}

//saving configs
if (!empty($_POST['link'])) {
if (empty($_POST['filename'])) $config=array();
foreach ($_POST['link'] as $link=>$value){
$config['link'][$link] = $_POST['link'][$link];
$config['address'][$link] = $_POST['address'][$link];
$config['description'][$link] = $_POST['description'][$link];
}

if (!empty($_POST['filename'])&&!empty($_FILES['uploads'])) {
if(@move_uploaded_file($_FILES['uploads']['tmp_name'], SLIDER_PATH . $_POST['filename'])) 
rcms_showAdminMessage(__('Files uploaded').': '.SLIDER_PATH.$_POST['filename']);
}
file_write_contents(CONFIG_PATH.'slider.ini',serialize($config));
rcms_showAdminMessage(__('Configuration updated'));
}


if(isset($_POST['banner'])){
file_write_contents(SLIDER_PATH . 'banner.html',$_POST['banner']);
rcms_showAdminMessage(__('Configuration updated').': banner.html');
}

if(isset($_POST['code'])){
$code = '';
$sliders = array();
$banner = @file_get_contents(SLIDER_PATH.'banner.html');
//Build slider
if(isset($_POST['scanfornew'])) {
$files = array_keys($config['link']);
    foreach ($files as $file) {
    $pos = strpos ($file,'slide');
		if ($pos===false) continue;
		else $sliders [] =  $file;
		}

//Build slider group
	$code .= ' 	<div id="box-header-cnt">';
$code .= '        
<div class="b1"></div>
<div class="b2"></div>
<div class="b3"></div>
<div class="b4"></div>
        
<div class="box-opacity"></div>
<div class="bordino"></div>
        <div id="header-flash">
		<div id="header-flash-cnt">
            <div id="header">
';
			$caption='';
			$i=0;
	foreach ($sliders as $slider) {
	$code .= '<img src="'.SLIDER_PATH.$slider.'" width="970" title="#img-gall-'.$i.'" />'."\n";
		if (isset($config['link'][$slider])) $href = $config['link'][$slider];
	else $href = RCMS_ROOT_PATH;	
	$caption .= '
	<div class="nivo-html-caption" id="img-gall-'.$i.'">
	<span class="spn1"><a href="'.$href.'">'.$config['address'][$slider].'</a></span><br />
                <span class="spn2">'.$config['description'][$slider].'</span>
    <div class="spn3"><a href="'.$href.'" ><img src="skins/5557070by/images/img-1010.gif" width="25" height="25" /></a></div>
            </div>';
	$i++;
	}
	$code .= '</div>'.$caption;//header
	$code .= ' 
	<link rel="stylesheet" href="skins/5557070by/images/nivo-sli.css" type="text/css" media="screen" />
        <script type="text/javascript" src="skins/5557070by/images/jquery03.js"></script>
        <script type="text/javascript">
        $(function() {
            $("#header").nivoSlider({
                effect:"fade",
                animSpeed:1000,
                pauseTime:6000,
                captionOpacity:0.8
            });
        });
        </script>
                </div>
				</div>';//<div id="header-flash"><div id="header-flash-cnt">
$code .= $banner;
$code .= '</div>';//box-header-cnt
} else $code = $_POST['code'];

//Safe code for slider
if (file_write_contents(SLIDER_PATH . 'code.html', $code))
	rcms_showAdminMessage(__('Configuration updated'));
    else rcms_showAdminMessage(__('Error occurred'));
}

//Interface
//Main slider, upload file array{slide100.jpg,slide200.jpg,slide300.jpg}

//
$files = array_keys($config['link']);
//
$frm =new InputForm ('', 'post', __('Submit'),'','','','','id="sortable"');
//$frm =new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data','slideadd','id="sortable"');
$frm->addbreak(__('Slider').': '.__('Uploaded files'));
if(!empty($files)) {
$sliders = array();
$rand = rand(1,15000);
    foreach ($files as $file) {
    $pos = strpos ($file,'slide');
		if ($pos===false) continue;
		else {
		$sliders [] =  $file;
        $frm->addrow('<a class="gallery" href="' . SLIDER_PATH . $file . '">
		<img height="80px" src="'.SLIDER_PATH.$file.'?rand='.$rand.'"/></a><br/>'.
		__('Link').': '.$frm->text_box('link['.$file.']', @$config['link'][$file], 55).'<br/>'.
		__('Address').': '.$frm->text_box('address['.$file.']', @$config['address'][$file], 55).'<br/>'.
		__('Description').': '.$frm->text_box('description['.$file.']', @$config['description'][$file], 85),
		 '<br/><br/><span style="cursor:pointer;">&nbsp;&darr;&uarr;'.__('Move').'&nbsp;</span><br/><br/><br/>'.$frm->checkbox('delete[' . $file . ']', 'true', __('Delete')), 'top');
		}
    }
}
$frm->show();

if (!empty($sliders)) {
natsort($sliders);
$last_slide = array_pop($sliders);
$last_slide = str_replace('00.jpg','',$last_slide);
$last_slide = str_replace('slide','',$last_slide);
$num = (int)$last_slide;
$num++;
} else $num = 1;
?>

<script script type="text/javascript">
<!--

function selChange(seln) {
selNum = seln.items.selectedIndex;
	if (selNum > 0) {
	Isel = seln.items.options[selNum].value;
	Itext = seln.items.options[selNum].text;
	document.forms['slideadd'].elements['link[slide<?=$num?>00.jpg]'].value = '?module=fncatalogue&showitem=' + Isel;
	document.forms['slideadd'].elements['address[slide<?=$num?>00.jpg]'].value = Itext;
	}
}
//-->
</script> 

<?

    $query="SELECT `id`,`name` from `items` ORDER BY `name`";
    $allitems=simple_queryall($query);
	if (!empty ($allitems)) {
	$items = array();
	foreach ($allitems as $item){
		$items[$item['id']] = $item['name'];
	}

$frm =new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data','slideadd');
$frm->addbreak(__('Upload files'));

$frm->addrow(__('Link') , $frm->text_box('link[slide'.$num.'00.jpg]', '', 55).$frm->select_tag('items',$items,-1,'onChange="selChange(this.form)">
	<option value="-1">'. __('Item').'</option'), 'top');
$frm->addrow(__('Address'), $frm->text_box('address[slide'.$num.'00.jpg]', '', 55));
$frm->addrow(__('Description'), $frm->text_box('description[slide'.$num.'00.jpg]', '', 85));
$frm->addrow(__('Select file').' (*.jpg, 970px × 266px)', $frm->file('uploads'), 'top');
$frm->hidden('filename','slide'.$num.'00.jpg');
$frm->show();
}
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Slider').': '.__('Banner'));
$frm->addrow(__('Content of').' banner.html', $frm->textarea('banner', @file_get_contents(SLIDER_PATH.'banner.html'), 80, 10), 'top');
$frm->show();
$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Code'));
$frm->addrow(__('Content of').' code.html', $frm->textarea('code', @file_get_contents(SLIDER_PATH.'code.html'), 80, 10), 'top');
$frm->addrow(__('Rebuild index'), $frm->checkbox('scanfornew', '1', '', 1));
$frm->show();
?>
<script type="text/javascript">
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
 
$("#sortable table tbody").sortable({
    helper: fixHelper
});
</script>