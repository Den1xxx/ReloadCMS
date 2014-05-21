<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

define('RCMS_GB_DEFAULT_FILE', DF_PATH . 'guestbook.dat');
define('RCMS_MC_DEFAULT_FILE', DF_PATH . 'minichat.dat');
$_CACHE['gbook'] = array();

/*
* Return all messages from comment file
*
* @page integer Start page for pagination
* @parse boolean Parse text or not
* @limited boolean Safe parsing (not show links and images) or not
* @file string File for parsing, full path
* @config string Config file in configuration directory, only name
*
* @return array Array comments
*/
function get_messages($page = 0, $parse = true, $limited = true, $file = RCMS_GB_DEFAULT_FILE, $config = 'comments.ini') {
	global $_CACHE, $system, $comment_config;
	$comment_config = parse_ini_file(CONFIG_PATH . $config);
	$bbcodes = empty($comment_config['bbcodes']) ? false : true;
	$img = empty($comment_config['links']) ? false : true;
	$data = &$_CACHE['gbook'][$file];
	if(!isset($data)) {
		if (!is_readable($file) || !($data = unserialize(file_get_contents($file)))) $data = array();
	}
	if(!empty($data)){
		$c    = sizeof($data);
		$ndata = $rdata = array();
		foreach ($data as $key => $value) $ndata[$key . ''] = $value;
		$ndata = array_reverse($ndata, true);
		if($page !== null){
			$i = 0;
			while($i < (($page+1) * $system->config['perpage']) && $el = each($ndata)){
				if($i >= $page * $system->config['perpage']) $rdata[$el['key']] = $el['value'];
				$i++;
			}
		} else {
			$rdata = $ndata;
		}
		if($parse){
			foreach($rdata as $id => $msg){
			if (!$bbcodes && !$img) $rdata[$id]['text'] = htmlspecialchars($msg['text']);
			else if(!empty($msg)) $rdata[$id]['text'] = rcms_parse_text($msg['text'], !$limited, false, !$limited,$bbcodes,$img);
			}
		}
		return $rdata;
	} else return array();
}

/*
* Return last messages from comment file
*
* @num integer Number last messages
* @parse boolean Parse text or not
* @limited boolean Safe parsing (not show links and images) or not
* @file string File for parsing, full path
* @config string Config file in configuration directory, only name
*
* @return array Array comments
*/
function get_last_messages($num = 10, $parse = true, $limited=false, $file = RCMS_GB_DEFAULT_FILE, $config = 'comments.ini') {
$tmp_arr=get_messages(0, $parse, $limited, $file, $config);
return array_slice($tmp_arr,0,$num,true);
}

/*
* Return count comments from file
*
* @file string File for parsing, full path
*
* @return integer
*/
function get_pages_number($file = RCMS_GB_DEFAULT_FILE) {
	global $_CACHE, $system;
	$data = &$_CACHE['gbook'][$file];
	if(!isset($data)) {
		if (!is_readable($file) || !($data = unserialize(file_get_contents($file)))) $data = array();
	}
	if(!empty($system->config['perpage'])) {
		return ceil(sizeof($data)/$system->config['perpage']);
	} else return 1;
}

/*
* Posting message in comment file
*
* @username string Name user
* @nickname string User nick
* @text string Text to add
* @file string File for parsing, full path
* @config string Config file in configuration directory, only name
*
* @return boolean
*/
function post_message($username, $nickname, $text, $file = RCMS_GB_DEFAULT_FILE, $config = 'comments.ini') {
	global $_CACHE, $comment_config;
	$text = trim($text);
	if(empty($text)) return false;
	$comment_config = parse_ini_file(CONFIG_PATH . $config);
	$data = &$_CACHE['gbook'][$file];
	if(!isset($data)) {
		if (!is_readable($file) || !($data = unserialize(file_get_contents($file)))) $data = array();
	}
	if(!empty($comment_config['max_db_size'])) $data = array_slice($data, -$comment_config['max_db_size']+1);
	$newmesg['username'] = $username;
	$newmesg['nickname'] = (!empty($comment_config['max_word_len']) && mb_strlen($nickname) > $comment_config['max_word_len']) ? '<abbr title="' . htmlspecialchars($nickname) . '">' . mb_substr($nickname, 0, $comment_config['max_word_len']) . '</abbr>' : htmlspecialchars($nickname);
	$newmesg['time'] = rcms_get_time();
	$newmesg['text'] = (mb_strlen($text) > $comment_config['max_message_len']) ? mb_substr($text, 0, $comment_config['max_message_len']) : $text;
	$data[] = $newmesg;
	return file_write_contents($file, serialize($data));
}

/*
* Remove message from comment file
*
* @id Message number in file
* @file string File for parsing, full path
*/
function post_remove($id, $file = RCMS_GB_DEFAULT_FILE) {
	global $_CACHE;
	$data = &$_CACHE['gbook'][$file];
	if(!isset($data)) {
		if (!is_readable($file) || !($data = unserialize(file_get_contents($file)))) $data = array();
	}
	rcms_remove_index($id, $data, true);
	if (file_write_contents($file, serialize($data))) show_window('',__('Comment removed'));
	else show_window(__('Error'),__('Comment are not removed!'));
}
?>