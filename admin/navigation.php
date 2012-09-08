<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(empty($system)) die();
?>
<table width="100%" cellpadding="4" cellspacing="1" border="0">
<tr>
	<th style="text-align: left">&#0187; <?=__('Return to')?> ...</th>
</tr>
<tr>
	<td class="row1"><a href="<?=RCMS_ROOT_PATH?>" target="_top">... <?=__('site index')?></a></td>
</tr>
<tr>
	<td class="row1"><a href="?show=module">... <?=__('admin index')?></a></td>
</tr>
<?php
foreach($MODULES as $category => $blockdata) {
    if(!empty($blockdata[1]) && is_array($blockdata[1])) { ?>
<tr>
	<th style="text-align: left">&#0187; <?=$blockdata[0]?></th>
</tr>
<?php foreach($blockdata[1] as $module => $title) { ?>
<tr>
	<td class="row1"><a href="?show=module&id=<?=$category . '.' . $module?>"><?=$title?></a></td>
</tr>
<?php
		}
	} elseif($blockdata[0] === @$blockdata[1]) { ?>
<tr>
	<th style="text-align: left">&#0187; <a href="?show=module&id=<?=$category . '.index'?>" class="th"><?=$blockdata[0]?></a></th>
</tr>
<?php
	}
}
?>
</table> 