<form method="post" action="">
<?php if(!LOGGED_IN) {?>
<input type="hidden" name="login_form" value="1" />
<a name="top_login"></a>
<table cellpadding="2" cellspacing="1" style="width: 100%;">
<tr>
    <td class="row2" colspan="2"><a href="#top_login" onclick="$('#tablelogin').toggle('slow'); return false;"><?=__('Log in')?></a></td>
</tr>
</table>
<table cellpadding="2" cellspacing="1" id="tablelogin" style="display: none; width: 100%;">
<tr>
    <td class="row1" style="font-size:0.8em;"><?=__('Username')?>:</td>
    <td class="row1" style="font-size:0.8em;"><input type="text" name="username" size="9"/></td>
</tr>
<tr>
    <td class="row1" style="font-size:0.8em;"><?=__('Password')?>:</td>
    <td class="row1" style="font-size:0.8em;">
	<input type="password" id="pass" name="password" size="9"/>
	<input onchange="if ($('#pass').get(0).type=='text') $('#pass').get(0).type='password'; else $('#pass').get(0).type='text';" name="fff" type="checkbox" value="false">
	<?=__('Show')?></td>
</tr>
<tr>
    <td class="row1" colspan="2">
        <input type="checkbox" name="remember" id="remember" value="1" />
        <label for="remember"><?=__('Remember me')?></label>
    </td>
</tr>
<tr>
    <td class="row2" colspan="2"><input type="submit" value="<?=__('Log in')?>" /></td>
</tr>
</table>
</form>
<form method="post" action="">
<table cellpadding="2" cellspacing="1" style="width: 100%;">
<tr>
    <td class="row2" colspan="2"><a href="?module=user.profile&amp;act=password_request"><?=__('I forgot my password')?></a></td>
</tr>
<tr>
    <td class="row2" colspan="2"><a href="?module=user.profile&amp;act=register"><?=__('Register')?></a></td>
</tr>
<?php } else {?>
<input type="hidden" name="logout_form" value="1">
<table cellpadding="2" cellspacing="1" style="width: 100%;">
<?php if($system->checkForRight('-any-')) { ?>
<tr>
    <td class="row3">
        <a href="admin.php"><?=__('Administration')?></a>
    </td>
</tr>
<?php }?>
<?php if(!empty($system->modules['main']['articles.post'])) { ?>
<tr>
    <td class="row3">
        <a href="?module=fnadmin&action=new"><?=__('Post article')?></a>
    </td>
</tr>
<?php }?>
<tr>
    <td class="row2">
        <a href="?module=user.profile"><?=__('My profile')?></a>
    </td>
</tr>
<?php if(LOGGED_IN and !pm_disabled()) { ?>
<tr>
    <td class="row3">
        <a href="?module=pm&mode=get" title="<? $ar = pm_get_msgs(); echo $ar[2]; ?>" ><? $ar = pm_get_msgs(); if ($ar[1] <> 0) echo '<b>'; echo __('Messages ').'('.$ar[1].'/'.$ar[0].')'; if ($ar[1] <> 0) echo '</b>';?></a>
    </td>
</tr>
<?php }?>
<tr>
    <td class="row2">
        <input type="submit" value="<?=__('Log out')?>" />
    </td>
</tr>
<?php }?>
</table>
</form>
<?php if(!empty($system->config['allowchskin'])){?>
<form name="skin_select" method="post" action="">
    <?=user_skin_select(SKIN_PATH, 'user_selected_skin', $system->skin, 'font-size: 90%; width: 100%', 'onchange="document.forms[\'skin_select\'].submit()" title="' . __('Skin') . '"')?>
</form>
<?php }?>
<?php if(!empty($system->config['allowchlang'])){?>
<form name="lang_select" method="post" action="">
    <?=user_lang_select('lang_form', $system->language, 'font-size: 90%; width: 100%', 'onchange="document.forms[\'lang_select\'].submit()" title="' . __('Lang') . '"')?>
</form>
<?php }?>