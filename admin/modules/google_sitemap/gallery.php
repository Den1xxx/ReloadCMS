<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
// Site map for Gallery
$name_module = __('Gallery');
$file='';
	if ($dh = opendir(GALLERY_IMAGES_DIR)) {
       while (($file = readdir($dh)) !== false) {
			if($file != '.' && $file != '..') {
		$lastmod = filemtime(GALLERY_IMAGES_DIR.$file);
		$loc = $directory . '?module=gallery&id='.$file;
			$sitemap->addUrl(
			$loc,
			rcms_format_time('Y-m-d', $lastmod),
			$chfr,
			$prio);
			}
       }
       closedir($dh);
   }
?>
