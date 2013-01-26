<?php
if(empty($system->config['disable_stats']) && $stats = statistic_get()){
    $guests_count = 0;
    foreach ($stats['online'] as $ip => $data){
        if($data['name'] == 'guest'){
            $guests_count++;
        }
    }
    $result = '
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr>';
    if(!empty($stats['online']) || !empty($stats['guests_online'])){
        $result .= '
    <td align="left" class="row1" colspan="2">' . __('Online') . ' - ' . sizeof($stats['online']) . ' (' . (sizeof($stats['online']) - $guests_count) . ' ' . __('registered') . ')</td>
</tr>';
    }
     if(!empty($stats['online'])){
    $result .= '
<tr>
    <td align="right" class="row2" colspan="2">';
    $i = 0;
    foreach ($stats['online'] as $ip => $data){
        if($data['name'] !== 'guest'){
            if($i != 0) $result .= ', ';
            $result .= user_create_link($data['name'], $data['nick']);
            $i++;
        }
    }
    if($i > 1) $result .= '.';
        $result .= '
    </td>
</tr>';
    }
    $result .= '
<tr>
    <td align="left" class="row1" width="100%">' . __('Total hits') . '</td><td align="right" class="row2" nowrap="nowrap">' . $stats['totalhits'] . '&nbsp;</td>
</tr>
<tr>
    <td align="left" class="row1">' . __('Today hits') . '</td><td align="right" class="row2" nowrap="nowrap">' . $stats['todayhits'] . '&nbsp;</td>
</tr>
<tr>
    <td align="left" class="row1">' . __('Today hosts') . '</td><td align="right" class="row2" nowrap="nowrap">' . sizeof($stats['todayhosts']) . '&nbsp;</td>
</tr>
</table>';
    show_window(__('Counter'), $result, 'center');
}
?>
