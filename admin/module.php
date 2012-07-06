<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(empty($system)) die();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$system->config['encoding']?>">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/ajaxupload.js"></script>
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/editor.js"></script>
<link rel="stylesheet" href="<?=ADMIN_PATH?>style.css" type="text/css">
</head>
<body>
<?php
include(ADMIN_PATH . 'modules/' . $module[0] . '/' . $module[1] . '.php');
?>
</body>
</html>