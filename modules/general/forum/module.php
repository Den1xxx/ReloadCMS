<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$this->registerModule($module, 'main', __('Forum'), 'ReloadCMS Team', array('FORUM' => __('Right to moderate forums')));
$this->registerFeed($module, __('Forum'), __('Feed for latest forum updates'));
?>