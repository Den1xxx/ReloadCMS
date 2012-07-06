<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$this->registerModule($module, 'main', __('Guest book'), 'ReloadCMS Team', array(
    'GUESTBOOK' => __('Right to moderate and configure guest book'),
));
$this->registerFeed($module, __('Guest book'), __('Feed for messages in guest book'));
?>