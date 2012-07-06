<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$polls = new polls;
$polls->openCurrentPolls();
$save_o = false;
$save_c = false;

if(!empty($_POST['new'])){
    if(!$polls->startPoll($_POST['poll_question'], $_POST['poll_variants'])){
        rcms_showAdminMessage($polls->lasterror);
    } else {
        rcms_showAdminMessage(__('Poll created'));
        $save_c = true;
    }
}

if(!empty($_POST['stop'])){
    if(!$polls->removePoll($_POST['stop'], true)){
        rcms_showAdminMessage($polls->lasterror);
    } else {
        rcms_showAdminMessage(__('Poll stopped'));
        $save_o = true;
        $save_c = true;
    }
}

if(!empty($_POST['remove'])){
    if(!$polls->removePoll($_POST['remove'], false)){
        rcms_showAdminMessage($polls->lasterror);
    } else {
        rcms_showAdminMessage(__('Poll removed'));
        $save_c = true;
    }
}

$polls->close($save_c, $save_o);

foreach ($polls->getCurrentPolls() as $pollid => $polldata){
    $frm =new InputForm ('', 'post', __('Submit'));
    $frm->addbreak(__('Question') . ': ' . $polldata['q']);
    foreach ($polldata['v'] as $id => $answer) $frm->addrow($polldata['c'][$id], $answer);
    $frm->addrow($frm->checkbox('stop', $pollid, __('Stop poll and move it to archive')), $frm->checkbox('remove', $pollid, __('Remove poll without moving to archive')));
    $frm->show();
}

$frm =new InputForm ('', 'post', __('Submit'));
$frm->addbreak(__('New poll'));
$frm->hidden('new', '1');
$frm->addrow(__('Question'), $frm->text_box('poll_question', '', 40));
$frm->addrow(__('Answers'), $frm->textarea('poll_variants', '', 50, 10), 'top');
$frm->show();
?>