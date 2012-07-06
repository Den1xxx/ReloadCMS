<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Gallery');
if($system->checkForRight('GALLERY')){
	$MODULES[$category][1]['images'] = __('Images management');
	$MODULES[$category][1]['index'] = __('Indexes management');
	$MODULES[$category][1]['upload'] = __('Upload images');
}
?>