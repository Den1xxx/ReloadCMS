<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Static pages');
if($system->checkForRight('ARTICLES-EDITOR')) {
    $MODULES[$category][1]['manage'] = __('Manage articles');
}
?>