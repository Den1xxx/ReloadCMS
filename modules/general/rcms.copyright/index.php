<?php
if(!defined('RCMS_COPYRIGHT_SHOWED')){
	show_window('', '
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	    <td class="row2" style="height: 9px; font-size: 9px; text-align: left;">
	        Powered by
	    </td>
	</tr>
	<tr>
	    <td class="row2" style="height: 17px; font-size: 17px; text-align: center; font-weight: bold;">
	        <a href="' . RCMS_LINK . '">ReloadCMS</a>
	    </td>
	</tr>
	<tr>
	    <td class="row2" style="height: 9px; font-size: 9px; text-align: right;">
	        ' . RCMS_VERSION_A . '.'  . RCMS_VERSION_B . '.' . RCMS_VERSION_C . RCMS_VERSION_SUFFIX . '
	    </td>
	</tr>
	<tr>
	    <td class="row3" style="height: 11px; font-size: 11px; text-align: center;">
	        ' . RCMS_COPYRIGHT . '
	    </td>
	</tr>
	</table>');
	define('RCMS_COPYRIGHT_SHOWED', true);
}
?>
