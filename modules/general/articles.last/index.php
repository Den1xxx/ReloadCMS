<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$articles = new articles();
    $result = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
$i = 2;
$arr_res = array();
 foreach (array_keys($articles->getContainers()) as $c){
  if (substr($c, 0, 1) != '#') {
$articles->setWorkContainer($c);
if(($list = $articles->getLimitedStat('time', $system->config['num_of_latest'], true))){
        foreach($list as $id => $time) {
        $id = explode('.', $id);
        if(($article = $articles->getArticle($id[0], $id[1], false, false, false, false)) !== false){
            $arr_res[$article['time']] = '<tr><td class="row' . $i . '"><a href="index.php?module=articles&c='.$c.'&b=' . $id[0] . '&a=' . $id[1] . '"><abbr title="' . $article['author_nick'] . ', ' .  rcms_format_time('d.m.Y H:i:s', $article['time']) . '">' . $article['title'] . ' </abbr><small><sup title="'.__('Comments').': ' . $article['comcnt'] . '">' . $article['comcnt'] . '</sup></small></a></td></tr>';
            $i++;
            if($i > 3) $i = 2;
        }
    }
}
}}
krsort($arr_res);
$arr_res=array_values($arr_res);
for ($k=0; $k < $system->config['num_of_latest']; $k++) 
$result.=$arr_res[$k];
$result .= '</table>';
show_window(__('Latest articles'), $result);
?>