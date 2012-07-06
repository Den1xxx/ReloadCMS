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

if(!empty($_POST['remove'])){
    if(!$polls->removePollFromArchive($_POST['remove'])){
        rcms_showAdminMessage($polls->lasterror);
    } else {
        rcms_showAdminMessage(__('Poll removed'));
        $save_o = true;
    }
}

$polls->close($save_c, $save_o);

$poll = $polls->getArchivedPolls();
if(!empty($poll)){
	foreach ($poll as $pollid => $polldata){
	    $frm =new InputForm ('', 'post', __('Submit'));
    	$frm->addbreak(__('Question') . ': ' . $polldata['q']);
    	foreach ($polldata['v'] as $id => $answer) $frm->addrow($polldata['c'][$id], $answer);
    	$frm->addrow($frm->checkbox('remove', $pollid, __('Remove poll from archive')));
    	$frm->show();
	}
} else {
    rcms_showAdminMessage(__('Archive poll is empty'));
}
?>