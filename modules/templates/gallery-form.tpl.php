<b><?=__('Nickname')?>: [<?=$system->user['nickname']?>]</b>

<form method="post" action="" name="form1">
<?=rcms_show_bbcode_panel('form1.comtext'); ?>
<textarea name="comtext" cols="20" rows="7" style="width: 90%"></textarea><br />
<p align="center">    
<?php 
	if(!LOGGED_IN) {
    $rand=rand(0,777); 
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
    	<?=__('Text')?>:<input type="text" size="5" name="captcheckout" value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>" /><br />
	<? echo (__('You can enter').' <script type="text/javascript">displaylimit("document.form1.comtext", "",300) </script> '.__('characters'));?><br />
<?php } ?>
<input type="submit" value="<?=__('Submit')?>" /></p>
</form>
