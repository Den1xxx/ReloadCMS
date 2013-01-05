<?php
//Params
$config = parse_ini_file(CONFIG_PATH . 'search.ini');

//Func check privilegies
function access_chk($acs)
{
global $system, $config;
$can = false;
if ($system->user['accesslevel'] >= $acs) $can = true;
if (isset($config['access'])) $can = true;
return $can;
}
//Func add word, if need, to result array
function to_result($text, $word, $link, &$arr)
{
//echo $text.'='.$word.'='.$link.'<br/>';
if (trim($word) !== '')
if (strpos(' '.strtolower($text), strtolower($word)))
    if (!in_array($link,$arr))
        $arr[$link] = $text;
}

//Func to formating output
function format_finded($text, $word)
{
GLOBAL $config;
$start = 0;
$count = strlen($text);
$start_ = '';
$end_ = '';
$temp = strpos(strtolower($text),strtolower($word));
if ($temp > $config['block'] / 2) {$start = $temp - ($config['block'] / 2); $start_='...'; }
if (strlen($text) - ($config['block'] / 2) > $temp) {$count = $config['block']-1; $end_='...';}
return $start_.str_replace(strtolower($word),'<u><b>'.strtolower($word).'</b></u>',mb_substr($text,$start, $count)).$end_;
}

//Func for search in article
function search_in_article($article, $find)
{
if (!class_exists('articles')) return false;
$out = array();
$articles = new articles();
$articles -> setWorkContainer($article);
$categories = $articles -> getCategories();
if (!empty($categories)) {
	foreach ($categories as $catkey => $katval) {
        if (access_chk($katval['accesslevel'])){
        to_result($katval['description'],$find,'?module=articles&c='.$article.'&b='.$katval['id'],$out);
        to_result($katval['title'],$find,'?module=articles&c='.$article.'&b='.$katval['id'],$out);
		$articls = $articles -> getArticles($katval, true, true, true);
		if (!empty($articls)) {
			foreach ($articls as $akey => $aval) {
                $link = '?module=articles&c='.$article.'&b='.$aval['catid'].'&a='.$aval['id'];
                to_result($aval['text'],$find,$link,$out);
                to_result($aval['desc'],$find,$link,$out);
                to_result($aval['keywords'],$find,$link,$out);
                to_result($aval['sef_desc'],$find,$link,$out);
                to_result($aval['title'],$find,$link,$out);
                to_result($aval['author_nick'],$find,$link,$out);
			}
		}
        }
	}
}
return $out;
}

//Func for search in files
function search_in_files($find)
{
if (!class_exists('linksdb')) return false;
$out = array();
$filesdb = new linksdb(DOWNLOADS_DATAFILE);
if (!empty($filesdb)) {
	foreach ($filesdb as $catkey => $katval) {
        if (is_array($katval))
			foreach ($katval as $akey => $aval) {
                if (access_chk($aval['accesslevel']))
                foreach ($aval as $bkey => $bval) {
                    to_result($aval['name'],$find,'?module=filesdb&id='.($akey+1),$out);
                    to_result($aval['desc'],$find,'?module=filesdb&id='.($akey+1),$out);
                    if ($bkey=='files')
                    foreach ($bval as $ckey => $cval) {
                        to_result($cval['name'],$find,'?module=filesdb&id='.($akey+1).'&fid='.($ckey+1),$out);
                        to_result($cval['desc'],$find,'?module=filesdb&id='.($akey+1).'&fid='.($ckey+1),$out);
                        to_result($cval['author'],$find,'?module=filesdb&id='.($akey+1).'&fid='.($ckey+1),$out);
                    }
                }
            }
	}
}
return $out;
}

//Func for search in forum
function search_in_forum($find)
{
if (!is_file(FORUM_PATH . 'topic_index.dat')) return false;
$out = array();
$topics = @unserialize(@file_get_contents(FORUM_PATH . 'topic_index.dat'));
    if(!is_array($topics)) $topics = array();
    foreach ($topics as $akey => $aval){
        to_result($aval['text'],$find,'?module=forum&action=topic&id='.$akey,$out);
        to_result($aval['title'],$find,'?module=forum&action=topic&id='.$akey,$out);
        to_result($aval['author_nick'],$find,'?module=forum&action=topic&id='.$akey,$out);
        $posts = @unserialize(@file_get_contents(FORUM_PATH . 'topic.' . $akey . '.dat'));
        if(!is_array($posts)) $posts = array();
        foreach ($posts as $bkey => $bval){
            to_result($bval['text'],$find,'?module=forum&action=topic&id='.$akey.'#'.$bkey,$out);
            to_result($bval['author_nick'],$find,'?module=forum&action=topic&id='.$akey.'#'.$bkey,$out);
        }
    }
return $out;
}

//Main logic
$find = '';
if (isset($_GET['search'])) $find = trim(rcms_parse_text($_GET['search'])); else $find='';
if ((strlen($find) >= $config['min']) and (strlen($find) <= $config['max'])) {
    $content = '<table width=100%>';

    //articles
    if (class_exists('articles')&&isset($_GET['articles']))
    {
    $articles = new articles();
    $containers = $articles -> getContainers();
    foreach ($containers as $conkey => $conval) {
        $result = search_in_article($conkey,$find);
        $content .= '<tr class = "row2"><td><b>'.$conval.' ('.count($result).'):</b></td></tr>';
        if (count($result) == 0) {$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<i>'.__('Nothing founded').'</i></td></tr>';}
        else foreach ($result as $link => $text)$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<a href="'.$link.'">'.format_finded($text, $find).'</a></td></tr>';
        $content .= '<tr class = "row3"><td>&nbsp;</td></tr>';
    }
    }
    
    //files
    if (isset($_GET['files'])&&defined('DOWNLOADS_DATAFILE'))
    {
    $result = search_in_files($find);
    $content .= '<tr class = "row2"><td><b>'.__('FilesDB').' ('.count($result).'):</b></td></tr>';
    if (count($result) == 0) {$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<i>'.__('Nothing founded').'</i></td></tr>';}
        else foreach ($result as $link => $text)$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<a href="'.$link.'">'.format_finded($text, $find).'</a></td></tr>';
        $content .= '<tr class = "row3"><td>&nbsp;</td></tr>';
    }
    
    //forum
    if (isset($_GET['forum'])&&defined('FORUM_PATH'))
    {
    $result = search_in_forum($find);
    $content .= '<tr class = "row2"><td><b>'.__('Forum').' ('.count($result).'):</b></td></tr>';
    if (count($result) == 0) {$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<i>'.__('Nothing founded').'</i></td></tr>';}
        else foreach ($result as $link => $text)$content .= '<tr class = "row3"><td>&nbsp;&nbsp;<a href="'.$link.'">'.format_finded($text, $find).'</a></td></tr>';
    $content .= '<tr class = "row3"><td>&nbsp;</td></tr>';
  }

    $content .= '</table>';
} else {$content='<center>'.sprintf(__('Input value must be more then %s and less then %s'),$config['min'], $config['max']).'</center>';}

if (isset($config['guest']) and !LOGGED_IN) $content='<center>'.__('You are not logined!').'</center>'; 
show_window(__('Result of search'), $content);
?>