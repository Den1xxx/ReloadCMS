<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = __('Forum');
$forum = new forum();
if (!empty($forum->topics)) {
	foreach ($forum->topics as $topic_id => $topic) {
	$sitemap -> addUrl(
			$directory . '?module=forum&action=topic&id='.$topic_id,
			rcms_format_time('Y-m-d', $topic['last_reply']),
			$chfr,
			$prio);
	}
}
?>
