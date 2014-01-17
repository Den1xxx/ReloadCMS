<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = __('Pages');
$pages = @rcms_scandir(RCMS_ROOT_PATH.'content/pages');
if (!empty($pages) && is_array($pages)) {
   foreach ($pages as $page) {
   $sitemap -> addUrl(
			$directory . '?module=pages&id='.$page,
			rcms_format_time('Y-m-d', filemtime(RCMS_ROOT_PATH.'content/pages/'.$page)),
			$chfr,
			$prio);
   }
}
?>
