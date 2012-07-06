<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['support_req']) && LOGGED_IN) {
		post_message($system->user['username'], $system->user['nickname'], $system->user['email'] . "\n" . $_POST['support_req'], DF_PATH . 'support.dat');
		show_window('', __('Message sent'), 'center');
} if (!LOGGED_IN) show_window(__('Error'),__('You are not logined!'));
$result = '<form method="post" action="" name="form1">';
$result .= '<textarea name="support_req" cols="70" rows="7"></textarea>
<p align="center"><input type="submit" value="' . __('Submit') . '" /></p>
</form>';
if (LOGGED_IN) show_window(__('Feedback request'), $result, 'center');
$system->config['pagename'] = __('Feedback');
?>
