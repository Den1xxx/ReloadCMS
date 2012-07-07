<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

function format_size($i){
  if (floor($i / 1073741824) > 0) { return sprintf("%.2f Gb", $i / (1024 * 1024 * 1024));} elseif 
  (floor($i / 1048576) > 0) { return sprintf("%.2f Mb", $i / (1024 * 1024));} elseif
  (floor($i / 1024) > 0) { return sprintf("%.2f Kb", $i / 1024);} elseif
  ($i < 1024) { return $i." Byte(s)";}
}

function get_dir_size($dir){
	$size = 0;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	if (is_dir($dir.$file)){
        		if (($file != '.') and ($file != '..')) {
        			$size += get_dir_size($dir.$file.'/');
        		}
        	} else {
            $size += filesize($dir.$file);
          }
        }
        closedir($dh);
    }
  return $size;
}

function get_user_count()
{
	global $system;
	$userlist = $system->getUserList('*', 'username');
	return count($userlist);
}

if(isset($_POST['remarks'])) {
	file_write_contents(DATA_PATH . 'admin_remarks.txt', $_POST['remarks']);
}
$frm = new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('Welcome to administration panel'));
if(!$root) {
	foreach ($rights as $right => $right_desc) {
		$frm->addrow($right, $right_desc, 'top');
	}
} else {
	$frm->addrow(__('You have all rights on this site'));
}
$frm->addbreak(__('Information'));
$frm->addrow('httpd', $_SERVER['SERVER_SOFTWARE']);
$frm->addrow('php', phpversion());
$frm->addrow('ReloadCMS', RCMS_VERSION_A . '.'  . RCMS_VERSION_B . '.' . RCMS_VERSION_C . RCMS_VERSION_SUFFIX);

$frm->addbreak(__('Additional information'));
$frm->addrow(__('News').', '.__('Articles'), format_size(get_dir_size(DATA_PATH.'a/')));
$frm->addrow(__('Various datafiles'), format_size(get_dir_size(DF_PATH)));
$frm->addrow(__('Users profiles').' ('.get_user_count().')', format_size(get_dir_size(USERS_PATH)+get_dir_size(DATA_PATH.'avatars/') + get_dir_size(DATA_PATH.'pm/')));
$frm->addrow(__('Uploads'), format_size(get_dir_size(FILES_PATH)));
if (defined('GALLERY_PATH')) $frm->addrow(__('Gallery'), format_size(get_dir_size(GALLERY_PATH)));
if (defined('FORUM_PATH')) $frm->addrow(__('Forum'), format_size(get_dir_size(FORUM_PATH)));
$frm->addrow(__('Logs'), format_size(get_dir_size(LOGS_PATH)));
$frm->addrow(__('Config'), format_size(get_dir_size(CONFIG_PATH)));
$frm->addrow(__('Backups'), format_size(get_dir_size(BACKUP_PATH)));
$frm->addrow(__('Skins'), format_size(get_dir_size(SKIN_PATH)));
$frm->addrow(__('All'), format_size(get_dir_size(RCMS_ROOT_PATH)));

if($system->checkForRight('ARTICLES-EDITOR')){
	$frm->addbreak(__('Information from users'));
	$articles = new articles();
	$articles->setWorkContainer('#hidden');
	$count = sizeof($articles->getArticles(0, false, false, false));
	$frm->addrow($count . ' ' . __('article(s) awaits moderation'));
}
if($system->checkForRight('SUPPORT')) {
	$count = sizeof(get_messages(null, true, false, DF_PATH . 'support.dat'));
	$frm->addrow($count . ' ' . __('feedback requests in database'));
}
$frm->addbreak(__('Here you can leave message for other administrators'));
$frm->addrow($frm->textarea('remarks', file_get_contents(DATA_PATH . 'admin_remarks.txt'), 60, 10), '', 'middle', 'center');
$frm->show();
?>