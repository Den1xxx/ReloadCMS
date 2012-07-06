<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Guest books');
if($system->checkForRight('MINICHAT')) {
    $MODULES[$category][1]['minichat'] = __('Minichat configuration');
}
if($system->checkForRight('GUESTBOOK')) {
    $MODULES[$category][1]['config'] = __('Guest book configuration');
}
?>