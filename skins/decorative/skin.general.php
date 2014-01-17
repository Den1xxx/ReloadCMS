<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<title><?rcms_show_element('title')?></title>
<?rcms_show_element('meta')?> 
<link href="<?=CUR_SKIN_PATH?>default.css" rel="stylesheet" type="text/css" media="screen" />
<SCRIPT type="text/javascript">
//<![CDATA[
$(function() {
	var url=document.location.href;
	$.each($(".meta a"),function(){
	if(this.href==url){$(this).addClass('active');};
	});
    });
//]]>
</SCRIPT>
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a href="index.php"><?=$system->config['title']?></a></h1>
		</div>
	<div id="search">
		<form method="get" action="?">
			<fieldset>
			<input id="s" type="text" name="search" value="" class="text" />
			<input id="x" type="submit" value="Search" class="button" />
			</fieldset>
		</form>
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<p class="meta"> <?rcms_show_element('navigation', '<a href="{link}">{title}</a>&nbsp;&nbsp;&nbsp;')?></p>
			<div class="entry">
		<?rcms_show_element('menu_point', 'up_center@window')?>
        <?rcms_show_element('main_point', $module . '@window')?>
        <?rcms_show_element('menu_point', 'down_center@window')?> 
			</div>
		</div>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<?rcms_show_element('menu_point', 'left@window')?>
        <?rcms_show_element('menu_point', 'right@window')?> 
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p><?rcms_show_element('copyright')?> Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></p> 
	<?php    	
  // Page gentime end
  $mtime = explode(' ', microtime());
  $totaltime = $mtime[0] + $mtime[1] - $starttime;
  print(__('GT:').round($totaltime,2));
 ?> 
</div>
<!-- end footer -->
</body>
</html>
