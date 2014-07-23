<?
//New pagination
        $pages = ceil($tpldata['total']/$tpldata['perpage']);
		$return='';
        if($pages != 1){
		    $c = 1;
            while($c <= $pages){
                if($c != $tpldata['current']) $return .= ' ' . '<a href="' . $tpldata['link'] . '&amp;page=' . $c . '">' . $c . '</a> ';
                else $return .= ' (' . $c . ') ';
                $c++;
            }
		$prev=($tpldata['current']>1)?'<a href="' . $tpldata['link'] . '&amp;page=' . ($tpldata['current']-1) . '">&larr;' . __('Back') . '</a> ':'';
		$next=($tpldata['current']<$pages)?'<a href="' . $tpldata['link'] . '&amp;page=' . ($tpldata['current']+1) . '">' . __('Forward') . '&rarr;</a> ':'';
		$return = $prev.$return.$next;
        }
?>
<?=$return?>
<?
/*	
//Old pagination	
$old='';
        $pages = ceil($tpldata['total']/$tpldata['perpage']);
        if($pages != 1){
            $c = 1;
            while($c <= $pages){
                if($c != $tpldata['current']) $old .= ' [' . '<a href="' . $tpldata['link'] . '&amp;page=' . $c . '">' . $c . '</a>] ';
                else $old .= ' [' . $c . '] ';
                $c++;
            }
        }	
<?=$old?>		
*/		
?>
