<form method="post" action="" name="new_post" style="text-align: center">
    <input type="hidden" name="new_post_perform" value="1" />
    <input type="hidden" name="new_post_topic" value="<?=$tpldata[0]?>" />
    <?=rcms_show_bbcode_panel('new_post.new_post_text')?>
    <textarea name="new_post_text" cols="70" rows="7" style="width: 95%;"><?=$tpldata[1]?></textarea><br />
 <?php 
	$config = parse_ini_file(CONFIG_PATH . 'forum.ini');
	$max_message_len = $config['max_message_len'];
	echo __('You can enter').' <script type="text/javascript">displaylimit("document.new_post.new_post_text", "",'.$max_message_len.') </script> '.__('characters')?><br />
<?	if(!LOGGED_IN) {
    $rand=rand(0,777); 
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
    	<?=__('Text')?>:<input type="text" size="5" name="captcheckout" value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>"/><br />
<?php } ?>   
	<input type="submit" value="<?=__('Submit')?>" />
</form>