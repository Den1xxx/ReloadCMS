<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$name_module = __('Articles');
$articles = new articles();
 foreach (array_keys($articles->getContainers()) as $c){
  if ($c!='#hidden') {
   if($articles->setWorkContainer($c)){
    if (is_array($articles->getCategories())){
	foreach	(($articles->getCategories()) as $b) {
		foreach ($articles->getArticles($b['id'],true) as $article){
		$loc  = $directory.'?module=articles&c='.str_replace('#', '%23', $c).'&b='.$b['id'].'&a='.$article['id'];
		$sitemap->addUrl(
		$loc,
		rcms_format_time('Y-m-d', $article['time']),
		$chfr,
		$prio);
		}; 
	};
	}
   };
  };
 };


?>
