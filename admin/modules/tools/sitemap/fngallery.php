<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = 'FN: '.__('Gallery');
    $pictures=fn_pic_array_keywords();
	if (!empty ($pictures)) {
		$sitemap -> addUrl(//cc���� �� ������� �������� �������
				$directory . '?module=fngallery',
				rcms_format_time('Y-m-d', time()),
				$chfr,
				$prio);
if (!empty($pictures)) {
	foreach ($pictures as $key=>$picture) {//������� ���� �������� ����
			$sitemap -> addUrl(
				$directory . '?module=fngallery&keyword='.$picture,
				rcms_format_time('Y-m-d', time()),
				$chfr,
				$prio);
	}
}
}
?>
