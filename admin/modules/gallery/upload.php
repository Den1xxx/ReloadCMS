<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
rcms_loadAdminLib('file-uploads');
$gallery = new gallery();

// Uploading
if(!empty($_FILES['upload'])) {
$result='';
	if(fupload_array($_FILES['upload'], GALLERY_UPLOAD_DIR, $gallery->img_preg)){
		$new_names = $gallery->scanForNewImages();
		foreach ($_FILES['upload']['name'] as $key => $name){
			$keywords = (!empty($_POST['gkeywords']) ? $_POST['gkeywords'] : '') . (!empty($_POST['keywords'][$key]) ? (!empty($_POST['gkeywords']) ? ';' : '') . $_POST['keywords'][$key] : '');
			if(!empty($keywords)) $gallery->setKeywords(@$new_names[$name], $keywords);
			if(!empty($_POST['title'][$key])) {
			$gallery->setDataValue(@$new_names[$name], 'title', $_POST['title'][$key]);
			$result.='<br/>'.__('Filename').': '.@$new_names[$name].' '.__('Title').': '.$_POST['title'][$key];
			} elseif (!empty($_POST['gtitle'])) {
			if (!empty($_POST['gadd'])) {
			$skey = (int)($_POST['gstart'])+$key;
			$snum = str_pad($skey,$_POST['gdigits'], '0', STR_PAD_LEFT);
			} else $snum='';
			$title = $_POST['gtitle'].$snum;
			$gallery->setDataValue(@$new_names[$name], 'title', $title);
			$result.='<br/>'.__('Filename').': '.@$new_names[$name].' '.__('Title').': '.$title;
			}
		}
		$gallery->saveIndexFiles();
		rcms_showAdminMessage(__('Images uploaded').$result);
	} else {
		rcms_showAdminMessage(__('Error occurred'));
	}
}


function pic_select_keywords($array_keys,$frm='newpicture',$key='keywords',$add=true) {
$result='';
if ($add) $ins='if (this.value!=-1) {if (document.forms[\''.$frm.'\'].elements[\''.$key.'\'].value != \'\') insert_text(document.forms[\''.$frm.'\'].elements[\''.$key.'\'], \';\'); insert_text(document.forms[\''.$frm.'\'].elements[\''.$key.'\'],  this.options[selectedIndex].text);}';
else $ins='if (this.value!=-1) document.forms[\''.$frm.'\'].elements[\''.$key.'\'].value = this.options[selectedIndex].text;';
if (!empty($array_keys)&&is_array($array_keys)){
$result .= ' 
	<select name="all_keys" onchange="'.$ins.'">
	<option value="-1">' . __('Select') . '</option>';
foreach ($array_keys as $key=>$keyword){
$result.='<option value="'.$key.'" >'.$keyword.'</option>';
}
$result.='</select>';
}
return $result;
}

//Upload form
$frm_name='newpicture';
$keywords=array_keys($gallery->indexes['keywords']);

$frm = new InputForm ('', 'post', __('Submit'), '', '' , 'multipart/form-data',$frm_name);
$frm->addbreak(__('Upload images'));
$code_to_repeat = __('Image') . ': ' . $frm->file('upload[]') . '<br />' . __('Keywords') . ': ' . $frm->text_box('keywords[]', '') . '<br />' . __('Title') . ': ' . $frm->text_box('title[]', '') . '<br /><br />';
$js_b = '<script type="text/javascript">function add_file_field(){ var ni = document.getElementById(\'upload_images\'); var newdiv = document.createElement(\'div\'); newdiv.innerHTML = \'' . $code_to_repeat . '\'; ni.appendChild(newdiv); }</script>';
$js_a = '<script type="text/javascript">add_file_field();</script>';
$frm->addrow(__('Select images to upload'), $js_b . '<span id="upload_images"></span>' . $js_a . '<a href="#" onclick="add_file_field();">' . __('Upload another image') . '</a>', 'top');
$frm->addrow(__('Keywords for all images'), $frm->text_box('gkeywords', post('gkeywords')).pic_select_keywords($keywords,$frm_name,'gkeywords',false));
$frm->addmessage(__('To divide keywords use ; symbol'));
$frm->addrow(__('Title for all images'), $frm->text_box('gtitle', post('gtitle')));
$frm->addrow(__('Add number to title'),$frm->checkbox('gadd',  '1', __('Enable'),post('gadd')).'&nbsp;&nbsp;&nbsp;'.__('Start').': '.$frm->text_box('gstart', 1, 2).__('Number of digits').': '.$frm->text_box('gdigits', 2, 2).'&nbsp;&nbsp;&nbsp;');
$frm->addmessage(__('Also you can upload your files using filemanager or FTP to directory') . ' ' . substr(GALLERY_UPLOAD_DIR, 1) . ' ' . __('(Relative to ReloadCMS installation path) and rebuild index file.'));
$frm->show();
?>