<!doctype html>
<html>
<head>     
    <title><?=$title?></title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <?rcms_show_element('meta')?>
<link rel="stylesheet" href="<?=ADMIN_SKIN?>style.css" type="text/css">
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script>
    $(function() {
        $( "#navigation" ).tabs(<?=(get('tab')?'{active:'.$_GET['tab'].'}':'');?>);
	var url=document.location.href;
	$.each($("#navigation a"),function(){
	if(this.href==url){$(this).addClass('active');};
	});
    });
    </script>
</head>
<body>
    <div id="navigation">
		<?php include(ADMIN_SKIN . 'navigation.php'); ?>
    </div>
    <div id="main">
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
</body>
</html>	