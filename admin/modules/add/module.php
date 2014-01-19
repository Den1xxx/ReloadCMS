<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Core controls');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1]['config'] = __('Site configuration');
    $MODULES[$category][1]['navigation'] = __('Navigation panel');
    $MODULES[$category][1]['logging'] = __('Control logs');
    $MODULES[$category][1]['statistic'] = __('Site statistics');
    $MODULES[$category][1]['search'] = __('Search configuration');
    $MODULES[$category][1]['avatars'] = __('Avatars configuration');
    $MODULES[$category][1]['smiles'] = __('Smiles configuration');
    $MODULES[$category][1]['rss'] = __('Configuration').' '.__('RSS');
    $MODULES[$category][1]['skins'] = __('Skins');
}
?>