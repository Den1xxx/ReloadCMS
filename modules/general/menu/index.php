<?php
if(!defined('HIDE_NAVIGATION') || !HIDE_NAVIGATION){
    show_window(__('Navigation'), rcms_parse_menu('<a href="{link}" class="{class}" title="{desc}">{icon}  {title}</a><br />'));
}
?>