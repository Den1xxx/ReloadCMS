<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(empty($system)) die();
header('Last-Modified: ' . gmdate('r')); 
header('Content-Type: text/html; charset=' . $system->config['encoding']);
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); // HTTP/1.1 
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<head>
<title><?=__('Administration')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$system->config['encoding']?>">
</head>
<frameset cols="190, *" border="0" framespacing="0" frameborder="NO">
	<frame src="./admin.php?show=nav" name="nav" marginwidth="3" marginheight="3" scrolling="auto">
	<frame src="./admin.php?show=module" name="main" marginwidth="0" marginheight="0" scrolling="auto">
</frameset>
<noframes>
	<body bgcolor="white" text="#000000">
		<p>Sorry, but your browser does not support frames</p>
	</body>
</noframes>
</html>