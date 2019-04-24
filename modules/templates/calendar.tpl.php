<table width="100%" border="0" cellspacing="1" cellpadding="0">
    <tr><th align="center" colspan="7"><?=rcms_date_localise(date('F Y', $tpldata['_temp']['first_day_stamp']))?></th></tr>
<tr>
<?=rcms_date_localise('<th align="center">Mon</th><th align="center">Tue</th><th align="center">Wed</th><th align="center">Thu</th><th align="center">Fri</th><th align="center">Sat</th><th align="center">Sun</th>')?>
</tr>
<?
//Calendar rows
$days_showed = 1;
$cwpos = $tpldata['_temp']['first_day_week_pos'];
if($cwpos == 0) $cwpos = 7;
while($days_showed <= $tpldata['_temp']['number_of_days']){
    echo  '<tr>';
    if($cwpos > 1) {
        echo  '<td colspan="' . ($cwpos-1) . '">&nbsp;</td>';
    }
    $inc = 0;
    for ($i = $days_showed; $i < $days_showed + 7 && $i <= $tpldata['_temp']['number_of_days'] && $cwpos <= 7; $i++){
        $class = '';
        if(!empty($tpldata['_highlight'][$i])) {
            $class = 'special ';
        }
        if(empty($tpldata['_events'][$i])) {
            $class .= 'row2';
        } else {
            $class .= 'row3';
        }
        if(empty($tpldata['_events'][$i])) {
            echo  '<td align="center" class="' . $class . '">' . $i . '</td>';
        } else {
            echo  '<td align="center" class="' . $class . '"><a href="' . $tpldata['_events'][$i] . '"  class="' . $class . '">' . $i . '</a></td>';
        }
        $cwpos++;
        $inc++;
    }
    $days_showed = $days_showed + $inc;
    $cwpos = 0;
    echo  '</tr>';
}
?>      
</table>