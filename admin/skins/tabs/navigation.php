<?php 
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////
$tab=0;
$tabs=
'<li>
<a href="#tab-'.$tab.'">'.__('Start').'</a>
</li>';
$divs=
'
<div id="tab-'.$tab.'">
<table>
<tr>
<td class="white">
'.__('Return to').' &#0187; <a href="'.RCMS_ROOT_PATH.'" target="_top">'.__('site index').'</a> / 
<a href="?show=module&tab='.$tab.'">'.__('admin index').'</a>
</td><td class="white">
<form id="admin_skin_select" name="admin_skin_select" method="post" action="">
'.__('Skin').' '.user_skin_select(ADMIN_PATH.'skins/', 'admin_selected_skin', $admin_skin, '',
	'onchange="document.forms[\'admin_skin_select\'].submit()" title="' . __('Skin') . '"').
'</form>
</td><td class="white">
<form id="tablelogin" method="post" action="'.RCMS_ROOT_PATH.'">
<input type="hidden" value="1" name="logout_form">
 &mdash; '.__('Hello') . ', ' . $system->user['nickname'].'!
<a href="?module=user.profile">'.__('My profile').'</a>
<input class="button" type="submit" value="'.__('Log out').'">
</form>
</td>
</tr>
</table>
</div>
';
foreach($MODULES as $category => $blockdata) {
$tab++;
    if(!empty($blockdata[1]) && is_array($blockdata[1])) { 
$tabs.=	'<li><a href="#tab-'.$tab.'">'.$blockdata[0].'</a></li>';
$divs.='<div id="tab-'.$tab.'">';
 foreach($blockdata[1] as $module => $title)  
$divs.='&nbsp;<a href="?show=module&id='.$category.'.'.$module.'&tab='.$tab.'">'.$title.'</a>&nbsp;';
$divs.='</div>';
	} elseif($blockdata[0] === @$blockdata[1]) {
$tabs.='<li><a href="?show=module&id='.$category.'.index">'.$blockdata[0].'</a></li>';
	}
}
echo '<ul>'.$tabs.'</ul>
'.$divs;