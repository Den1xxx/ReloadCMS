<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$system->config['language']?>" lang="<?=$system->config['language']?>">
<head>   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?rcms_show_element('meta')?>
    <title><?=__('Administration')?></title>
<link rel="stylesheet" href="<?=ADMIN_SKIN?>style.css" type="text/css">
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
    <script>
    $(function() {
	var url=document.location.href;
	$.each($("#layout-left a"),function(){
	if(this.href==url){$(this).addClass('active');};
	});
    });
    </script>
</head>
<body>
    <div id="layout-center">
	<?php
	echo '<div style="z-index:1;position:absolute;right:5px;">'.show_help(get('id')).'</div>';
	if(!empty($_GET['show'])) {
			$module = (!empty($_GET['id'])) ? basename($_GET['id']) : '.index';
			$module = explode('.', $module, 2);
			if(!is_file(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php')) {
				$message = __('Module not found') . ': ' . $module[0] . '/' . $module[1];
				include(ADMIN_PATH . 'error.php');
			} elseif($module[1] != 'index' && empty($MODULES[$module[0]][1][$module[1]])) {
				$message = __('Access denied') . ': ' . $module[0] . '/' . $module[1];
				include(ADMIN_PATH . 'error.php');
			} else include(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php');
		} else include(ADMIN_PATH . 'modules/index.php');
	?>   
	</div>
    <div id="layout-left">
		<?php include(ADMIN_SKIN . 'navigation.php'); ?>
    </div>
</body>
</html>	
