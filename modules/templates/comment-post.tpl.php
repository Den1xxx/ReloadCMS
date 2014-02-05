<b><?=__('Nickname')?>: [<?=$system->user['nickname']?>]</b>
<?=rcms_show_bbcode_panel('form1.comtext'); ?>
<?if(empty($tpldata['field'])) $field = 'comtext'; else $field = $tpldata['field'];?>

<form method="post" action="" name="form1">
<? echo __('You can enter').' <script type="text/javascript">displaylimit("document.form1.comtext", "",2000) </script> '.__('characters')?><br />
	<textarea name="<?=$field?>" cols="20" rows="7" style="width: 90%"><?=@$tpldata['text']?></textarea>
    <p align="center">
<?php 
	if(!LOGGED_IN) {
    $rand=rand(0,777);
    ?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" /><br />
    	<input type="text" size="5" name="captcheckout" value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>"/><br />
<?php } ?>
        <input type="submit" value="<?=__('Submit')?>" />
    </p>
</form>