<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

$messages = guestbook_get_msgs(0, true);
foreach ($messages as $id => $message) {
    $feed->addItem(__('Message by') . ' ' . $message['nickname'],
    htmlspecialchars($message['text']),
    'http://'.$_SERVER['HTTP_HOST']. '?module=' . $module,
    $message['time']);
}
?>