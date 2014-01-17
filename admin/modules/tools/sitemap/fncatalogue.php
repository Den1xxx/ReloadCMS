<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = 'FN: '.__('Catalogue');
$query="SELECT `id` FROM `items` ORDER BY id ASC;";
$io=simple_queryall($query);
if (!empty ($io)) {
    foreach ($io as $val => $key) { 
	$sitemap -> addUrl(
			$directory . '?module=fncatalogue&showitem='.$key['id'],
			rcms_format_time('Y-m-d', time()),
			$chfr,
			$prio);
	}
}
?>
