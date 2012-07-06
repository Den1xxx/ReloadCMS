<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['cleanstats'])) statistic_clean();

if($stats = statistic_get()){
    $frm = new InputForm ('', 'post', __('Clean stats'));
    $frm->addbreak(__('Site statistics'));
    $frm->addrow(__('Total hits'), $stats['totalhits']);
    $frm->addrow(__('Today hits'), $stats['todayhits']);
    $frm->addrow(__('Today hosts'), sizeof($stats['todayhosts']));
    $frm->addbreak(__('Popular pages'));
    arsort($stats['popular']);
    array_splice($stats['popular'], 20);
    foreach ($stats['popular'] as $page => $count) $frm->addrow('<a href="'.htmlspecialchars($page).'" target="_blank">'.htmlspecialchars($page).'</a>', $count);
    $frm->addbreak(__('Today users'));
    arsort($stats['todayhosts']);
    foreach ($stats['todayhosts'] as $ip => $count) $frm->addrow($ip,$count);
    $frm->addbreak(__('Today referers'));
    arsort($stats['ref']);
    foreach ($stats['ref'] as $ref => $count) $frm->addrow(htmlspecialchars($ref),$count);
    $frm->hidden('cleanstats', '1');
    $frm->show();
} 

?>