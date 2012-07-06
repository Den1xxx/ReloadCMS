<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$MODULES[$category][0] = __('Articles');
if($system->checkForRight('ARTICLES-ADMIN')) $MODULES[$category][1]['containers'] = __('Manage sections');
if($system->checkForRight('ARTICLES-ADMIN')) $MODULES[$category][1]['categories'] = __('Manage categories');
if($system->checkForRight('ARTICLES-EDITOR')) $MODULES[$category][1]['articles'] = __('Manage articles');
if($system->checkForRight('ARTICLES-EDITOR')) $MODULES[$category][1]['post'] = __('Post article');
if($system->checkForRight('ARTICLES-ADMIN')) $MODULES[$category][1]['config'] = __('Show');
?>