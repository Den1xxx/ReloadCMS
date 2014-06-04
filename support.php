<?php
/*
Support chat
All features in one file.
*/

//Preparations
define('RCMS_ROOT_PATH', './');
define('AJAX', true);
include(RCMS_ROOT_PATH . 'common.php');

define('CHANNEL_PATH', RCMS_ROOT_PATH.'content/support/');
if (!is_dir(CHANNEL_PATH)) rcms_mkdir(CHANNEL_PATH);
$nickname=$system->user['nickname'];

//Добавить возможность к каналу выбора его владельца. Посторонние не должны видеть канал. Админ видит все каналы.
if (cfr('GENERAL')) $channel = isset($_POST['channel']) ? preg_replace("/[^a-z0-9]/i", '', $_POST['channel']): 'general';
else $channel = $nickname;


//Main logic
if (isset($_POST['action'])){

	switch ($_POST['action']) {
	//User 
		case 'join': 
			writeLine($channel, '<span><b>'.$nickname.'</b> has entered the chatroom</span>');
		break;
		case 'send': 
			$text = strip_tags($_POST['text']); 
			writeLine($channel , $text);
		break;
		case 'listen': 
			if ($stat = @stat(CHANNEL_PATH.$channel)) {
				$lastsize = intval($stat['size']);
			} else {
				writeLine($channel, '<span>Channel <b>'.$channel.'</b> created</span>');
				$lastsize = 0;
			}
			while (1){
				usleep(200000);
				clearstatcache();
				$stat = @stat(CHANNEL_PATH.$channel);
				if (intval($stat['size']) > $lastsize){
					$result='';
					$lines = file(CHANNEL_PATH.$channel);
					foreach ($lines as $line) $result .= '<li>'.$line.'</li>';
					echo $result;
					die();
				}
			}	
		break;
		case 'part': 
			writeLine($channel, '<span><b>'.$nickname.'</b> has left the chatroom '.$channel.'</span>');
		break;
		default: 

		break;
	}
}

/*
* Add line to channel history
*
*  
*/
function writeLine($room, $text) {
    //$time = gmdate("H:i:s",rcms_get_time());
    $time = gmdate("Y-m-d H:i:s",rcms_get_time());
	$fp = fopen(CHANNEL_PATH.$room, 'a');
	fwrite($fp, '<span class="time">'.$time.'</span> | '.$text."\n");
	fclose($fp);
}
if (!empty($_POST['action'])) die();
$inputs='';
if (cfr('GENERAL')) {
$channels=rcms_scandir(CHANNEL_PATH);
foreach ($channels as $input) $inputs.='<input type="button" value="'.$input.'" class="enter"> ';
} else $inputs.='<input type="button" value="'.$channel.'" class="enter"> '
//Interface
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Support demo chat</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<style>
	#support {
		border: #ccc 1px solid;
		font-family: Arial, sans-serif;
		font-size: 12px;
	}

	#support_lines ul {
		margin: 0;
		padding: 0;
		list-style: none;
	}

	#support_lines li {
		margin: 0;
		padding: 2px 8px;
		border-bottom: #999 1px solid;
	}

	#support_lines li:nth-child(odd) { background-color:#eee; }
	#support_lines li:nth-child(even) { background-color:#fff; }

	#support_lines span {
		color: #3c3;
	}

	#support_hint {
		font-size: 11px;
		text-align: center;
		margin: 0;
	}

	#support_entry {
		margin: 5px 10px 5px 5px;
	}

	#support_input {
		width: 100%;
		padding: 0;
	}

	#support span.time {
		color: #666;
		font-size: 0.8em;
	}
	</style>
	<script type="text/javascript">
		var support_error = '<?='<li>Sorry there was an error! Please reload the page and re-enter the chatroom.</li>'?>';
		var support_serverurl = 'http://<?=$_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'] . basename($_SERVER['SCRIPT_NAME']))?>';
		var support_channel = '<?=$channel?>';
		var nickname = '<?=$nickname?>';
		var support_url = '/support.php';
	$(document).ready(function(){
		$(document).ajaxError(function() {
		$('#support_lines ul').html(support_error);
		});
		
		$('.enter').click(function(){
			support_channel = this.value;
			listener();
			$.post(support_serverurl+support_url, {action: 'join', nickname: nickname, channel: support_channel}, function(data){
				$('#support_input').val('');
				$('#support_hint').html('<span><?=__('Hello').', <b>'.$nickname?></b>!</span><br/> Type a line of chat and press enter to speak:');
			}); 
		})
		
		$('#support_input').focus();
		$('#support_input').keyup(function(e) {
			if (e.keyCode == 13) {
				var sendline = $('#support_input').val();
				if (sendline != '') {
					$('#support_input').val('sending...');
					serverSend('<b>'+nickname+':</b> '+sendline);
				}
			}
		});
		
		$(window).bind("beforeunload", function(){
			if (nickname != '') {
				$.post(support_serverurl+support_url, {action: 'part', nickname: nickname, channel: support_channel});
			}
		});
	});

	function serverSend(sendtext) {
		$.post(support_serverurl+support_url, {action: 'send', text: sendtext, channel: support_channel}, function(data){
			$('#support_input').val('');
		});
	}

	function listener() {
		$.post(support_serverurl+support_url, {action: 'listen', channel: support_channel}, function(data){
			$('#support_lines ul').html(data);
			$('#support_lines').scrollTop($('#support_lines')[0].scrollHeight);
			listener();
		});
	}
	</script>
</head>
	
<body>
	<div id="support">
		<div id="support_lines">
			<ul></ul>
		</div>
		<div id="support_entry">
			<p id="support_hint">Select channel: <?=$inputs?></p>
			<input type="text" id="support_input">
		</div>
	</div>
</body>
</html>