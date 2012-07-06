<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$cpolls = new polls;
$cpolls->openCurrentPolls();

if(!empty($_POST['vote'])){
    if(!$cpolls->voteInPoll($_POST['vote'], $_POST['poll_vote'], $_SERVER['REMOTE_ADDR'])){
        show_error($cpolls->lasterror);
    } else {
        $cpolls->close(true, false);
    }
}

$result = '';
if($polls = $cpolls->getCurrentPolls()){
    foreach ($polls as $poolid => $poll){
        $poll['voted'] = $cpolls->isVotedInPoll($poolid, $_SERVER['REMOTE_ADDR']);
        $poll['id'] = $poolid;
        $result .= rcms_parse_module_template('poll.tpl', $poll);
    }
}
$result .= '[<a href="?module=poll.archive">' .__('Old polls') . '</a>]';
show_window(__('Poll'), $result, 'center');
?>