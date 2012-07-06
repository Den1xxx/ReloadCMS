<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Polls control');
if($system->checkForRight('POLL')) {
    $MODULES[$category][1]['polls'] = __('Polls');
    $MODULES[$category][1]['archive'] = __('Archive');
}
?>