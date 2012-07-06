<?php
$aforizms = array_values(rcms_scandir(DATA_PATH . 'aforizm'));
if(!empty($aforizms) && in_array($system->language . '.txt', $aforizms)) {
    $file = DATA_PATH . 'aforizm/' . $system->language . '.txt';
} elseif(!empty($aforizms) && in_array($system->config['default_lang'] . '.txt', $aforizms)) {
    $file = DATA_PATH . 'aforizm/' . $system->config['default_lang'] . '.txt';
} elseif(!empty($aforizms)) $file = DATA_PATH . 'aforizm/' . $aforizms[0];

if(!empty($file)){
    srand(microtime() * 1000000);
    $aforizm = file($file);  
	$result = $aforizm[array_rand($aforizm, 1)];
	if($system->checkForRight('GENERAL'))     
	$result = $result.'<br/><a href = "?module=aforizm_edit">'.__('Edit').'</a>';  
    show_window('', $result);
}

?>