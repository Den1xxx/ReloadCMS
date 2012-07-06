<?php
$config = parse_ini_file(CONFIG_PATH . 'search.ini');
$init='';
$chose='';
if (isset($_GET['search'])) $init = trim(rcms_parse_text($_GET['search']));
if (isset($config['chose']))
{
	$articles = ' checked ';
	$files = ' checked ';
	$forum = ' checked ';
	if (isset($_GET['module'])) {
		if ($_GET['module'] == 'search.result') {
			if (!isset($_GET['articles'])) $articles = '';
			if (!isset($_GET['forum'])) $forum = '';
			if (!isset($_GET['files'])) $files = '';
		}
	}
	$chose = '<tr valign = "middle"><td align="left" valign = "middle"><input type="checkbox" name="articles" '.$articles.'>'.__('News').', '.__('Articles').'</td></tr>'
		.'<tr><td align="left"><input type="checkbox" name="forum" '.$forum.' />'.__('Forum').'</td></tr>'
		.'<tr><td align="left"><input type="checkbox" name="files" '.$files.' />'.__('FilesDB').'</td></tr>';
}
show_window(__('Search'), '<center><table><tr><td align="left"><form method="get" action="">
    <input type="hidden" name="module" value="search.result" />
    <input type="text" name="search" size='.$config['width'].' value="'.$init.'" />&nbsp;
	<input type="submit" value="'.__('Find').'" /></td></tr>'.$chose.'</form></table></center>');
?>