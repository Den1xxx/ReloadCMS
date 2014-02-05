<b><?=__('Nickname')?>: [<?=$system->user['nickname']?>]</b>
<?=rcms_show_bbcode_panel('form1.comtext'); ?>
<form method="post" action="" name="form1">
<textarea name="comtext" cols="20" rows="7" style="width: 90%"></textarea><br />
<? $config = parse_ini_file(CONFIG_PATH . 'guestbook.ini'); 
$max_len=$config['max_message_len']; ?>
<? echo __('You can enter').' <script type="text/javascript">displaylimit("document.form1.comtext", "",'.$max_len.') </script> '.__('characters')?><br />
<p align="center">    
<?php 
	if(!LOGGED_IN) {
    $rand=rand(0,777); 
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
<?=__('Text')?>:<input type="text" size="5" name="captcheckout"value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>" /><br />
<?php } ?>
<input type="submit" value="<?=__('Submit')?>" /></p>
</form>