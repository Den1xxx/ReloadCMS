<?php
define('RCMS_ROOT_PATH', './');
include(RCMS_ROOT_PATH . 'common.php');
global $system,$lightbox_config;
$ins=get('ins');
$form=get('form');
$url_inc=$ins?'?ins='.$ins.'&form='.$form:'?ins=0';
$msg = '';
$insertlink='';
function user_can_delete($file) {
if(!empty($file)&&$file<>'.'&&$file<>'..') return true;
else return false;
}
if (!user_can_upload_images()) {
return false;
}

$start_path=realpath(RCMS_ROOT_PATH.'uploads/');
if (!empty($lightbox_config['distribute_enable'])) {
	if (!cfr('GENERAL')) $start_path .= $system->user['username'];
	if (!@is_dir($start_path)) {
		$msg=__('Nothing founded');
		rcms_mkdir(RCMS_ROOT_PATH.'uploads/'.$system->user['username']);
		//rcms_redirect('');
	}
}

if(empty($_GET['path'])) {
$user_path = $start_path;
} else {
$user_path = realpath($_GET['path']);
if (strlen($start_path)>strlen($user_path)) $user_path=$start_path;
}
$user_path = str_replace('\\', '/', $user_path) . '/';
    if(user_can_delete(get('delete'))) {
        if(!rcms_delete_files($user_path . $_GET['delete'],true)) {
            $msg .= __('Error occurred').' '.$_GET['delete'];
        } else $msg .= __('Deleted').' '.$_GET['delete'];
    }  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$system->config['encoding']?>">
<? rcms_show_element('meta'); ?>
<link rel="stylesheet" href="admin/skins/default/style.css" type="text/css">
<style>
html,body{
min-height:100%;
height:100%;
}
</style>
</head>
<body>
<table border='0' cellspacing='1' cellpadding='1' width="100%">
<tr> 
    <td width="100%" colspan="5"> <?=$msg?> </th>
</tr>
<tr> 
    <th width="100%"> <?=__('Filename')?> </th>
    <th style="white-space:pre"> <?=__('Size of file')?> </th>
    <th style="white-space:pre"> <?=__('Date')?> </th>
    <th style="white-space:pre" colspan="2"> <?=__('Manage')?> </th>
</tr>
<?php
$elements = rcms_scandir($user_path, '', 'all', true);
$dirs = array();
$files = array();
foreach ($elements as $file){
    if(@is_dir($user_path . $file)){
        $dirs[] = $file;
    } else {
        $files[] = $file;
    }
}
natsort($dirs); natsort($files);
$elements = array_merge($dirs, $files);

foreach ($elements as $file){
    $filename = $user_path . $file;
    $filedata = @stat($filename);
    if(@is_dir($filename)){
    if($file=='.') continue;//$filename = $start_path . $file;;
        $filedata[7] = '';
        $link = '<a href="'.$url_inc.'&path='.$filename.'" title="'.__('Show').'"><img src="'.IMAGES_PATH.'skins/folder.png"/> '.$file.'</a>';
        $loadlink = '';
        $style = 'row2';
        $alert = $file<>'.' ? 'onClick="if(confirm(\'' . __('Are you sure you want to delete this directory (recursively)?').'\n /'. $file. '\')) document.location.href = \''.$url_inc.'&delete=' . $file . '&path=' . $user_path  . '\'"' : $alert = '';
    } else {
    $link_img=str_replace(realpath(RCMS_ROOT_PATH).'/',RCMS_ROOT_PATH,$user_path);
    if (is_images($filename)) $link = '<a href="'.$link_img.$file.'" class="gallery" title="'.$file.'"><img src="'.IMAGES_PATH.'skins/view.gif" > '.$file.'</a>';
    else continue;
        $style = 'row1';
        $alert = 'onClick="if(confirm(\''. __('File selected').': \n'. $file. '. \n'.__('Are you sure you want to delete this file?') . '\')) document.location.href = \''.$url_inc.'&delete=' . $file . '&path=' . $user_path  . '\';else return false;"';
        $opener='onClick="window.opener.document.getElementById(\''.get('ins').'\').value += \'[img]\'+\''.$link_img.$file.'\'+\'[/img]\';'.(!empty($lightbox_config['close_by_clicking'])?'window.close();':'').' return false; "';
        $insertlink = get('ins')?'<a href="'.$link_img.$file.'"  class="btnmain" ' . $opener . '>' . __('Insert') . '</a>':'';
    }
    $deletelink = user_can_delete($file)?'<a href="'.$url_inc.'&delete=' . $file . '&path=' . $user_path  . '" class="btnmain" ' . $alert . '>' . __('Delete') . '</a>':'';
?>
<tr> 
    <td class="<?=$style?>"><?=$link?></td>
    <td class="<?=$style?>" style="white-space:pre"><?=$filedata[7]?></td>
    <td class="<?=$style?>" style="white-space:pre"><?=gmdate("Y-m-d H:i:s",$filedata[9])?></td>
    <td class="<?=$style?>"><?=$deletelink?></td>
    <td class="<?=$style?>"><?=$insertlink?></td>
</tr>
<?php } ?>
</table>
</body>
</html>