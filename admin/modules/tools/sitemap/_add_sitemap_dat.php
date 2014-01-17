<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = __('Enable all').' url'.__(' for ').' sitemap.dat';
	if (is_file(DF_PATH. 'sitemap.dat')) {
	$array_url = explode("\n", file_get_contents(DF_PATH. 'sitemap.dat'));
	if (!empty($array_url)) {
		foreach  ($array_url as $url) {
		$url = trim($url);
		if (!empty($url))	
		$sitemap->addUrl($directory.$url, 
		rcms_format_time('Y-m-d', time()),	
		$chfr,	
		$prio);
		}
		}
	}
?>