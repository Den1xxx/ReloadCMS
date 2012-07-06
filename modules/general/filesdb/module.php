<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$this->registerModule($module, 'main', __('FilesDB'), 'ReloadCMS Team', array(
    'FILESDB' => __('Right to add/edit/delete files and categories of files in filesdb')
));
$this->registerFeed($module, __('FilesDB'), __('Files feed'));
?>