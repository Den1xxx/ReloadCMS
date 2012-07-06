<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if (!LOGGED_IN) show_window(__('Forum').': '.__('Archive'), __('You are not logined!').
'&nbsp;&nbsp;&nbsp;[<a href="?module=forum">'.__('Forum').'</a>]&nbsp;&nbsp;&nbsp;[<a href="?module=user.profile&act=register">'.__('Registration').'</a>]', 'center');
else {
function rcms_get_user_status($username){
    global $system;
    if($username == 'guest') return __('Guest');
    $system->getRightsForUser($username, $rights, $root, $level);
    if($root) return __('Administrator');
    if(user_check_right($username, 'FORUM')) return __('Moderator');
    if($system->checkForRight('-any-', $username)) return __('Advanced user');
    return __('User');
}
//Add by Den1xxx;
//Initialize:
$config = parse_ini_file(CONFIG_PATH . 'forum.ini');
$max_topic_title = $config['max_topic_title'];
$max_topic_len = $config['max_topic_len'];
$max_message_len = $config['max_message_len'];
if (!empty($config['sorting'])) $config['sorting'] = true; else $config['sorting'] = false;
//End add by Den1xxx;

$action = (empty($_GET['action'])) ? '' : $_GET['action'];
$archives = @unserialize(@file_get_contents(FORUM_PATH . 'archive/topic_index.dat'));
$sysconfig = parse_ini_file(CONFIG_PATH . 'config.ini');

if(!is_array($archives)) $archives = array();
$result = '';
$module = 'forum.archive';

if($action == 'restore_topic') {
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    if(!empty($archives[$topic_id])){
        if($system->checkForRight('FORUM') ){
	$topics = @unserialize(@file_get_contents(FORUM_PATH . 'topic_index.dat'));
	$topics[$topic_id]=$archives[$topic_id];
	rcms_remove_index($topic_id, $archives, true);
if (file_write_contents(FORUM_PATH . 'archive/topic_index.dat', serialize($archives))){            
            if(is_file(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat')) rcms_rename_file(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat',FORUM_PATH . 'topic.' . $topic_id . '.dat');//Восстановить взад на место! 
            if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
                rcms_redirect('?module=' . $module, true);
                $action = '';
            }
        }
		} else {
            show_error(__('You cannot restore this topic'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif ($action == 'del_topic') {
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    if(!empty($archives[$topic_id])){
        if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $archives[$topic_id]['author_name'] && $archives[$topic_id]['replies'] == 0)){
            rcms_remove_index($topic_id, $archives, true);
            if(is_file(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat')) rcms_delete_files(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat');
            if(file_write_contents(FORUM_PATH . 'archive/topic_index.dat', serialize($archives))){
                rcms_redirect('?module=' . $module, true);
                $action = '';
            }
        } else {
            show_error(__('You cannot delete this topic'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif($action == 'del_post') {
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    $post_id = (empty($_GET['p'])) ? 0 : (int)$_GET['p'] - 2;
    if(!empty($archives[$topic_id])){
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat'));
        if(!empty($posts[$post_id])){
            if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $posts[$post_id]['author_name'])){
                $archives[$topic_id]['replies']--;
                rcms_remove_index($post_id, $posts, true);
                if($archives[$topic_id]['replies'] != 0 && $archives[$topic_id]['last_reply_id'] == $post_id){
                    $dates = array();
                    foreach ($posts as $post_id => $post){
                        if(!empty($post)){
                            $dates[$post['date']] = array($post_id, $post['date']);
                        }
                    }
                    krsort($dates, SORT_NUMERIC);
                    $keys = array_keys($dates);
                    $archives[$topic_id]['last_reply_id'] = $dates[$keys[0]][0];
                    $archives[$topic_id]['last_reply'] = $dates[$keys[0]][1];
                } elseif ($archives[$topic_id]['replies'] == 0){
                    $archives[$topic_id]['last_reply_id'] = 0;
                    $archives[$topic_id]['last_reply'] = $archives[$topic_id]['date'];
                }
                if(file_write_contents(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat', serialize($posts)) && file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($archives))){
                    rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id, true);
                    $action = '';
                }
            } else {
                show_error(__('You cannot delete this post'));
            }
        } else {
            show_error(__('There is no post with this id'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif($action == 'topic'){
    $topic_id = (empty($_GET['id'])) ? 0 : basename((int)$_GET['id']);
    if(!empty($archives[$topic_id])){
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'archive/topic.' . $topic_id . '.dat'));
        if(!is_array($posts)) $posts = array();
        $archives[$topic_id]['id'] = $topic_id;
        $system->config['pagename'] = __('Archive').': '.__('Show topic') . ': ' . $archives[$topic_id]['title'];
        show_window(__('Archive').': '.__('Show topic') . ' - "' . $archives[$topic_id]['title'] . '"', rcms_parse_module_template('forum.archive-messages.tpl', array('topic' => &$archives[$topic_id], 'posts' => &$posts)));
        if(!$archives[$topic_id]['closed']){
        	if (isset($sysconfig['forum.archive-guest']) and !LOGGED_IN){
    			show_window(__('Post comment'), __('You are not logined!'), 'center');
    		} else {
            	show_window(__('Reply to topic'), rcms_parse_module_template('forum.archive-new-post.tpl', array($topic_id, @$new_post_text)));
            }
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} else {
    $system->config['pagename'] =__('Forum').': '.__('Archive');
    show_window(__('Forum').': '.__('Archive'), rcms_parse_module_template('forum.archive-topics.tpl', $archives));
}
}
?>
