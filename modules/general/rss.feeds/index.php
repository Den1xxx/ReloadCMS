<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($system->config['enable_rss'])){
    $feeds = &$system->feeds;
    $data = '';
    foreach ($feeds as $module => $d) {
        $data .= '<img src="'.IMAGES_PATH.'skins/rss.png" alt="RSS"/>&nbsp;<a href="./rss.php?m=' . $module . '">' . $d[0] . '</a><br />';
    }
    if(!empty($data)) show_window(__('RSS Feeds'), $data);
}
?>