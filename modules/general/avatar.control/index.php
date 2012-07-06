<?
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if ((isset($_POST['upload_avatar'])) AND ($_POST['upload_avatar']=="true"))
{
       upload_avatar();
}
$config_ext = parse_ini_file(CONFIG_PATH . 'avatars.ini');
$avatars_enabled=parse_ini_file(CONFIG_PATH . 'disable.ini');
 if(LOGGED_IN){
  if(!isset($avatars_enabled['avatar.control'])) {
show_window(__('Your current avatar'),show_new_avatar($system->user['username']),'center');
show_window(__('Update your avatar'),show_avatar_requirements().'<br/>'.avatar_upload_box(),'center'); }}
?>
