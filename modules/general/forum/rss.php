<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$forum = new forum();
$topics = $forum->getFreshTopics($system->config['num_of_latest']);
foreach ($topics as $topic_id) {
	$topic = $forum->getTopicData($topic_id);
	if($topic['replies'] == 0) {
		$title = __('New topic') . ': ' . $topic['title'] . ' [' . $topic['author_nick'] . '/' . rcms_format_time('H:i:s d.m.Y', $topic['date']) . ']';
	} else {
		$title = __('New message in topic') . ': ' . $topic['title'] . ' [' . @$topic['last_reply_author'] . '/' . rcms_format_time('H:i:s d.m.Y', $topic['last_reply']) . ']';
	}
    $feed->addItem($title, $title . $topic['title'], 'http://'.$_SERVER['HTTP_HOST']. '?module=' . $module . '&amp;action=topic&amp;id=' . $topic_id . '&amp;pid=' . (@$topic['last_reply_id'] + 2) . '#' . (@$topic['last_reply_id'] + 2), $topic['last_reply']);
}
?>
