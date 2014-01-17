<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

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
$topics = @unserialize(@file_get_contents(FORUM_PATH . 'topic_index.dat'));
$sysconfig = parse_ini_file(CONFIG_PATH . 'config.ini');

if(!is_array($topics)) $topics = array();
$result = '';

if($action == 'new_topic' && !empty($_POST['new_topic_perform'])){
    $new_topic_text = (empty($_POST['new_topic_text'])) ? '' : $_POST['new_topic_text'];
    $new_topic_title = (empty($_POST['new_topic_title'])) ? '' : htmlspecialchars($_POST['new_topic_title']);
    if (isset($sysconfig['forum-guest']) and !LOGGED_IN){
    	show_error(__('You are not logined!'));
    } else {
    if(!empty($new_topic_title) && !empty($new_topic_text)){
        if(mb_strlen(str_replace(array("\n","\r"),array("",""),$new_topic_text)) <= $max_topic_len && mb_strlen($new_topic_title) <= $max_topic_title){
            $time = rcms_get_time();
            if($system->checkForRight('FORUM')){
                $new_topic_sticky = (empty($_POST['new_topic_sticky'])) ? false : true;
                $new_topic_closed = (empty($_POST['new_topic_closed'])) ? false : true;
            } else {
                $new_topic_sticky = false;
                $new_topic_closed = false;
            }
	if	(is_file(FORUM_PATH . 'last_topic.dat')) {
			$last_topic =(int)file_get_contents(FORUM_PATH . 'last_topic.dat');}
			else	{
			$tmp_array = $topics;
			krsort($tmp_array);
			$last_topic = key($tmp_array);//maximum key
			}
			$last_topic = $last_topic + 1;		
            $topics[$last_topic] = array(
                'title' => $new_topic_title,
                'text' => $new_topic_text,
                'author_nick' => $system->user['nickname'],
                'author_name' => $system->user['username'],
                'date' => $time,
                'author_ip' => $_SERVER['REMOTE_ADDR'],  // Matrix haz you neo ;) 
                'last_reply' => $time,
                'last_reply_id' => 0,
                'last_reply_author' => $system->user['nickname'],
                'replies' => 0,
                'sticky' => $new_topic_sticky,
                'closed' => $new_topic_closed,
            ); 
			
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
            if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
			file_write_contents(FORUM_PATH . 'last_topic.dat', $last_topic);
                rcms_redirect('?module=' . $module, true);
                $action = '';
            }
} else {
show_window(__('Error'),__('Invalid form data'));
}
} else {			
            if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
				file_write_contents(FORUM_PATH . 'last_topic.dat', $last_topic);
                rcms_redirect('?module=' . $module, true);
                $action = '';
            }
}					
        } else {
            show_error(__('Message text or title are too long'));
        }
    } else {
        show_error(__('Message text or title are empty'));
    }
	}
}

if(!empty($_POST['new_post_perform'])){
    $new_post_text = (empty($_POST['new_post_text'])) ? '' : $_POST['new_post_text'];
    $topic_id = (empty($_POST['new_post_topic'])) ? 0 : (int)$_POST['new_post_topic'];
    if (isset($sysconfig['forum-guest']) and !LOGGED_IN){
    	show_error(__('You are not logined!'));
    } else {
    if(!empty($new_post_text)){
        if(!empty($topics[$topic_id])){
            if(mb_strlen($new_post_text) <= $max_message_len){
                if(!$topics[$topic_id]['closed']){
                    $time = rcms_get_time();
                    $topics[$topic_id]['replies']++;
                    $topics[$topic_id]['last_reply'] = $time;
                    $topics[$topic_id]['last_reply_author'] = $system->user['nickname'];
                    $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat'));
                    if(!is_array($posts)) $posts = array();
                    $keys = array_keys($posts);
                    rsort($keys, SORT_NUMERIC);
                    if(empty($keys[0])) $keys[0] = 0;
                    $post_id = $keys[0] + 1;
                    $posts[$post_id] = array(
                        'author_nick' => $system->user['nickname'],
                        'author_name' => $system->user['username'],
                        'date' => $time,
                        'author_ip' => $_SERVER['REMOTE_ADDR'],  // Matrix haz you neo again ;) 
                        'text' => $new_post_text,
                    );
                    $topics[$topic_id]['last_reply_id'] = $post_id;
if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) { 
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{	
                    if(file_write_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat', serialize($posts)) && file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
                        rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id . '&pid=' . ($post_id + 2) . '#' . ($post_id + 2), true);
                        $action = '';
                    }
}		
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {
                    if(file_write_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat', serialize($posts)) && file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
                        rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id . '&pid=' . ($post_id + 2) . '#' . ($post_id + 2), true);
                        $action = '';
                    }
}				
                } else {
                    show_error(__('Topic is closed'));
                }
            } else {
                show_error(__('Message text is too long'));
            }
        } else {
            show_error(__('There is no topic with this id'));
        }
    } else {
        show_error(__('Message text is empty'));
    }
    }
}

if(!empty($_POST['edit_submit']) && !empty($_GET['p'])){
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    $post_id = (int)$_GET['p'] - 2;
    $new_text = (empty($_POST['text'])) ? '' : $_POST['text'];
    if(!empty($new_text)){
        if(mb_strlen(str_replace(array("\n","\r"),array("",""),$new_text)) <= $max_topic_len){
            if(!empty($topics[$topic_id])){
                $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat'));
                if(!empty($posts[$post_id])){
                    if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $posts[$post_id]['author_name'])){
                        $posts[$post_id]['text'] = $new_text;
                        if(file_write_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat', serialize($posts))){
                            rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id . '&pid=' . ($post_id + 2) . '#' . ($post_id + 2), true);
                            $action = '';
                        }
                    } else {
                        show_error(__('You cannot edit this post'));
                    }
                } else {
                    show_error(__('There is no post with this id'));
                }
            } else {
                show_error(__('There is no topic with this id'));
            }
        } else {
            show_error(__('Message text or title are too long'));
        }
    } else {
        show_error(__('Message text or title are are empty'));
    }
}

if(!empty($_POST['edit_submit']) && empty($_GET['p'])){
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    $new_title = (empty($_POST['title'])) ? '' : htmlspecialchars($_POST['title']);
    $new_text = (empty($_POST['text'])) ? '' : $_POST['text'];
    if(!empty($new_title) && !empty($new_text)){
        if(mb_strlen(str_replace(array("\n","\r"),array("",""),$new_text)) <= $max_topic_len && mb_strlen($new_title) <= $max_topic_title){
            if(!empty($topics[$topic_id])){
                if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $topics[$topic_id]['author_name'])){
                    $topics[$topic_id]['text'] = $new_text;
                    $topics[$topic_id]['title'] = $new_title;
                    if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
                        rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id, true);
                        $action = '';
                    }
                } else {
                    show_error(__('You cannot edit this topic'));
                }
            } else {
                show_error(__('There is no topic with this id'));
            }
        } else {
            show_error(__('Message text or title are too long'));
        }
    } else {
        show_error(__('Message text or title are are empty'));
    }
}

if($action == 'new_topic'){
    $system->config['pagename'] = __('New topic');
    if (isset($sysconfig['forum-guest']) and !LOGGED_IN){
    	show_error(__('You are not logined!'));
    } else {
    	show_window(__('New topic'), rcms_parse_module_template('forum-new-topic.tpl', array(@$new_topic_title, @$new_topic_text)));
    }
} elseif($action == 'oc_topic' && $system->checkForRight('FORUM')) {
    $topic_id = (empty($_GET['id'])) ? 0 : (int)$_GET['id'];
    $topics[$topic_id]['closed'] = !$topics[$topic_id]['closed'];
    if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
        rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id, true);
        $action = '';
    }
} elseif($action == 'st_topic' && $system->checkForRight('FORUM')) {
    $topic_id = (empty($_GET['id'])) ? 0 : (int)$_GET['id'];
    $topics[$topic_id]['sticky'] = !$topics[$topic_id]['sticky'];
    if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
        rcms_redirect('?module=' . $module . '&action=topic&id=' . $topic_id, true);
        $action = '';
    }
} 

elseif ($action == 'arch_topic') {
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    if(!empty($topics[$topic_id])){
        if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $topics[$topic_id]['author_name'])){
	$archive = @unserialize(@file_get_contents(FORUM_PATH . 'archive/topic_index.dat'));
	$archive[$topic_id]=$topics[$topic_id];
if (file_write_contents(FORUM_PATH . 'archive/topic_index.dat', serialize($archive))){            
            rcms_remove_index($topic_id, $topics, true);
            if(is_file(FORUM_PATH . 'topic.' . $topic_id . '.dat')) rcms_rename_file(FORUM_PATH . 'topic.' . $topic_id . '.dat',FORUM_PATH . 'archive/topic.' . $topic_id . '.dat');//Не удаляем, ложим в архив! 
            if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
                rcms_redirect('?module=' . $module, true);
                $action = '';
            }
        }
		} else {
            show_error(__('You cannot archive this topic'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif ($action == 'del_topic') {
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    if(!empty($topics[$topic_id])){
        if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $topics[$topic_id]['author_name'] && $topics[$topic_id]['replies'] == 0)){
            rcms_remove_index($topic_id, $topics, true);
            if(is_file(FORUM_PATH . 'topic.' . $topic_id . '.dat')) rcms_delete_files(FORUM_PATH . 'topic.' . $topic_id . '.dat');
            if(file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
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
    if(!empty($topics[$topic_id])){
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat'));
        if(!empty($posts[$post_id])){
            if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $posts[$post_id]['author_name'])){
                $topics[$topic_id]['replies']--;
                rcms_remove_index($post_id, $posts, true);
                if($topics[$topic_id]['replies'] != 0 && $topics[$topic_id]['last_reply_id'] == $post_id){
                    $dates = array();
                    foreach ($posts as $post_id => $post){
                        if(!empty($post)){
                            $dates[$post['date']] = array($post_id, $post['date']);
                        }
                    }
                    krsort($dates, SORT_NUMERIC);
                    $keys = array_keys($dates);
                    $topics[$topic_id]['last_reply_id'] = $dates[$keys[0]][0];
                    $topics[$topic_id]['last_reply'] = $dates[$keys[0]][1];
                } elseif ($topics[$topic_id]['replies'] == 0){
                    $topics[$topic_id]['last_reply_id'] = 0;
                    $topics[$topic_id]['last_reply'] = $topics[$topic_id]['date'];
                }
                if(file_write_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat', serialize($posts)) && file_write_contents(FORUM_PATH . 'topic_index.dat', serialize($topics))){
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
} elseif($action == 'ed_post') {
    $system->config['pagename'] = __('Edit post');
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    $post_id = (empty($_GET['p'])) ? 0 : (int)$_GET['p'] - 2;
    if(!empty($topics[$topic_id])){
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat'));
        if(!empty($posts[$post_id])){
            if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $posts[$post_id]['author_name'])){
                show_window(__('Edit post'), rcms_parse_module_template('forum-edit.tpl', array('', $posts[$post_id]['text'], $topic_id, $post_id + 2)));
            } else {
                show_error(__('You cannot edit this post'));
            }
        } else {
            show_error(__('There is no post with this id'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif($action == 'ed_topic') {
    $system->config['pagename'] = __('Edit topic');
    $topic_id = (empty($_GET['t'])) ? 0 : (int)$_GET['t'];
    if(!empty($topics[$topic_id])){
        if($system->checkForRight('FORUM') || ($system->user['username'] != 'guest' && $system->user['username'] == $topics[$topic_id]['author_name'])){
            show_window(__('Edit topic'), rcms_parse_module_template('forum-edit.tpl', array($topics[$topic_id]['title'], $topics[$topic_id]['text'], $topic_id, 0)));
        } else {
            show_error(__('You cannot edit this topic'));
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} elseif($action == 'topic'){
    $topic_id = (empty($_GET['id'])) ? 0 : basename((int)$_GET['id']);
    if(!empty($topics[$topic_id])){
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $topic_id . '.dat'));
        if(!is_array($posts)) $posts = array();
        $topics[$topic_id]['id'] = $topic_id;
        $system->config['pagename'] = __('Forum').': '.__('Show topic') . ': ' . $topics[$topic_id]['title'];
        show_window(__('Forum').': '.__('Show topic') . ' - "' . $topics[$topic_id]['title'] . '"', rcms_parse_module_template('forum-messages.tpl', array('topic' => &$topics[$topic_id], 'posts' => &$posts)));
        if(!$topics[$topic_id]['closed']){
        	if (isset($sysconfig['forum-guest']) and !LOGGED_IN){
    			show_window(__('Post comment'), __('You are not logined!'), 'center');
    		} else {
            	show_window(__('Reply to topic'), rcms_parse_module_template('forum-new-post.tpl', array($topic_id, @$new_post_text)));
            }
        }
    } else {
        show_error(__('There is no topic with this id'));
    }
} else {
    $system->config['pagename'] = __('Forum');
    show_window(__('Forum'), rcms_parse_module_template('forum-topics.tpl', $topics));
}
?>
