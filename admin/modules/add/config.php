<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////


if(!empty($_POST['nconfig'])) {
write_ini_file($_POST['nconfig'], CONFIG_PATH . 'config.ini');
rcms_showAdminMessage(__('Configuration updated'));
}

if (!empty($_POST['redirect'])) {
//delete self host
$_POST['redirect']['from_arr']=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/','',$_POST['redirect']['from_arr']);
$_POST['redirect']['from_arr']=str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$_POST['redirect']['from_arr']);
$_POST['redirect']['to_arr']=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/','',$_POST['redirect']['to_arr']);
$_POST['redirect']['to_arr']=str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$_POST['redirect']['to_arr']);
//safe config to redirecting
file_write_contents(CONFIG_PATH.'redirect.ini',serialize(str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$_POST['redirect'])));
}

//delete redirect file if data empty 
elseif (isset($_POST['meta_tags'])&&is_file(CONFIG_PATH.'redirect.ini')) rcms_delete_files(CONFIG_PATH.'redirect.ini');
if(isset($_POST['meta_tags'])) file_write_contents(DATA_PATH . 'meta_tags.html', $_POST['meta_tags']);
if(isset($_POST['welcome_mesg'])) file_write_contents(DATA_PATH . 'intro.html', $_POST["welcome_mesg"]);
if(isset($_POST['clear_cache'])) {rcms_clear_directory(CACHE_DIR);}

$system->config = parse_ini_file(CONFIG_PATH . 'config.ini');
$config = &$system->config;
if (is_file(CONFIG_PATH.'redirect.ini')) $redirect = unserialize(file_get_contents(CONFIG_PATH.'redirect.ini'));

$avaible_modules = array();
$avaible_modules[''] = __('Latest news');
foreach ($system->modules['main'] as $module => $module_data){
    if($module !== 'index'){
        $avaible_modules[$module] = $module_data['title'];
    }
}

// Interface generation
$frm =new InputForm ('', 'post', __('Submit'));
//Site configuration
$frm->addbreak(__('Site configuration'));
$frm->addrow(__('Your site\'s title'), $frm->text_box("nconfig[title]", @$config['title'], 60));
$frm->addrow(__('Your site\'s slogan'), $frm->text_box("nconfig[slogan]", @$config['slogan'], 60));
$frm->addrow(__('Do not show sitename in title'), $frm->checkbox('nconfig[hide_title]', '1', '', @$config['hide_title']));
$frm->addrow(__('Your site\'s URL') . '<br />' . __('Leave empty for autodetect'), $frm->text_box("nconfig[site_url]", $config['site_url'], 40));
$frm->addrow(__('Copyright for your content'), $frm->text_box("nconfig[copyright]", @$config['copyright'], 60));
$frm->addrow(__('Enable RSS'), $frm->checkbox('nconfig[enable_rss]', '1', '', @$config['enable_rss']));
$frm->addrow(__('Enable logging'), $frm->checkbox('nconfig[logging]', '1', '', @$config['logging']));
$frm->addrow(__('Enable IDS (logging must be enabled)'), $frm->checkbox('nconfig[enable_ids]', '1', '', @$config['enable_ids']));
$frm->addrow(__('Number of element that will be considered as latest'), $frm->text_box('nconfig[num_of_latest]', @$config['num_of_latest']));
$frm->addrow(__('Number of elements per page'), $frm->text_box('nconfig[perpage]', @$config['perpage']));
$frm->addrow(__('Module on index page'), $frm->select_tag('nconfig[index_module]', $avaible_modules, @$config['index_module']));
$frm->addrow(__('Hide welcome message'), $frm->checkbox('nconfig[wmh]', '1', '', @$config['wmh']));
$frm->addrow(__('Text of Welcome message').tinymce_selector('welcome_mesg'), $frm->textarea('welcome_mesg', file_get_contents(DATA_PATH . 'intro.html'), 70, 20), 'top');
$frm->addrow(__('Additional meta tags for your site'), $frm->textarea('meta_tags', file_get_contents(DATA_PATH . 'meta_tags.html'), 70, 5), 'top');
$frm->addrow(__('Add to external link'). '.<br /> ' .__('Example').": rel='nofollow' class='external'", $frm->text_box('nconfig[addtolink]', @$config['addtolink']));
//Redirect
$add_redirect ='<div>+'.__(' from ')."<input type=\'text\' name=\'redirect[from_arr][]\' size=\'35\' />*"
.__(' to ')."<input type=\'text\' name=\'redirect[to_arr][]\' size=\'35\'/>*"
."<img src=\'".SKIN_PATH."neok.gif\' title=\'".__('Delete')."\' style=\'cursor:pointer;display:table-cell;vertical-align:middle;\' onClick=\'$($(this).parents().get(0)).remove();\'>"
.'</div>';
$frm->addbreak(__('Redirect').
'&nbsp;&nbsp;&nbsp;<img onClick="$(\'#add_redirect\').append(\''.$add_redirect.'\');" title="'.__('Add').'" src="'.SKIN_PATH.'plus.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;"/>'
);
if (!empty($redirect['from_arr'])) {
foreach ($redirect['from_arr'] as $i=>$value)
if (!empty($redirect['from_arr'][$i]))
$frm->addrow(
__(' from ').' '.$frm->text_box('redirect[from_arr][]', @$redirect['from_arr'][$i], 35),
__(' to ').$frm->text_box('redirect[to_arr][]', @$redirect['to_arr'][$i], 35)
.'<img src="'.SKIN_PATH.'neok.gif" style="cursor:pointer;display:table-cell;vertical-align:middle;" onClick="$($(this).parents(\'tr\').get(0)).remove();">'
);
}
$frm->addmessage('<div id="add_redirect"></div>');

//Performance
$frm->addbreak(__('Performance'));
$frm->addrow(__('Disable statistic'), $frm->checkbox('nconfig[disable_stats]', '1', '', @$config['disable_stats']));
$frm->addrow(__('Enable site cache in seconds (0 - disabled)'), $frm->text_box('nconfig[cache]', @$config['cache'],10));
$frm->addrow(__('Clear cache directory'), $frm->checkbox('clear_cache', '1', '', false));
$frm->addrow(__('Disable modules in cache'), $frm->textarea('nconfig[disable_cache]', @$config['disable_cache'], 70, 10), 'top');
//Interaction with user
$frm->addbreak(__('Interaction with user'));
$frm->addrow(__('Email for users letters'), $frm->text_box("nconfig[admin_email]", @$config['admin_email'], 30));
$frm->addrow(__('Disallow guest post to Articles'), $frm->checkbox('nconfig[article-guest]', '1', '', @$config['article-guest']));
$frm->addrow(__('Disallow guest post comments to Guestbook'), $frm->checkbox('nconfig[guestbook-guest]', '1', '', @$config['guestbook-guest']));
$frm->addrow(__('Disallow guest post comments to Gallery'), $frm->checkbox('nconfig[gallery-guest]', '1', '', @$config['gallery-guest']));
$frm->addrow(__('Disallow guest post to Forum'), $frm->checkbox('nconfig[forum-guest]', '1', '', @$config['forum-guest']));
$frm->addrow(__('Disallow user selection of password in registration form'), $frm->checkbox('nconfig[regconf]', '1', '', @$config['regconf']));
$frm->addrow(__('Period when one password request can be acomplished (seconds)'), $frm->text_box('nconfig[pr_flood]', @$config['pr_flood']));
$frm->addrow(__('Access level for registered users'), $frm->text_box('nconfig[registered_accesslevel]', @$config['registered_accesslevel']));
$frm->addrow(__('Try to detect user\'s language'), $frm->checkbox('nconfig[detect_lang]', '1', '', @$config['detect_lang']));
$frm->addrow(__('Default skin'), user_skin_select(SKIN_PATH, 'nconfig[default_skin]', $config['default_skin']));
$frm->addrow(__('Allow users to select skin'), $frm->checkbox('nconfig[allowchskin]', '1', '', @$config['allowchskin']));
$frm->addrow(__('Default language'), user_lang_select('nconfig[default_lang]', $config['default_lang']));
$frm->addrow(__('Allow users to select language'), $frm->checkbox('nconfig[allowchlang]', '1', '', @$config['allowchlang']));
$frm->addrow(__('Default timezone'), user_tz_select((int)@$config['default_tz'], 'nconfig[default_tz]'));
$frm->show();
?>
