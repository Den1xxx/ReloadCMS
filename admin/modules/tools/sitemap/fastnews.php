<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = 'FN: '.__('Articles');
    $query='SELECT `id`,`date` from `articles` ORDER BY `id`';
    $allarticles=simple_queryall($query);
	if (!empty ($allarticles)) {
foreach ($allarticles as $key=>$article) {
$date=substr($article['date'], 0, 10);
			$sitemap -> addUrl(
				$directory . '?module=fn&a='.$article['id'],
				$date,
				$chfr,
				$prio);
}
}
?>
