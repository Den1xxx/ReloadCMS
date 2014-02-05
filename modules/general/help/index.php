<?php
if ($system->current_point=='__MAIN__') {
if ($system->checkForRight('HELP'))  {
$lang=curlang();
$help_dir=DATA_PATH.'help/'.$lang;
$module=get('edit');
$file=$help_dir.'/'.$module;

if (!is_dir(DATA_PATH.'help/')) mkdir(DATA_PATH.'help/',0777);
if (!is_dir($help_dir)) mkdir($help_dir,0777);

if (!empty($_GET['delete'])) {
$dfile=$help_dir.'/'.vf(get('delete'));
if (rcms_delete_files($dfile)) show_window (__('Result'),__('File removed'));
else show_window (__('Result'),__('Error occurred').':'.$dfile);
}

if (!empty($_POST['save'])&&!empty($module)) {
file_write_contents($file,$_POST['help_edit']);
show_window (__('Result'),__('File updated').'. '.__('Show').' — '.show_help($module));
}


if (!empty($module)) {
	$result = file_exists($file)?file_get_contents($file):'';
    $frm = new InputForm ('', 'post', __('Submit'));
    $frm->hidden('save', '1');
    $frm->addrow('',$frm->textarea('help_edit', $result, 55, 15).tinymce_selector('help_edit'), 'top');
    $result = $frm->show(true);
show_window(__('Help').' '.__('for the module').' '.get('edit').': '.__('Edit'), $result, 'center');
} else {
$files=rcms_scandir($help_dir);
if (!empty($files)) {
    $result = '<table width="100%">';
foreach ($files as $helpfile) {
	$admin_link = '
	<a href="?module=help&edit='.$helpfile.'"><img title="'.__('Edit').'" src="'.SKIN_PATH.'edit_small.gif"></a>
	<a href="#" onClick="if(confirm(\''	. __('Delete').': \n'. str_replace('"','&#8243;',$helpfile). '?\n\')) document.location.href = \'?module=help&delete='.$helpfile.'\'">
	<img title="'.__('Delete').'" src="'.SKIN_PATH.'fastnews/trash_small.gif">
	</a>
	';
     $result .= '<tr><td class="row2" align="left" valign="top">'.$helpfile.'</td><td class="row3" align="left" valign="top">'.$admin_link.'</td></tr>';
}
$result .= '</table>';
show_window(__('Help').': '.__('Manage files'), $result, 'center');
} else show_window(__('Help'),__('Nothing founded'));
}
}  else show_window(__('Error'),__('You are not administrator of this site'));
} elseif (($system->checkForRight('-any-'))) {
$link=$system->checkForRight('HELP')?'<a href="?module=help"><img src="'.SKIN_PATH.'view_file.png" title="'.__('Manage files').'" alt="'.__('Manage files').'" /></a>':'';
show_window(__('Help'),show_help().$link);
}
?>
