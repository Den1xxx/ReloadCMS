<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('RSS Aggregator');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1] = __('RSS Aggregator');
}
?>