<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

$gallery = new gallery();
$images = $gallery->getFullImagesList();
if(!empty($images)){
    $i = rand(0, sizeof($images) - 1);
    $id = 0;
    foreach ($images as $filename){
        if ($id == $i){
            show_window(__('Random image'), '<a href="?module=gallery&amp;id=' . $filename . '">' . $gallery->getThumbnail($filename) . '</a>', 'center'); 
            break;
        }
        $id++;
    }
}
?>