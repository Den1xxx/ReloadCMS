<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

$articles = new articles();
$c = (empty($_GET['c']) || $_GET['c'] == '#hidden') ? null : $_GET['c'];
$b = (empty($_GET['b'])) ? null : (int)$_GET['b'];
$a = (empty($_GET['a'])) ? null : (int)$_GET['a'];
$code_addon = '';
//add rating
if (!empty($articles->config['code_rating'])) {
if(!empty($tpldata['linkurl'])) $url='/' . $tpldata['linkurl'];
else $url=$_SERVER['REQUEST_URI'];
$urlcode = crc32(str_replace('&amp;','&',$url));
$RW_UID = html_entity_decode($articles->config['code_rating']); 
// replace number of widjet to unique ID 
$RW_UID = str_replace('rw-urid-1','rw-urid-'.$urlcode,$RW_UID);
$code_addon .= '<br/><br/>
'. $RW_UID . '
';
}
//add social button
$code_addon .= (!empty($articles->config['social'])?html_entity_decode($articles->config['social']):'');

if(!empty($a) && ((!empty($b) && !empty($c)) || $c == '#root')){
	if(!$articles->setWorkContainer($c)){
		show_error($articles->last_error);
	} elseif(!($article = $articles->getArticle($b, $a, true, true, true, true))) {
		show_error($articles->last_error);
	} elseif($c !== '#root' && !($category = $articles->getCategory($b, false))) {
		show_error($articles->last_error);
	} else {
		if(!empty($category)) $article['cat_data'] = $category;
		$containers = $articles->getContainers();

		$com_text = '';
		/* If user posting a comment */
		if(!empty($_POST['comtext']) && $article['comments'] == 'yes') {
			if (isset($system->config['article-guest']) and !LOGGED_IN){
				show_error(__('You are not logined!'));
			} else {
			if ((isset($_POST['antispam'])) AND (isset($_POST['captcheckout']))) {
	$defcatp=substr(md5($_POST['antispam']),0,5);
	$intcapt=$_POST['captcheckout'];
if($defcatp==$intcapt)	{
				if(!$articles->addComment($b, $a, $_POST['comtext'])){
					show_error($articles->last_error);
					$com_text = $_POST['comtext'];
				}
rcms_redirect(RCMS_ROOT_PATH.'?module=articles&c='.$_GET['c'].'&b='.$_GET['b'].'&a='.$_GET['a']);//f5 hack
}
else {
show_window(__('Error'),__('Invalid form data'));
}
} else {								
				if(!$articles->addComment($b, $a, $_POST['comtext'])){ 
					show_error($articles->last_error);
					$com_text = $_POST['comtext'];
				}
rcms_redirect(RCMS_ROOT_PATH.'?module=articles&c='.$_GET['c'].'&b='.$_GET['b'].'&a='.$_GET['a']);//f5 hack
}
			}
			$_GET['page'] = 0;
		}
		/* If admin deleting comment */
		if(isset($_POST['cdelete']) && $system->checkForRight('ARTICLES-MODERATOR')) {
			if(!$articles->deleteComment($b, $a, $_POST['cdelete'])){
				show_error($articles->last_error);
			}
		}
		$article['text'] = trim($article['text']);

		/* Let's view selected article */
		if($c !== '#root') {
			$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . '<a class="winheader" href="?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '">' . $containers[$c] . '</a> &rarr; <a class="winheader" href="?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $b . '">' . ((mb_strlen($category['title'])>$articles->config['category']) ? mb_substr($category['title'], 0, $articles->config['category']) . '...' : $category['title']) . '</a> &rarr; ' . ((mb_strlen($article['title']) > $articles->config['title']) ? mb_substr($article['title'], 0, $articles->config['title']) . '...' : $article['title']);
		} else {
			$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . '<a class="winheader" href="?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '">' . $containers[$c] . '</a> &rarr; ' . ((mb_strlen($article['title']) > $articles->config['title']) ? mb_substr($article['title'], 0, $articles->config['title']) . '...' : $article['title']);
		}
		$system->config['pagename'] = $article['title'];
		if(!empty($article['keywords'])) {
			$system->addInfoToHead('<meta name="Keywords" content="' . $article['keywords'] . '">' . "\n");
		}
		if(!empty($article['sef_desc'])) {
			$system->addInfoToHead('<meta name="Description" content="' . $article['sef_desc'] . '">' . "\n");
		}
		$article['container'] = $c; 	
		$article['text'] .= $code_addon;
		show_window($title, rcms_parse_module_template('art-article.tpl', $article));

		/* If comments are enabled in this article, show form */
		if($article['comments'] == 'yes') {
			if (!(isset($system->config['article-guest']) and !LOGGED_IN)) {
				show_window(__('Post comment'), rcms_parse_module_template('comment-post.tpl', array('text'=>$com_text)), 'center');
			} else {
				show_window(__('Post comment'), __('You are not logined!') . ' <a href="?module=user.profile&amp;act=register">' . __('Register') . '</a>', 'center');
			}
		}

		/* May be show some comments :) */
		if($scomments = $articles->getComments($b, $a)){
			foreach ($scomments as $id => $comment) $comments[] = $comment + array('id' => $id);
			$cnt = sizeof($comments);
			if(!empty($system->config['perpage'])) {
				$pages = ceil($cnt/$system->config['perpage']);
				if(!empty($_GET['page']) && ((int) $_GET['page']) > 0) $page = ((int) $_GET['page'])-1; else $page = 0;
				$start = $page * $system->config['perpage'];
				$total = $system->config['perpage'];
			} else {
				$pages = 1;
				$page = 0;
				$start = 0;
				$total = $cnt;
			}

			$result = '';
			$result .= '<div align="right">' . rcms_pagination($cnt, $system->config['perpage'], $page + 1, '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $b . '&amp;a=' . $a) . '</div>';
			for ($id = $start; $id < $total+$start; $id++){
				$comment = &$comments[$id];
				if(!empty($comment)) $result .= rcms_parse_module_template('comment.tpl', $comment);
			}
			$result .= '<div align="left">' . rcms_pagination($cnt, $system->config['perpage'], $page + 1, '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $b . '&amp;a=' . $a) . '</div>';
			show_window(__('Comments'), $result);
		}
	}
} elseif (!empty($c) && (!empty($_GET['from']) || !empty($_GET['until']))){
	if(!$articles->setWorkContainer($c)){
		show_error($articles->last_error);
	} elseif(($articles_list = $articles->getStat('time')) === false) {
		show_error($articles->last_error);
	} else {
		$containers = $articles->getContainers();
		$from = @$_GET['from'];
		$until = @$_GET['until'];
		$result = '';
		$system->config['pagename'] = __('Search results');
		foreach($articles_list as $id => $time) {
			$id = explode('.', $id);
			if((!$from || $time >= $from) && (!$until || $time <= $until)){
				if((($cat_data = $articles->getCategory($id[0], false)) !== false || $c == '#root') && ($article = $articles->getArticle($id[0], $id[1], true, true, false, false)) !== false){
					$result .= rcms_parse_module_template('art-article.tpl', $article + array('showtitle' => true,
					'linktext' => $articles->linktextArticle($article['text_nonempty'], $article['comcnt'], $article['views']),
					'linkurl' => '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $article['catid'] . '&amp;a=' . $article['id'],
					'cat_data' => @$cat_data));
				}
			}
		}
		$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . '<a class="winheader" href="?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '">' . $containers[$c] . '</a> &rarr; ' . __('Search results');
		show_window($title, $result);
	}
} elseif (!empty($_GET['from']) || !empty($_GET['until'])){
	$result = '';
	foreach ($articles->getContainers(0) as $c => $null){
		if(!$articles->setWorkContainer($c)){
			show_error($articles->last_error);
		} elseif(($articles_list = $articles->getStat('time')) === false) {
			show_error($articles->last_error);
		} else {
			$containers = $articles->getContainers();
			$from = @$_GET['from'];
			$until = @$_GET['until'];
			$system->config['pagename'] = __('Search results');
			foreach($articles_list as $id => $time) {
				$id = explode('.', $id);
				if((!$from || $time >= $from) && (!$until || $time <= $until)){
					if((($cat_data = $articles->getCategory($id[0], false)) !== false || $c == '#root') && ($article = $articles->getArticle($id[0], $id[1], true, true, false, false)) !== false){
						$result .= rcms_parse_module_template('art-article.tpl', $article + array('showtitle' => true,
						'linktext' => $articles->linktextArticle($article['text_nonempty'], $article['comcnt'], $article['views']),
						'linkurl' => '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $article['catid'] . '&amp;a=' . $article['id'],
						'cat_data' => @$cat_data));
					}
				}
			}
		}
	}
	$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . __('Search results');
	show_window($title, $result);
} elseif (!empty($c) && (!empty($b) || $c == '#root')){
	if(!$articles->setWorkContainer($c)){
		show_error($articles->last_error);
	} elseif(($contents = $articles->getArticles($b, true, true, false)) === false) {
		show_error($articles->last_error);
	} elseif($c !== '#root' && !($category = $articles->getCategory($b, false))) {
		show_error($articles->last_error);
	} else {
		$containers = $articles->getContainers();
		$result = '';
		if($c !== '#root') {
			$system->config['pagename'] = ((mb_strlen($category['title'])>$articles->config['category']) ? mb_substr($category['title'], 0, $articles->config['category']) . '...' : $category['title']);
		} else {
			$system->config['pagename'] = $containers[$c];
		}
		$contents = array_reverse($contents);
		if(!empty($system->config['perpage'])) {
			$pages = ceil(sizeof($contents)/$system->config['perpage']);
			if(!empty($_GET['page']) && ((int) $_GET['page']) > 0) $page = ((int) $_GET['page'])-1; else $page = 0;
			$start = $page * $system->config['perpage'];
			$total = $system->config['perpage'];
		} else {
			$pages = 1;
			$page = 0;
			$start = 0;
			$total = sizeof($contents);
		}
		$result .= '<div align="right">' . rcms_pagination(sizeof($contents), $system->config['perpage'], $page+1, '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $b) . '</div>';
		for ($a = $start; $a < $total+$start; $a++){
			$article = &$contents[$a];
			if(!empty($article)){
				if($c !== '#root') {
					$result .= rcms_parse_module_template('art-article.tpl', $article + array('showtitle' => true,
					'linktext' => $articles->linktextArticle($article['text_nonempty'], $article['comcnt'], $article['views']),
					'linkurl' => '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $b . '&amp;a=' . $article['id'],
					'cat_data' => $category));
				} else {
					$result .= rcms_parse_module_template('art-article.tpl', $article + array('showtitle' => true,
					'linktext' => $articles->linktextArticle($article['text_nonempty'], $article['comcnt'], $article['views']),
					'linkurl' => '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;a=' . $article['id']));
				}
			}
		}
		if($c !== '#root') {
			$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . '<a class="winheader" href="?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '">' . $containers[$c] . '</a> &rarr; ' . ((mb_strlen($category['title'])>$articles->config['category']) ? mb_substr($category['title'], 0, $articles->config['category']) . '...' : $category['title']);
		} else {
			$title = '<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . $containers[$c];
		}
		show_window($title, $result);
	}
} elseif (!empty($c)){
	if(!$articles->setWorkContainer($c)){
		show_error($articles->last_error);
	} else {
		if(($categories = $articles->getCategories(false, true, true)) === false ){
			show_error($articles->last_error);
		} else {
			$containers = $articles->getContainers();
			$result = '';
			foreach($categories as $category) {
				$result .= rcms_parse_module_template('art-category.tpl', $category + array('link' => '?module=' . $module . '&amp;c=' . str_replace('#', '%23', $c) . '&amp;b=' . $category['id']));
			}
			show_window('<a class="winheader" href="?module=' . $module . '">' . __('Sections') . '</a> &rarr; ' . $containers[$c], $result);//Show list container
			$system->config['pagename'] = $containers[$c];
		}
	}    
} else {
  $result = '';
  $containers = $articles->getContainers();
  foreach ($containers as $container_id => $container_title) {
    if (mb_substr($container_id, 0, 1) != '#') { 
    if ($articles->setWorkContainer($container_id) == true) {
       $siteUrl = '.';
        $result .= "\n<ul>\n";
        $result .= "<li type=\"disc\"><a href=\"".$siteUrl.'/?module=articles&amp;c='.str_replace('#','%23',$container_id).'">'.$container_title."</a></li>\n<li style=\"visibility: hidden\">\n";
if ($categories = $articles->getCategories(false, false, false)) {  
          $result .= "<ul>\n";
          foreach ($categories as $category) {
            $result .= "<li style=\"visibility: visible\"><a href=\"".$siteUrl.'/?module=articles&amp;c='.str_replace('#','%23',$container_id).'&amp;b='.$category['id'].'">'.$category['title'].'</a>';
			if (!empty($category['articles_clv'])) $result .= "<sup>".$category['articles_clv']."</sup>";
            $result .= "</li>\n";
          }
          $result .= "</ul>\n";
        }
        $result .= "</li>\n</ul>\n";
      }
    }
  }
  $result .= '<br/>';
  if ($system->checkForRight('ARTICLES-ADMIN')) 
	$result .= '<a href="' . RCMS_ROOT_PATH . 'admin.php?show=module&id=articles.containers">' . __('Manage sections') . '</a><br/>';
  $system->config['pagename'] = __('Containers');
  show_window(__('Containers'), rcms_parse_module_template('art-container.tpl', $result));
}
?>
