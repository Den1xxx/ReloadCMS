<div id="ul-user">
<?php if(!LOGGED_IN) {?>
	<ul style="list-style-type:none">
		<form method="post" action="" id="tablelogin" >
			<li class="username">
				<?=__('Username')?> 
				<input type="text" name="username" id="mod_login_username"  />
			</li>
			<li class="password">
				<?=__('Password')?>
				<input type="password" id="mod_login_password"  name="password" />
			</li>
			<li class="username">
			<input type="checkbox" name="remember" id="remember" value="1" />
			<label for="mod_login_remember">   <?=__('Remember me')?></label><br/>
			<input type="hidden" name="login_form" value="1" />
			<input type="submit" value="<?=__('Log in')?>" />
			</li>
			<li id="form-login-remember">
				<a href="?module=user.profile&amp;act=password_request"><?=__('I forgot my password')?></a>
				<a href="?module=user.profile&amp;act=register"><?=__('Register')?></a>
			</li>
		</form>
	</ul>
<?php } else {?>
	<ul style="list-style-type:none">
			<?php if($system->checkForRight('-any-')) { ?>
			<li class="username">
			<a href="<?=RCMS_ROOT_PATH.ADMIN_FILE?>"><?=__('Administration')?></a>
			</li>
			<?php }?>
			<li class="username">
	        <a href="?module=user.profile"><?=__('My profile')?></a>
			</li>
			<?php if(!empty($system->modules['main']['articles.post'])) { ?>
			<li class="username">
			<a href="?module=articles.post"><?=__('Post article')?></a>
			</li>
			<?php }?>
			<?php if(LOGGED_IN and !pm_disabled()) { ?>
			<li class="username">
				<a href="?module=pm&mode=get" title="<? $ar = pm_get_msgs(); echo $ar[2]; ?>" ><? $ar = pm_get_msgs(); if ($ar[1] <> 0) echo '<b>'; echo __('Messages ').'('.$ar[1].'/'.$ar[0].')'; if ($ar[1] <> 0) echo '</b>';?></a>
			</li>
			<?php }?>
			<li class="username">
			<form method="post" action="" id="tablelogin" >
			<input type="hidden" name="logout_form" value="1"/>
			<input type="submit"  value="<?=__('Log out')?>" /><br/>
			</form>
			</li>
			<?php if(!empty($system->config['allowchskin'])){?>
			<li>
			<form name="skin_select" method="post" action="">
			<?=user_skin_select(SKIN_PATH, 'user_selected_skin', $system->skin, 'font-size: 90%; width: 100%',
			'onchange="document.forms[\'skin_select\'].submit()" title="' . __('Skin') . '"')?>
			</form></li>
			<?php }?>
			<?php if(!empty($system->config['allowchlang'])){?><li>
			<form name="lang_select" method="post" action="">
			<?=user_lang_select('lang_form', $system->language, 'font-size: 90%; width: 100%', 
			'onchange="document.forms[\'lang_select\'].submit()" title="' . __('Lang') . '"')?>
			</form>
			</li>
			<?php }?>
	</ul>
<?}?>
</div>
