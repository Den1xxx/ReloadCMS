<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Modules');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1]['menus'] = __('Menus management');
    $MODULES[$category][1]['ucm'] = __('User-Created-Menus');
    $MODULES[$category][1]['installer'] = __('Install modules');
    $MODULES[$category][1]['uninstaller'] = __('Uninstall modules');
    $MODULES[$category][1]['module_dis'] = __('Enable').'/'.__('Disable');
}
?>