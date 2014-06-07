<!doctype html>
<html>
<head> 
    <?rcms_show_element('meta')?>
    <title><?=__('Administration')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=ADMIN_SKIN?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="<?=ADMIN_SKIN?>style.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
    <script src="<?=ADMIN_SKIN?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=ADMIN_SKIN?>js/custom.js"></script>
	<script type='text/javascript'>
	var url=document.location.href;

	$(document).ready(function(){
		$.each($("#sidebar .submenu a"),function(){
			if(this.href==url){
				$(this).addClass('active');
				$($(this).parents('ul')).css('display','block').addClass('active').prev('a').css('color','#000').css('font-size','1.1em');
			};
		});
		$('[title]').popover({ trigger: "hover", placement:'top' });
	});
	</script>
</head>
<body>
  	<div class="header navbar navbar-fixed-top">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-7">
	              <div class="logo">
	                 <h1>
						<a href="<?=ADMIN_FILE?>"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;<?=__('Administration')?></a>
					</h1>
	              </div>
	           </div>
	           <div class="col-md-5">
	              <div class="navbar navbar-inverse" role="banner">
	                  <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
	                    <ul class="nav navbar-nav">
						  <li>
								<?=show_help(get('id'))?>
						  </li>
	                      <li class="dropdown">
	                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?=__('Hello').', '.$system->user['username']?> <b class="caret"></b>
							</a>
	                        <ul class="dropdown-menu animated fadeInUp">
	                          <li><a href="<?=RCMS_ROOT_PATH?>?module=user.profile"><?=__('My profile')?></a></li>
	                          <li><form id="admin_skin_select" name="admin_skin_select" method="post" action="">
							  <?=user_skin_select(ADMIN_PATH.'skins/', 'admin_selected_skin', $admin_skin, '',
							  'onchange="document.forms[\'admin_skin_select\'].submit()" title="' . __('Skin') . '"')?></form></li>
	                          <li><form id="tablelogin" method="post" action="<?=RCMS_ROOT_PATH?>">
							  <input type="hidden" value="1" name="logout_form">
							  <input class="button" type="submit" value="<?=__('Log out')?>">
							  </form></li>
	                        </ul>
	                      </li>
						  <li>
								<?=' <a href="'.RCMS_ROOT_PATH.'" target="_top">'.__('site index').'&nbsp;&nbsp;
								<span class="glyphicon glyphicon-share-alt"></span></a>
								'?>
						  </li>
	                    </ul>
	                  </nav>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>
	<div class="page-content">
    	<div class="row">
		  <div class="col-md-3">
		  	<div class="sidebar content-box" style="display: block;" id="sidebar">
                <ul class="nav">
					<?php
					$tab=1;
					foreach($MODULES as $category => $blockdata) {
						if(!empty($blockdata[1]) && is_array($blockdata[1])) {?>
					<li class="submenu">
					<a name="<?=$tab?>" href="#<?=$tab?>">
					<?=$blockdata[0]?>
					<span class="glyphicon glyphicon-chevron-right pull-right"></span>
					</a>
					<ul>
					<?php foreach($blockdata[1] as $module => $title) {?>
					<li><a href="?show=module&id=<?=$category . '.' . $module.'&tab='.$tab?>"><?=$title?></a></li>
					<?php
					}
					?>
					</ul>
					</li> 
					<?php
						} elseif($blockdata[0] === @$blockdata[1]) { ?>
					<li><a href="?show=module&id=<?=$category . '.index'?>" class="th"><?=$blockdata[0]?></a></li>
					<?php
						}
					$tab++;
					}
					?>				
				</ul>
            </div>
		  </div>
		  <div class="col-md-9" id="main">
			<div class="content-box-large">
				<?
				if(!empty($_GET['show'])) {
					$module = (!empty($_GET['id'])) ? basename($_GET['id']) : '.index';
					$module = explode('.', $module, 2);
					if(!is_file(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php')) {
						$message = __('Module not found') . ': ' . $module[0] . '/' . $module[1];
						include(ADMIN_PATH . 'error.php');
					} elseif($module[1] != 'index' && empty($MODULES[$module[0]][1][$module[1]])) {
						$message = __('Access denied') . ': ' . $module[0] . '/' . $module[1];
						include(ADMIN_PATH . 'error.php');
					} else {
					echo '<h4 style="margin-top:0">'.$MODULES[$module[0]][0].' &rarr; '.$MODULES[$module[0]][1][$module[1]].'</h4>';
					include(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php');
					}
				} else {
				include(ADMIN_PATH . 'modules/index.php');
				}
				?>
		  </div>
		  </div>
		</div>
	</div>
	<footer>
	 <div class="container">
		<div class="copy text-center">
		   Copyright 2014 by <a href="http://fromgomel.com">Den1xxx</a>
		</div>
	 </div>
  </footer>
</body>
</html>	