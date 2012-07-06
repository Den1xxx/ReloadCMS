<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$filesdb = new linksdb(DOWNLOADS_DATAFILE);
$files = array_reverse($filesdb->getLastFiles(10));
$i=2;
if(!empty($files)){
    foreach($files as $id) {
        $feed->addItem($filesdb->data[$id[0]]['files'][$id[1]]['name'] . ' [' . $filesdb->data[$id[0]]['files'][$id[1]]['author'] . ']',
            htmlspecialchars($filesdb->data[$id[0]]['files'][$id[1]]['desc']),
            'http://'.$_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'] . basename($_SERVER['SCRIPT_NAME']))  . '?module=' . $module . '&amp;id=' . ($id[0]+1) . '&amp;fid=' . ($id[1]+1) . '&amp;a=' . $id[1],
            $filesdb->data[$id[0]]['files'][$id[1]]['date']);
    }
    $i++;
    if($i>3) $i=2;
}
?>
