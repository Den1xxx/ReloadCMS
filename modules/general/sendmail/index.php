<?php 
if (!empty($_POST['new_letter'])) {
    if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
        $defcatp = substr(md5($_POST['antispam']),0,5);
        $intcapt = $_POST['captcheckout'];
        if ($defcatp == $intcapt) {
            if (!empty($_POST['sender_name'])) {
                $sender = trim(htmlspecialchars($_POST['sender_name']));
                if (rcms_is_valid_email(@$_POST['sender_email'])) {
                    if (!empty($_POST['subject'])) {
                        $subject = trim(htmlspecialchars($_POST['subject']));
                        if (!empty($_POST['letter'])) {
                            $letter = trim(htmlspecialchars($_POST['letter']));
                            if (!empty($system->config['admin_email']))	{
										rcms_send_mail($system->config['admin_email'],
										$_POST['sender_email'],
										$sender,
										$system->config['encoding'],
										$subject,
										$letter);
								show_window('', __('Message sent'), 'center');
								unset($_POST);
							}	 else show_window(__('Error'), __('Admin e-mail is empty'), 'center');
                        } else show_window(__('Error'), __('Letter is empty'), 'center');
                    } else show_window(__('Error'), __('Subject of your letter'), 'center');
                } else show_window(__('Error'), __('Error in email field'), 'center');
            } else show_window(__('Error'), __('Sender name').__(' is empty') , 'center');
        } else show_window(__('Error'), __('Error of a control code'), 'center');
    } else show_window(__('Error'), __('Error of a control code'), 'center');
}
if (!empty($_GET['get'])) 
if (is_file(DATA_PATH . 'sendmail/'.$_GET['get'])) $data = unserialize(file_get_contents(DATA_PATH . 'sendmail/'.$_GET['get']));
if (empty ($data)) {
$data['title'] = __('The letter to admin');
$data['comment'] = '';
$data['sender_name'] = __('Your name');
$data['sender_email'] = __('Your e-mail for answer');
$data['subject'] = __('Subject of your letter');
$data['letter'] = __('Text of your letter');
$data['important'] = __('It is important!');
$data['important_text'] = __('Your letter will be sent to admin and it will not be kept on a site. Admin will answer to you as soon as he will have a possibility.');
}
$system->config['pagename'] = $data['title'];
//check right to edit
		$title =  
		($system->checkForRight('GENERAL')and isset($_GET['get'])) ?  
		$data['title'].' 
		<a href="'.ADMIN_FILE.'?show=module&id=tools.sendmail&edit=' . $_GET['get'] . '&tab=8" title="'.__('Edit').'">
		<img src="' . SKIN_PATH . 'edit_small.gif" alt="'.__('Edit').'">
		</a>
		' : $data['title'];
show_window($title, 
			rcms_parse_module_template('sendmail.tpl',array(
			'comment'=>$data['comment'],
			'sender_name'=>$data['sender_name'],
			'sender_email'=>$data['sender_email'],
			'subject'=>$data['subject'],
			'letter'=>$data['letter'],
			'important'=>$data['important'],
			'important_text'=>$data['important_text']
			)), 'center');

?>