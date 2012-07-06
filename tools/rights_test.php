<?php
function rcms_scandir($directory) {
    $dir = array();
    if(is_dir($directory)){
        $fh = opendir($directory);
        while (false !== ($filename = readdir($fh))) if(substr($filename, 0, 1) != '.') array_push($dir, $filename);
        closedir($fh);
	}
	if($exp != ''){
        $files = array();
        natsort($dir);
        for ($i=0; $i < sizeof($dir); $i++) if (preg_match($exp, $dir[$i])) $files[] = $dir[$i];
        return $files;
	} else return $dir;
}

function show_perms($file) {
   $in_Perms = fileperms($file);
   $sP = "<b>";
   if($in_Perms & 0x1000) $sP .= 'p';            // FIFO pipe
   elseif($in_Perms & 0x2000) $sP .= 'c';        // Character special
   elseif($in_Perms & 0x4000) $sP .= 'd';        // Directory
   elseif($in_Perms & 0x6000) $sP .= 'b';        // Block special
   elseif($in_Perms & 0x8000) $sP .= '&minus;';  // Regular
   elseif($in_Perms & 0xA000) $sP .= 'l';        // Symbolic Link
   elseif($in_Perms & 0xC000) $sP .= 's';        // Socket
   else $sP .= 'u';                              // UNKNOWN
   $sP .= "</b>";
   // owner - group - others
   $sP .= (($in_Perms & 0x0100) ? 'r' : '&minus;') . (($in_Perms & 0x0080) ? 'w' : '&minus;') . (($in_Perms & 0x0040) ? (($in_Perms & 0x0800) ? 's' : 'x' ) : (($in_Perms & 0x0800) ? 'S' : '&minus;'));
   $sP .= (($in_Perms & 0x0020) ? 'r' : '&minus;') . (($in_Perms & 0x0010) ? 'w' : '&minus;') . (($in_Perms & 0x0008) ? (($in_Perms & 0x0400) ? 's' : 'x' ) : (($in_Perms & 0x0400) ? 'S' : '&minus;'));
   $sP .= (($in_Perms & 0x0004) ? 'r' : '&minus;') . (($in_Perms & 0x0002) ? 'w' : '&minus;') . (($in_Perms & 0x0001) ? (($in_Perms & 0x0200) ? 't' : 'x' ) : (($in_Perms & 0x0200) ? 'T' : '&minus;'));
   return $sP;
}

function checkrights($el, $rec = false) {
    $perms = show_perms($el);
    if(is_readable($el) && is_writeable($el)) $color = 'green'; else $color = 'red';
    echo '
<tr>
    <td width="100%" class="row1" style="color: ' . $color . '">' . $el . '</td>
    <td class="row2" nowrap>' . show_perms($el) . '</td>
</tr>';
    if(is_dir($el) && $rec) {
        $dir = rcms_scandir($el);
        foreach ($dir as $del) checkrights($el . '/' . $del, true);
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <link rel="stylesheet" href="./style.css" type="text/css">
</head>
<body>
<table cellspacing="1" cellpadding="3" border="0" width="100%">
<tr>
    <th colspan="3">ReloadCMS rights test</th>
</tr>
<?php
checkrights('../config', true);
checkrights('../content', true);
?>
</table>
</body>
</html>