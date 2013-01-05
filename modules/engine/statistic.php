<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
function statistic_register(){
    global $system;
    if(is_file(DATA_PATH . 'stats.dat')) {
        $stats = @file(DATA_PATH . 'stats.dat');
        $stats = @unserialize($stats[0]);
    }
    $userip    = ($_SERVER['REMOTE_ADDR']);
    $referer   = ($_SERVER['HTTP_REFERER']);
    $page      = ($_SERVER['REQUEST_URI']);
    
    // Add popularity to referer
    if(!empty($referer) && $referer = parse_url($referer)) {
        if(!empty($stats['ref'][$referer['host']])) {
            $stats['ref'][$referer['host']]++;
        } else {
            $stats['ref'][$referer['host']] = 1;
        }
    }
    
    //Add popularity to page
     if(!empty($stats['popular'][$page])) {
         $stats['popular'][$page]++;
     } else {
         $stats['popular'][$page] = 1;
     }
    
    // Register last user's visit time
    $stats['ips'][$userip] = time();
    
    // Register user in total hits count
    if(!empty($stats['totalhits'])) {
        $stats['totalhits']++;
    } else {
        $stats['totalhits'] = 1;
    }
    
    // Check the last update time
    if(!empty($stats['update']) && $stats['update'] < mktime(0, 0, 0, date('n'), date('j'), date('Y'))) {
     if (!empty($stats['ref']))  { 
		rcms_log_put(__('Today referers'), 'Last', $stats['ref']); // Save yestarday's referers
        unset($stats['ref']);  // Remove yestarday's referers
		}         
     unset($stats['todayhits']);     // Remove yestarday's hits
     unset($stats['todayhosts']);    // Remove yestarday's hosts
    }
    
    if(!empty($stats['todayhits'])) {
        $stats['todayhits']++;
    } else {
        $stats['todayhits'] = 1;
    }
    
    // Today hosts counter
    if(!empty($stats['todayhosts'][$userip])) {
        $stats['todayhosts'][$userip]++;
    } else $stats['todayhosts'][$userip] = 1;
    
    // Online users
    $stats['online'][$userip]['name'] = $system->user['username'];
    $stats['online'][$userip]['nick'] = $system->user['nickname'];
    $stats['online'][$userip]['time'] = rcms_get_time();
    $online = array();
    $registered_already = array();
    if(!empty($stats['online'])){
        foreach ($stats['online'] as $ip => $data) {
            if($data['time'] > rcms_get_time() - 5 * 60 && !in_array($data['name'], $registered_already)) {
                $online[$ip] = $data;
                $registered_already[] = $data['name'];
            }
        }
    }
    $stats['online'] = $online;
    
    // Update time's update
    $stats['update'] = rcms_get_time();
    
    @file_write_contents(DATA_PATH . 'stats.dat', serialize($stats));
    return true;    
}

function statistic_get(){
    if(is_file(DATA_PATH . 'stats.dat')) {
        $stats = @file(DATA_PATH . 'stats.dat');
        $stats = @unserialize($stats[0]);
        if(!empty($stats)) {
            striptags_array($stats);
        }
        return $stats;
    } else {
        return false;
    }
}

function striptags_array(&$array){
    foreach ($array as $key => $value) {
        if(is_array($array[$key])) {
            striptags_array($array[$key]);
        } else {
            $array[$key] = strip_tags($value);
        }
    }
    return true;
}

function statistic_clean(){
    return rcms_delete_files(DATA_PATH . 'stats.dat');
}
                                  
if(basename($_SERVER['SCRIPT_FILENAME']) == 'index.php' && empty($system->config['disable_stats'])) statistic_register();
?>