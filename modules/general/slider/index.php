<?php
if (!defined('SLIDER_PATH')) define ('SLIDER_PATH',DATA_PATH . 'slider/');
show_window('', @file_get_contents(SLIDER_PATH.'code.html'));
?>