<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$filesdb = new linksdb(DOWNLOADS_DATAFILE);
$files = array_reverse($filesdb->getLastFiles(10));
$i=2;
$lst_c_id = -1;
if(!empty($files)){
    $result = '<ul style="margin: 0px 0px 0px 0px; text-indent: 0px; list-style-type: none; padding: 0px 0px 0px 0px;">';
    foreach($files as $id) {
        if($lst_c_id !== $id[0]) {
            $result .= '<li style="text-align: center"><a href="?module=filesdb&amp;id=' . ($id[0]+1) . '" style="font-weight: bold;">' . $filesdb->data[$id[0]]['name'] . '</a></li>';
            $lst_c_id = $id[0];
        }
        $result .= '<li class="row' . $i . '"><a href="?module=filesdb&amp;id=' . ($id[0]+1) . '&amp;fid=' . ($id[1]+1) . '">' . $filesdb->data[$id[0]]['files'][$id[1]]['name'] . '</a></li>';
        $i++;
        if($i>3) $i=2;
    }
    $result .= '</ul>';
    show_window(__('Last files'), $result);
}
?>
