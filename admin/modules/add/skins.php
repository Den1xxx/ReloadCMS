<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if (!defined('SKINS_MANAGE_DIR')) define('SKINS_MANAGE_DIR',dirname(__FILE__).'/skins/');


// Interface generation
if (isset($_POST['edit'])) {
include (SKINS_MANAGE_DIR.$_POST['edit']);
} else {

    $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
    $frm->addbreak(__('Skins configuration'));
    $menus = rcms_scandir(SKINS_MANAGE_DIR);
    foreach ($menus as $id=>$menu){
	        $frm->addrow(__('Skin') . ':  ' . str_replace('.php','',$menu),
            $frm->radio_button('edit', array($menu => __('Edit')))
			);
    }
    $frm->show();
	}
?>