<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Images resizing');
if($system->checkForRight('GENERAL') || $system->checkForRight('GALLERY') || $system->checkForRight('ARTICLES-EDITOR')) {
    $MODULES[$category][1] = __('Images resizing');
}
?>