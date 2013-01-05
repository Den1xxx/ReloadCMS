<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Communion');
if($system->checkForRight('GENERAL') || $system->checkForRight('FORUM')) $MODULES[$category][1]['forum'] = __('Forum configuration');
if($system->checkForRight('GUESTBOOK')) $MODULES[$category][1]['guestbook'] = __('Guest book configuration');
if($system->checkForRight('MINICHAT')) $MODULES[$category][1]['minichat'] = __('Minichat configuration');
if($system->checkForRight('GENERAL') || $system->checkForRight('GENERAL-M')) $MODULES[$category][1]['feedback'] = __('Feedback');
?>