<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Tools');
if($system->checkForRight('GENERAL')) {
    $MODULES[$category][1]['lightbox'] = __('Javascript');
	$MODULES[$category][1]['sitemap'] = __('Site map');
    $MODULES[$category][1]['sendmail'] = __('Manage sendmail');
    $MODULES[$category][1]['uploads'] = __('Uploads management');
    $MODULES[$category][1]['filemanager'] = __('File manager');
    $MODULES[$category][1]['rss_agg'] = __('RSS Aggregator');
    $MODULES[$category][1]['backup'] = __('Backups management');
}
if($system->checkForRight('UPLOAD'))    $MODULES[$category][1]['uploads'] = __('Uploads management');
?>