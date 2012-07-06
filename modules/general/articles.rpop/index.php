<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$articles = new articles();
$articles->setWorkContainer('articles');
if(($list = $articles->getLimitedStat('view', $system->config['num_of_latest'], true))){
    $result = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
    $i=2;
    foreach($list as $id => $time) {
        $id = explode('.', $id);
        if(($article = $articles->getArticle($id[0], $id[1], false, false, false, false)) !== false){
            $result .= '<tr><td class="row' . $i . '"><a href="index.php?module=articles&amp;c=articles&amp;b=' . $id[0] . '&amp;a=' . $id[1] . '"><abbr title="' . $article['author_nick'] . ', ' .  rcms_format_time('d.m.Y H:i:s', $article['time']) . '">' . $article['title'] . ' (' . $article['comcnt'] . ')</abbr></a></td></tr>';
            $i++;
            if($i > 3) $i = 2;
        }
    }
    $result .= '</table>';
    show_window(__('Most readable articles'), $result);
}
?>
