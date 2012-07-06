<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('FilesDB');
if($system->checkForRight('FILESDB')) {
    $MODULES[$category][1]['categories'] = __('Manage categories of files');
    $MODULES[$category][1]['files'] = __('Manage files');
}
?>