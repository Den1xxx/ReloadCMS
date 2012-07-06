<?php 
define('RCMS_ROOT_PATH', './');
require_once(RCMS_ROOT_PATH . 'common.php');

	if ($system->checkForRight('GENERAL')) {
		if((!empty($_FILES['uploadfile'])) AND is_images($_FILES['uploadfile']['name'])) {
		$filename = FILES_PATH. translitCP1251toUTF8(basename($_FILES['uploadfile']['name']));
				if(@move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filename))
				$result = '[img]'. $filename . "[/img]\n";
				else $result = __('Cannot write to file') . ': ' . $filename;
				} else $result = __('Your file is not an image or is corrupted') . ' (' . $_FILES['uploadfile']['name'] . ')';
	} else $result = __('You are not administrator of this site');
if (!empty($result)) echo $result;
?>