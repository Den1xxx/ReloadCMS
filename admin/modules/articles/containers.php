<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$articles = new articles();

if (!empty($_POST['newsave'])){
    if($articles->createContainer($_POST['id'], $_POST['title'])) {
        rcms_showAdminMessage(__('Section created'));
    } else rcms_showAdminMessage($articles->last_error);
} elseif(!empty($_POST['delete']) && is_array($_POST['delete'])) {
    $result = '';
    foreach ($_POST['delete'] as $id => $cond){
        if(!empty($cond)){
            if($articles->removeContainer($id)) {
                $result .= __('Section deleted') . ' (' . $id . ')<br/>';
            } else {
                $result .= $articles->last_error . ' (' . $id . ')<br/>';
            }
        }
    }
    rcms_showAdminMessage($result);
    $_POST['edit'] = '';
} elseif (!empty($_POST['edit']) && !empty($_POST['save'])){
    if($articles->editContainer($_POST['edit'], $_POST['id'], $_POST['title'])){
        rcms_showAdminMessage(__('Section updated'));
        $_POST['edit'] = $_POST['id'];
    } else rcms_showAdminMessage($articles->last_error);
}

////////////////////////////////////////////////////////////////////////////////
// Interface generation                                                       //
////////////////////////////////////////////////////////////////////////////////
$containers = $articles->getContainers();
if(!empty($_POST['new'])){
    $frm = new InputForm ('', 'post', __('Submit'));
    $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
    $frm->addbreak(__('Create section'));
    $frm->hidden('newsave', '1');
    $frm->addrow(__('ID'), $frm->text_box('id', ''));
    $frm->addrow(__('Title'), $frm->text_box('title', ''));
    $frm->show();
} elseif(!empty($_POST['edit'])){
    if(!empty($containers[$_POST['edit']])){
        $container = &$containers[$_POST['edit']];
        $frm = new InputForm ('', 'post', __('Submit'));
        $frm->addmessage('<a href="">&lt;&lt;&lt; ' . __('Back') . '</a>');
        if($_POST['edit'] != '#root' && $_POST['edit'] != '#hidden') {
            $frm->addbreak(__('Edit section'));
            $frm->hidden('save', '1');
            $frm->hidden('edit', $_POST['edit']);
            $frm->addrow(__('ID'), $frm->text_box('id', $_POST['edit']));
            $frm->addrow(__('Title'), $frm->text_box('title', $container));
        }
        $frm->show();
    } else rcms_showAdminMessage(__('Section with this ID doesn\'t exists'));
} else {
    $frm = new InputForm ('', 'post', __('Create section'));
    $frm->hidden('new', '1');
    $frm->show();
    $frm = new InputForm ('', 'post', __('Submit'), __('Reset'));
    if(!empty($containers)){
        foreach ($containers as $id => $title){
            if($id != '#root' && $id != '#hidden') {
                $frm->addrow($title, $frm->checkbox('delete[' . $id . ']', '1', __('Delete')) . ' ' . $frm->radio_button('edit', array($id => __('Edit')), 0));
            }
        }
    }
    $frm->show();
}
?>