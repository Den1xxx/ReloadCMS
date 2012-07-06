<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$articles = new articles();
$current_year  = rcms_format_time('Y', mktime());    
$current_month = rcms_format_time('n', mktime()); 
if (!empty($_POST['cal-year']) && $_POST['cal-year'] >= $current_year - 6 && $_POST['cal-year'] <= $current_year)
     $selected_year = $_POST['cal-year'];             
else $selected_year = $current_year;                  
if (!empty($_POST['cal-month']) && $_POST['cal-month'] >= 1 && $_POST['cal-month'] <= 12)
     $selected_month = $_POST['cal-month'];           
else $selected_month = $current_month;              

$calendar = new calendar($selected_month, $selected_year);
foreach ($articles->getContainers(0) as $container => $null) {
    $articles->setWorkContainer($container);
    if (($list = $articles->getStat('time'))) {
        foreach($list as $id => $time) {
            $id = explode('.', $id);
            if ((rcms_format_time('n', $time) == $selected_month) && (rcms_format_time('Y', $time) == $selected_year)) {
                $calendar->assignEvent(rcms_format_time('d', $time), '?module=articles&amp;from='.mktime(0, 0, 0, $selected_month, rcms_format_time('d', $time), $selected_year).'&amp;until='.mktime(23, 59, 59, $selected_month, rcms_format_time('d', $time), $selected_year));
            }
        }
    }
}
$calendar->highlightDay(rcms_format_time('d', mktime()));
$date_pick = '<form action="" method="post" style="text-align: center">
               <select name="cal-month">';
foreach (array('January', 'February', 'March', 'April', 'May',
               'June', 'July','August', 'September', 'October',
               'November', 'December') as $num => $month) {
    $date_pick .= '<option value="'.($num + 1).'"'.(($num == $selected_month - 1) ? ' selected="selected"' : '').'>'.$lang['datetime'][$month].'</option>';
}
$date_pick .= '</select>
               <select name="cal-year">';
for ($y = $current_year - 6; $y <= $current_year; $y++) {
    $date_pick .= '<option value="'.$y.'"'.(($y == $selected_year) ? ' selected="selected"' : '').'>'.$y.'</option>';
}
$date_pick .= '</select>
               <input value="OK" type="submit" />
              </form>';
show_window(__('Articles calendar'), $calendar->returnCalendar().$date_pick);
?>
