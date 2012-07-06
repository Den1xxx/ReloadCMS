<center>
<form name="minichat" method="post" action="">
    <input type="hidden" name="add_minichat_message" value="1" />
    <b><?=__('Nickname')?>: [<?=$system->user['nickname']?>]</b>
    <br/>

<? $config = parse_ini_file(CONFIG_PATH . 'minichat.ini'); 
$max_len=$config['max_message_len']; ?>
<? echo __('Maximum message length').'<br/>[<script type="text/javascript">displaylimit("document.minichat.mctext", "",'.$max_len.') </script>]'?><br />

 <textarea id="mctext" name="mctext" rows="5" cols="30"></textarea><br />
<?php 
	if(!LOGGED_IN) {
    $rand=rand(0,777); 
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
    	<?=__('Text')?>:<input type="text" size="5" name="captcheckout" value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>" /><br />
<?php } ?>

    <input type="submit" value="<?=__('Submit')?>" name="submit" />
</form>
</center>