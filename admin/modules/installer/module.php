<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Installer');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1]['installer'] = __('Install modules');
    $MODULES[$category][1]['uninstaller'] = __('Uninstall modules');
}
?>