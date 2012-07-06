<?php
	if($system->checkForRight('GENERAL')) {

$aforizms = array_values(rcms_scandir(DATA_PATH . 'aforizm'));
if(!empty($aforizms) && in_array($system->language . '.txt', $aforizms)) {
    $file = DATA_PATH . 'aforizm/' . $system->language . '.txt';
} elseif(!empty($aforizms) && in_array($system->config['default_lang'] . '.txt', $aforizms)) {
    $file = DATA_PATH . 'aforizm/' . $system->config['default_lang'] . '.txt';
} elseif(!empty($aforizms)) $file = DATA_PATH . 'aforizm/' . $aforizms[0];

if (!empty($_POST['save'])) {
file_write_contents($file,$_POST['text']);
show_window ('',__('Module updated'));
}

if(!empty($file)){
    $array_aforizm = file($file);  
} else $array_aforizm = array();
$result = '';
foreach ($array_aforizm as $aforizm)
$result .= $aforizm;

    $frm = new InputForm ('', 'post', __('Submit'));
    $frm->hidden('save', '1');
	$frm->addrow(__('All HTML is allowed in this field and line breaks will not be transformed to &lt;br&gt; tags!'));
    $frm->addrow('',$frm->textarea('text', $result, 80, 23), 'top');
    $result = $frm->show(true);

show_window (__('Edit').' '.__('Aphorism'),$result);
} 

?>