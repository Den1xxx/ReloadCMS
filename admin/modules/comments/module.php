<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Comments');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1]['comments'] = __('Comments configuration');
}
?>