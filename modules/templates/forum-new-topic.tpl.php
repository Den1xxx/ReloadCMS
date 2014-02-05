<table width="100%" style="border: 1px solid black" cellpadding="0" cellspacing="0">
<tr>
    <td align="left" class="row2">
        &nbsp;&nbsp;&gt; <a href="?module=forum"><?=__('Topics list')?></a> &gt; <?=__('New topic')?>
    </td>
</tr>
</table>
<br />
<form method="post" action="" name="new_topic" style="text-align: center">
    <input type="hidden" name="new_topic_perform" value="1" />
    <?=__('Topic title')?>: <input type="text" name="new_topic_title" value="<?=$tpldata[0]?>" size="50"/><br />
    <?=rcms_show_bbcode_panel('new_topic.new_topic_text')?>
    <textarea name="new_topic_text" cols="70" rows="7" style="width: 95%;"><?=$tpldata[1]?></textarea><br />
    <?php if($system->checkForRight('FORUM')){ ?>
    <input type="checkbox" name="new_topic_sticky" value="1" id="new_topic_sticky" /><label for="new_topic_sticky"><?=__('Sticky topic')?></label><br />
    <input type="checkbox" name="new_topic_closed" value="1" id="new_topic_closed" /><label for="new_topic_closed"><?=__('Create closed')?></label><br />
    <?php }  ?>
<?php
$config = parse_ini_file(CONFIG_PATH . 'forum.ini');
	$max_topic_len = $config['max_topic_len'];
	echo __('You can enter').' <script type="text/javascript">displaylimit("document.new_topic.new_topic_text", "",'.$max_topic_len.') </script> '.__('characters')?><br /> 
<?	if(!LOGGED_IN) {
    $rand=rand(0,777); 
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
    	<?=__('Text')?>:<input type="text" size="5" name="captcheckout" value="">
    <input type="hidden" name="antispam" value="<?=$rand;?>" /><br />
<?php } ?>	


    <input type="submit" value="<?=__('Submit')?>" />
</form>