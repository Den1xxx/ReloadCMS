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
<link rel="stylesheet" href="<?=ADMIN_SKIN?>style.css" type="text/css">
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%">
<tr>
	<td valign="middle" align="center">
		<table cellpadding="0" cellspacing="0" border="0" style="width: 300px; height: 100px">
		<tr>
			<td class="messagebox-top" style="height: 100%">
				<?=$message?><br />
			</td>
		</tr>
		<tr>
			<td class="messagebox-bottom">
				[ <a href="javascript:history.back()"><?=__('Back')?></a> ]
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>