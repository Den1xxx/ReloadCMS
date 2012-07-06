<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$articles = new articles();

if(!empty($_POST['newsave'])){
    if($articles->setWorkContainer($_POST['c']) && $articles->createCategory($_POST['ctitle'], @$_POST['cdesc'], @$_FILES['cicon'], @$_POST['caccess'])) {
        rcms_showAdminMessage(__('Category created'));
    } else rcms_showAdminMessage($articles->last_error);
} elseif(!empty($_POST['delete']) && is_array($_POST['delete']) && !empty($_POST['c']) && $articles->setWorkContainer($_POST['c'])) {
    $result = '';
    foreach ($_POST['delete'] as $id => $cond){
        if(!empty($cond)){
            if($articles->deleteCategory($id)) {
                $result .= __('Category removed') . ' (' . $_POST['c'] . ':' . $id . ')<br/>';
            } else {
                $result .= $articles->last_error . ' (' . $_POST['c'] . ':' . $id . ')<br/>';
            }
        }
    }
    rcms_showAdminMessage($result);
    unset($_POST['edit']);
} elseif (!empty($_POST['b']) && !empty($_POST['c']) && !empty($_POST['save'])){
    if($articles->setWorkContainer($_POST['c']) && $articles->editCategory($_POST['b'], $_POST['ctitle'], @$_POST['cdesc'], @$_FILES['cicon'], @$_POST['caccess'], @$_POST['ckillicon'])) {
        rcms_showAdminMessage(__('Category updated'));
    } else rcms_showAdminMessage($articles->last_error);
}

////////////////////////////////////////////////////////////////////////////////
// Interface generation                                                       //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['new'])){
    $frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back'));
    $frm->hidden('c', $_POST['c']);
    $frm->show();
    
    $frm = new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data', 'mainfrm');
    $frm->addbreak(__('Create new category'));
    $frm->hidden('newsave', '1');
    $frm->hidden('c', $_POST['c']);
    $frm->addrow(__('Title'), $frm->text_box('ctitle', ''), 'top');
    $frm->addrow(rcms_show_bbcode_panel('mainfrm.cdesc'));
    $frm->addrow(__('Description'), $frm->textarea('cdesc', '', 70, 5), 'top');
    $frm->addrow(__('Minimum access level'), $frm->text_box('caccess', '0'), 'top');
    $frm->addrow(__('Icon for category'), $frm->file('cicon'), 'top');
    $frm->show();
} elseif(!empty($_POST['b']) && !empty($_POST['c']) && $articles->setWorkContainer($_POST['c']) && $category = $articles->getCategory($_POST['b'], false)){
    $frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back'));
    $frm->hidden('c', $_POST['c']);
    $frm->show();
    
    $frm = new InputForm ('', 'post', __('Submit'), '', '', 'multipart/form-data', 'mainfrm');
    $frm->addbreak(__('Edit category'));
    $frm->hidden('save', '1');
    $frm->hidden('b', $_POST['b']);
    $frm->hidden('c', $_POST['c']);
    $frm->addrow(__('Title'), $frm->text_box('ctitle', $category['title']), 'top');
    $frm->addrow(rcms_show_bbcode_panel('mainfrm.cdesc'));
    $frm->addrow(__('Description'), $frm->textarea('cdesc', $category['description'], 70, 5), 'top');
    $frm->addrow(__('Minimum access level'), $frm->text_box('caccess', $category['accesslevel']), 'top');
    if(!$category['icon']) $frm->addrow(__('Icon for category'), $frm->file('cicon'));
    else $frm->addrow(__('Icon for category') . ' - ' . $category['icon'] . '<br />' . __('Delete') . '?', $frm->checkbox('ckillicon', '1', ''));
    $frm->show();
} elseif(!empty($_POST['c'])) {
    $frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back')); $frm->show();
    if($articles->setWorkContainer($_POST['c'])){
        if(($categories = $articles->getCategories()) !== false){
            $frm = new InputForm ('', 'post', __('Add category'));
            $frm->hidden('new', $_POST['c']);
            $frm->hidden('c', $_POST['c']);
            $frm->show();
            $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
            $frm->hidden('c', $_POST['c']);
            if(!empty($categories)){
                foreach ($categories as $cat_data){
                    $frm->addrow($cat_data['title'],
                        $frm->checkbox('delete[' . $cat_data['id'] . ']', '1', __('Delete')) . ' ' .
                        $frm->radio_button('b', array($cat_data['id'] => __('Edit')), 0), 'top'
                    );
                }
            }
            $frm->show();
        } else rcms_showAdminMessage($articles->last_error);
    } else rcms_showAdminMessage($articles->last_error);
} else {
    $frm =new InputForm ('', 'post', __('Browse'));
    $frm->addrow(__('Select section'), $frm->select_tag('c', $articles->getContainers(0)));
    $frm->show();
}
?>