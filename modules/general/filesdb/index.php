<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$filesdb = new linksdb(DOWNLOADS_DATAFILE);
$cid = (!empty($_GET['id'])) ? (int)$_GET['id'] - 1 : -1;
$fid = (!empty($_GET['fid'])) ? (int)$_GET['fid'] - 1 : -1;

if(!empty($_GET['get']) && $cid >= 0 && $fid >= 0 && !empty($filesdb->data[$cid]['files'][$fid])) {
    $cdata = &$filesdb->data[$cid];
    if(@$cdata['accesslevel'] > @$system->user['accesslevel'] && !$system->checkForRight('-any-')) {
        show_error(__('Access denied'));
    } else {
        $fdata = &$filesdb->data[$cid]['files'][$fid];
        @$fdata['count']++;
        $filesdb->close();
        $link = (basename($fdata['link']) == $fdata['link']) ? FILES_PATH . $fdata['link'] : $fdata['link'];
        header('Location: ' . $link);
        die('Redirection to <a href="' . $link . '">' . $fdata['link'] . '</a>');
    }
} elseif($cid >= 0 && $fid >= 0 && !empty($filesdb->data[$cid]['files'][$fid])) {
    $cdata = &$filesdb->data[$cid];
    if(@$cdata['accesslevel'] > @$system->user['accesslevel'] && !$system->checkForRight('-any-')) {
        show_error(__('Access denied'));
    } else {
        $system->config['pagename'] = $filesdb->data[$cid]['files'][$fid]['name'];
        $result = '';
        if(!empty($filesdb->data[$cid]['files'][$fid])){
            $fdata = $filesdb->data[$cid]['files'][$fid];
            $fdata['down_url'] = '?module=' . $module . '&amp;id=' . ($cid+1) . '&amp;fid=' . ($fid+1) . '&amp;get=1';
            $result .= rcms_parse_module_template('fdb-file.tpl', $fdata);
        }
        show_window('<a href="?module=' . $module .  '">' . __('Categories of files') . '</a> &rarr; ' . '<a href="?module=' . $module . '&amp;id=' . ($cid + 1) . '">' . $filesdb->data[$cid]['name'] . '</a>', $result, 'center');
    }
} elseif($cid >= 0 && !empty($filesdb->data[$cid])) {
    $cdata = &$filesdb->data[$cid];
    if(@$cdata['accesslevel'] > @$system->user['accesslevel'] && !$system->checkForRight('-any-')) {
        show_error(__('Access denied'));
    } else {
        $system->config['pagename'] = $filesdb->data[$cid]['name'];
        $result = '';
        foreach ($filesdb->data[$cid]['files'] as $fid=>$fdata){
            $filesdb->data[$cid]['files'][$fid]['id'] = $fid;
        }
        $data = array_reverse($filesdb->data[$cid]['files']);
        if(!empty($data)){
            if(!empty($system->config['perpage'])) {
                $pages = ceil(sizeof($data)/$system->config['perpage']);
                if(!empty($_GET['page']) && ((int) $_GET['page']) > 0) $page = ((int) $_GET['page'])-1; else $page = 0;
                $start = $page * $system->config['perpage'];
                $total = $system->config['perpage'];
            } else {
                $pages = 1;
                $page = 0;
                $start = 0;
                $total = sizeof($data);
            }
            if(($total + $start) > sizeof($data)) {
                $finish = sizeof($data);
            } else {
                $finish = $total + $start;
            }
            $result .= '<div align="right">' . rcms_pagination(sizeof($data), $system->config['perpage'], $page+1, '?module=' . $module . '&amp;id=' . ($cid + 1)) . '</div>';
            for ($fid = $start; $fid < $finish; $fid++){
                $fdata = $data[$fid];
                $fdata['down_url'] = '?module=' . $module . '&amp;id=' . ($cid+1) . '&amp;fid=' . ($fdata['id']+1) . '&amp;get=1';
                if(!empty($fdata)) $result .= rcms_parse_module_template('fdb-file.tpl', $fdata);
            }
        }
        show_window('<a href="?module=' . $module .  '">' . __('Categories of files') . '</a> &rarr; ' . $filesdb->data[$cid]['name'], $result, 'center');
    }
} else {
    $system->config['pagename'] = __('Categories of files');
    if(!empty($filesdb->data)){
        $result = '';
        foreach ($filesdb->data as $cid => $cdata){
            if(@$cdata['accesslevel'] <= @$system->user['accesslevel'] || $system->checkForRight('-any-')) {
                $result .= rcms_parse_module_template('fdb-cat.tpl', $cdata + array('link' => '?module=' . $module . '&amp;id=' . ($cid + 1)));
            }
        }
        show_window(__('Categories of files'), $result, 'center');
    } else show_error(__('There is no categories of files'));
}
?>
