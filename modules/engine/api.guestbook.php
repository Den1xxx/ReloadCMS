<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

function guestbook_get_msgs($page=0, $parse=true, $limited=true, $file=RCMS_GB_DEFAULT_FILE, $config='guestbook.ini' ) {
return get_messages($page, $parse, $limited, $file, $config);
}

function guestbook_get_last_msgs($num = 10, $parse = true, $limited, $file = RCMS_GB_DEFAULT_FILE, $config = 'guestbook.ini') {
return get_last_messages($num, $parse, $limited, $file, $config);
}

function guestbook_get_pages_num($file = RCMS_GB_DEFAULT_FILE, $config = 'guestbook.ini') {
return get_pages_number($file, $config);
}

function guestbook_post_msg($username, $nickname, $text, $file = RCMS_GB_DEFAULT_FILE, $config = 'guestbook.ini') {
return post_message($username, $nickname, $text, $file, $config);
}

function guestbook_post_remove($id, $file = RCMS_GB_DEFAULT_FILE, $config = 'guestbook.ini') {
	return post_remove($id, $file, $config);
}
?>