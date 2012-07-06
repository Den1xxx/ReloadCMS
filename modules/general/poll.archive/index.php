<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$polls = new polls;
$polls->openCurrentPolls();

$result = '';
$cpolls = array_reverse($polls->getArchivedPolls());
if(!empty($cpolls)){
	foreach ($cpolls as $poll){
    	$poll['voted'] = true;
    	$result .= rcms_parse_module_template('poll.tpl', $poll);
	}
} else {
	$result = __('Archive poll is empty');
}
show_window(__('Polls archive'), $result);
$system->config['pagename'] = __('Polls archive');
?>
