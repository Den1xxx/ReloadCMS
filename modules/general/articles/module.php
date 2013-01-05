<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$this->registerModule($module, 'main', __('Articles'), 'ReloadCMS Team', array(
'ARTICLES-MODERATOR' => __('Right to moderate comments in articles'),
'ARTICLES-ADMIN' => __('Right to control over categories and sections of articles'),
'ARTICLES-EDITOR' => __('Right to post and edit articles'),
));

if (is_file(ENGINE_PATH . 'api.articles.php')) {
include_once(ENGINE_PATH . 'api.articles.php');
$articles = new articles();
foreach ($articles->getContainers() as $c_id => $c_title){
	if(substr($c_id, 0, 1) !== '#') {
		$this->registerFeed($module . '@' . $c_id, $c_title, __('Feed for section') . ' ' . $c_title, $module);
	}
}
}

$this->registerNavModifier('article', '_nav_modifier_article_m', '_nav_modifier_article_h');

function _nav_modifier_article_m($input){
	global $articles;
	if(!is_a($articles, 'articles')){
		$arts = new articles();
	} else {
		$arts = &$articles;
	}
	$data = explode('/', $input, 3);
	$mode = sizeof($data);
	$containers = $arts->getContainers(0);
	switch ($mode){
		case 1:
			if(!empty($containers[$data[0]])){
				return array('?module=articles&amp;c=' . urlencode($data[0]), $containers[$data[0]]);
			}
			break;
		case 2:
			if($arts->setWorkContainer($data[0])){
				$categories = $arts->getCategories(true, false, false);
				if($data[0] == '#hidden' || $data[0] == '#root' && $article = $arts->getArticle(0, (int)$data[1], false, false, false, false)){
					return array('?module=articles&amp;c=' . urlencode($data[0]) . '&amp;a=' . (int)$data[1], $article['title']);
				} elseif($categories && !empty($categories[(int)$data[1]])) {
					return array('?module=articles&amp;c=' . urlencode($data[0]) . '&amp;b=' . (int)$data[1], $categories[$data[1]]);
				}
			}
			break;
		case 3:
			if($arts->setWorkContainer($data[0])){
				if($article = $arts->getArticle((int)$data[1], (int)$data[2], false, false, false, false)){
					return array('?module=articles&amp;c=' . urlencode($data[0]) . '&amp;b=' . (int)$data[1] . '&amp;a=' . (int)$data[2], $article['title']);
				}
			}
			break;
	}
	return false;
}

function _nav_modifier_article_h(){
	return __('This modifier is used to create links to sections, categories and articles. After ":" you must type section\'s ID, category ID (if you want to link to specified category/article, if you want to link to article in system conatainer (#root) you must skip this field), article ID (if you want to link to article) separated by "/" symbol.');
}
?>