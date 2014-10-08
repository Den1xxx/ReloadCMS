<?php
if(empty($_REQUEST['path'])) $_REQUEST['path'] = realpath('.');
else $_REQUEST['path'] = realpath($_REQUEST['path']);
$_REQUEST['path'] = str_replace('\\', '/', $_REQUEST['path']) . '/';
$url_inc = '?show=module&tab=8&id=' . implode('.', $module);

if(!empty($_REQUEST['edit'])){
	if(!empty($_REQUEST['save'])) {
	    file_write_contents($_REQUEST['path'] . $_REQUEST['edit'], $_REQUEST['newcontent']);
	}
    $oldcontent = @file_get_contents($_REQUEST['path'] . $_REQUEST['edit']);
    $editlink = $url_inc . '&edit=' . $_REQUEST['edit'] . '&path=' . $_REQUEST['path'];
    $backlink = $url_inc . '&path=' . $_REQUEST['path'];
?>
<table border='0' cellspacing='0' cellpadding='1' width="100%">
<tr>
    <th><?=__('File manager') . ' - ' .  __('Edit') . ' - ' . $_REQUEST['path'].$_REQUEST['edit']?></th>
</tr>
<tr>
    <td class="row1">
        <a href="<?=$backlink?>"><?=__('Back')?></a>
	</td>
</tr>
<tr>
    <td class="row1" align="center">
        <form name="form1" method="post" action="<?=$editlink?>">
            <textarea name="newcontent" id="newcontent" cols="45" rows="15" style="width: 100%"><?=htmlspecialchars($oldcontent)?></textarea>
            <input type="submit" name="save" value="<?=__('Submit')?>">
            <input type="submit" name="cancel" value="<?=__('Cancel')?>">
        </form>
    </td>
</tr>
</table>
<?php
} elseif(!empty($_REQUEST['rights'])){
	if(!empty($_REQUEST['save'])) {
	    if(rcms_chmod($_REQUEST['path'] . $_REQUEST['rights'], convert_rights_string($_REQUEST['rights_val']), @$_REQUEST['recursively']))
		rcms_showAdminMessage(__('File updated')); 
		else rcms_showAdminMessage(__('Error occurred'));
	}
	clearstatcache();
    $oldrights = get_rights_string($_REQUEST['path'] . $_REQUEST['rights'], true);
    $link = $url_inc . '&rights=' . $_REQUEST['rights'] . '&path=' . $_REQUEST['path'];
    $backlink = $url_inc . '&path=' . $_REQUEST['path'];
?>
<table border='0' cellspacing='0' cellpadding='1' width="100%">
<tr>
    <th><?=__('File manager') . ' - ' .  __('Rights') . ' - ' . $_REQUEST['rights']?></th>
</tr>
<tr>
    <td class="row1">
        <a href="<?=$backlink?>"><?=__('Back')?></a>
	</td>
</tr>
<tr>
    <td class="row1" align="center">
        <form name="form1" method="post" action="<?=$link?>">
            <input type="text" name="rights_val" value="<?=$oldrights?>"><br/>
        <?php if (is_dir($_REQUEST['path'] . $_REQUEST['rights'])) {?>
            <input type="checkbox" name="recursively" value="1"> <?=__('Recursively')?><br/>
        <?php } ?>
            <input type="submit" name="save" value="<?=__('Submit')?>">
        </form>
    </td>
</tr>
</table>
<?php
} elseif (!empty($_REQUEST['rename'])&&$_REQUEST['rename']<>'.') {
	if(!empty($_REQUEST['save'])) {
	    rcms_rename_file($_REQUEST['path'] . $_REQUEST['rename'], $_REQUEST['path'] . $_REQUEST['newname']);
		rcms_showAdminMessage(__('File updated'));
		$_REQUEST['rename'] = $_REQUEST['newname'];
	}
	clearstatcache();
    $link = $url_inc . '&rename=' . $_REQUEST['rename'] . '&path=' . $_REQUEST['path'];
    $backlink = $url_inc . '&path=' . $_REQUEST['path'];

?>
<table border='0' cellspacing='0' cellpadding='1' width="100%">
<tr>
    <th><?=__('File manager') . ' - ' .  __('Rename') . ' - ' . $_REQUEST['rename']?></th>
</tr>
<tr>
    <td class="row1">
        <a href="<?=$backlink?>"><?=__('Back')?></a>
	</td>
</tr>
<tr>
    <td class="row1" align="center">
        <form name="form1" method="post" action="<?=$link?>">
            <?=__('Rename')?>: <input type="text" name="newname" value="<?=$_REQUEST['rename']?>"><br/>
            <input type="submit" name="save" value="<?=__('Submit')?>">
        </form>
    </td>
</tr>
</table>
<?php

} else {
    $msg = '';
    if(!empty($_FILES['upload'])) {
        if(!empty($_FILES['upload']['name'])){
            $_FILES['upload']['name'] = str_replace('%', '', $_FILES['upload']['name']);
            if(!move_uploaded_file($_FILES['upload']['tmp_name'], $_REQUEST['path'] . $_FILES['upload']['name'])){
                $msg = __('Error occurred');
            } else {$msg = __('Files uploaded');}
        }
    } elseif(!empty($_REQUEST['delete'])&&$_REQUEST['delete']<>'.') {
        if(!rcms_delete_files(($_REQUEST['path'] . $_REQUEST['delete']), true)) {
            $msg = __('Error occurred');
        }
	} elseif(!empty($_REQUEST['mkdir'])) {
        if(!rcms_mkdir($_REQUEST['path'] . $_REQUEST['dirname'])) {
            $msg = __('Error occurred');
        }
    } elseif(!empty($_REQUEST['mkfile'])) {
        if(!$fp=fopen($_REQUEST['path'] . $_REQUEST['filename'],"w")) {
            $msg = __('Error occurred');
        } else fclose($fp);
    }
?>
<table border='0' cellspacing='0' cellpadding='1' width="100%">
<tr>
    <th colspan="2"><?=__('File manager')?><?=(!empty($_REQUEST['path'])?' - '.$_REQUEST['path']:'')?></th>
</tr>
<tr>
    <td class="row2">
		<table><tr><td>
        <form name="form1" method="post" action="<?=$url_inc?>">
        <input type="hidden" name="path" value="<?=$_REQUEST['path']?>" />
        <input type="text" name="dirname" size="15">
        <input type="submit" name="mkdir" value="<?=__('Make directory')?>">
        </form>
		</td><td>
        <form name="form1" method="post" action="<?=$url_inc?>">
        <input type="hidden" name="path" value="<?=$_REQUEST['path']?>" />
        <input type="text" name="filename" size="15">
        <input type="submit" name="mkfile" value="<?=__('New file')?>">
        </form>
		</td></tr></table>
    </td>
    <td class="row3">
        <form name="form1" method="post" action="<?=$url_inc?>" enctype="multipart/form-data">
        <input type="hidden" name="path" value="<?=$_REQUEST['path']?>" />
        <input type="file" name="upload" />
        <input type="submit" name="test" value="<?=__('Upload')?>" />
        </form>
    </td>
</tr>
<tr>
    <td class="row3" colspan="2"><?=$msg?></td>
</tr>
</table>
<table border='0' cellspacing='1' cellpadding='1' width="100%">
<tr> 
    <th width="100%"> <?=__('Filename')?> </th>
    <th style="white-space:nowrap"> <?=__('Size of file')?> </th>
    <th style="white-space:nowrap"> <?=__('Date')?> </th>
    <th style="white-space:nowrap"> <?=__('Rights')?> </th>
    <th colspan="2" style="white-space:nowrap"> <?=__('Manage')?> </th>
</tr>
<?php
$elements = rcms_scandir($_REQUEST['path'], '', 'all', true);
$dirs = array();
$files = array();
foreach ($elements as $file){
    if(@is_dir($_REQUEST['path'] . $file)){
        $dirs[] = $file;
    } else {
        $files[] = $file;
    }
}
natsort($dirs); natsort($files);
$elements = array_merge($dirs, $files);

foreach ($elements as $file){
    $filename = $_REQUEST['path'] . $file;
    $filedata = @stat($filename);
    if(@is_dir($filename)){
		$filedata[7] = '';
        $link = '<a href="'.$url_inc.'&path='.$_REQUEST['path'].$file.'" title="'.__('Show').' '.$file.'"><img src="'.IMAGES_PATH.'skins/folder.png"/> '.$file.'</a>';
        $loadlink = '';
        $style = 'row2';
		 if ($file<>'.')      $alert = 'onClick="if(confirm(\'' . __('Are you sure you want to delete this directory (recursively)?').'\n /'. $file. '\')) document.location.href = \'' . $url_inc . '&delete=' . $file . '&path=' . $_REQUEST['path']  . '\'"'; else $alert = '';
    } else {
	$link_img=str_replace(realpath(RCMS_ROOT_PATH).'/',RCMS_ROOT_PATH,$_REQUEST['path']);
	if (is_images($filename)) $link = '<a href="'.$link_img.$file.'" class="gallery" title="'.$link_img.$file.'"><img src="'.IMAGES_PATH.'skins/view.gif" > '.$file.'</a>';
	else $link = '<a href="' . $url_inc . '&edit=' . $file . '&path=' . $_REQUEST['path']. '" title="' . __('Edit') . '"><img src="'.IMAGES_PATH.'skins/edit.png"/> ' . $file . '</a>';
        $loadlink = '&nbsp;&nbsp;<a href="'.RCMS_ROOT_PATH.ADMIN_FILE.'?show=module&id=tools.filemanager&download='.base64_encode($filename).'" title="'.__('Download').' '. $file .'">'.__('Download').'</a>';
        $style = 'row1';
		$alert = 'onClick="if(confirm(\''. __('File selected').': \n'. $file. '. \n'.__('Are you sure you want to delete this file?') . '\')) document.location.href = \'' . $url_inc . '&delete=' . $file . '&path=' . $_REQUEST['path']  . '\'"';
    }
    $deletelink = '<a href="#" title="' . __('Delete') . ' '. $file . '" ' . $alert . '>' . __('Delete') . '</a>';
    $renamelink = '<a href="' . $url_inc . '&rename=' . $file . '&path=' . $_REQUEST['path'] . '" title="' . __('Rename') .' '. $file . '">' . __('Rename') . '</a>'.$loadlink;
    $rightstext = '<a href="' . $url_inc . '&rights=' . $file . '&path=' . $_REQUEST['path'] . '" title="' . __('Rights') .' '. $file . '">' . @get_rights_string($filename) . '</a>';
?>
<tr> 
    <td class="<?=$style?>"><?=$link?></td>
    <td class="<?=$style?>"><?=$filedata[7]?></td>
     <td class="<?=$style?>" style="white-space:nowrap"><?=gmdate("Y-m-d H:i:s",$filedata[9])?></td>
   <td class="<?=$style?>"><?=$rightstext?></td>
    <td class="<?=$style?>"><?=$deletelink?></td>
    <td class="<?=$style?>"><?=$renamelink?></td>
</tr>
<?php
    }
}
?>
</table>
<script language="Javascript" type="text/javascript" src="./tools/js/edit_area/edit_area_full.js"></script>
<script language="Javascript" type="text/javascript">
    $(document).ready(
        function()        {
            editAreaLoader.init({
                id: "newcontent", // привязываем к textarea с id: newcontent
                allow_resize: "both", // разрешаем изменения размера
                allow_toggle: true, // разрешаем отключение EditArea
				start_highlight: true,
				syntax: "php",
                word_wrap: false,
                language: "ru",
                font_size: "9px",
                toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help",
                syntax_selection_allow: "css,html,js,php,python,xml,c,cpp,sql,basic,pas",
                replace_tab_by_spaces: "4"
            });
        }
    );
</script>