<?php
/////////////////////////////////////////API///////////////////////////////////////////////
 rcms_loadAdminLib('sitemap');
 $directory = 'http://'.$_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'] . basename($_SERVER['SCRIPT_NAME']));
 $priority	= array(0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1);
 $changefreq	= array('always','hourly','daily','weekly','montly','yearly','never');
	if (!empty ($_POST['names']) && is_array($_POST['names'])){
	$names = $_POST['names'];
	$i=0;
		foreach ($names as $key=>$name){
				$config [$name]['changefreq'] = $changefreq[$_POST['changefreq'][$i]];
				$config [$name]['priority'] = $priority[$_POST['priority'][$i]];
		 $i++;
		}
	write_ini_file($config, CONFIG_PATH . 'sitemap.ini',true) ;
	}
	$config = @parse_ini_file(CONFIG_PATH . 'sitemap.ini',true);
	
/////////////////////////////////////Initialization/////////////////////////////////////////
if(!empty($_POST['create']) && !empty($_POST['filename'])){

 $frm = new InputForm ('', 'post', '&lt;&lt;&lt; ' . __('Back')); $frm->show();
 $sitemap = new SitemapGenerator($directory);
    $time = explode(" ",microtime());
    $time = $time[1];
		if(isset($_POST['sitemap_dat'])) 
		file_write_contents(DF_PATH . 'sitemap.dat', $_POST['sitemap_dat']);//save new urls

	if (!empty ($_POST['names']) && is_array($_POST['names']))	$modules = $_POST['names'];
	else { rcms_showAdminMessage ('Sitemap'.__(' is empty')); return;}
	
////////////////////////////////////////Start build//////////////////////////////////////
	$i = 0;
	foreach ($modules as $module){
		if (($module != 'module') AND ($module != 'sitemap')) {
			$chfr = $changefreq[$_POST['changefreq'][$i]];
			$prio = $priority[$_POST['priority'][$i]];
			include_once ($module.'.php');
		}
	$i++;
	}
 
 $sitemap->sitemapFileName = $_POST['filename']; 
 $result = $sitemap->sitemapFileName.'&nbsp;'.__('added successfully').'<br/>';
  
  if (!empty($_POST['gz'])) {
  $sitemap->createGZipFile = true;
  $result = $_POST['filename'].'.gz&nbsp;'.__('added successfully').'<br/>';
 }
    $sitemap->createSitemap();								// create sitemap in memory
	$sitemap->writeSitemap();								// write sitemap as file
	
 if(isset($_POST['robots_txt'])) 
 file_write_contents(RCMS_ROOT_PATH . 'robots.txt', $_POST['robots_txt']);//save robots.txt
 
 if (!empty($_POST['robots'])) {                           //build new robots.txt
 rcms_delete_files(RCMS_ROOT_PATH . 'robots.txt');
 $sitemap->updateRobots();
 $result .= 'robots.txt&nbsp;'.__('added successfully').'<br/>';
 }
  if (!empty($_POST['engines'])) {   						// submit sitemaps to search engines
    $result .= "<pre>";
	$result .= $sitemap->submitSitemap('');
    $result .= "</pre>";
	}
    $result .= __('Memory peak usage: ').number_format(memory_get_peak_usage()/(1024*1024),2)."MB";
    $time2 = explode(" ",microtime());
    $time2 = $time2[1];
    $result .= '<br/>'.__('Execution time: ').number_format($time2-$time)."s<br/>";
	$result .= __('Done').'<br/>';
	$result .= __('Look at').'&nbsp;<a href="'.$_POST['filename'].'" target="_blank"> sitemap.xml  </a> &nbsp;<br/>';
	rcms_showAdminMessage ($result);
	} else {
 $frm = new InputForm ('', 'post', __('Submit'), '', '', '', 'mainfrm');
 $frm->addbreak(__('Information').' sitemap');
 if (is_file(RCMS_ROOT_PATH . 'sitemap.xml'))
$frm->addrow (__('Look at').'&nbsp;<a href="sitemap.xml" target="_blank"> sitemap.xml  </a> ');
$frm->addrow (__('Sitemap will be created for all news (except hidden).').'<br/><a href="http://www.google.com/support/webmasters/bin/answer.py?answer=40318&topic=13450">'.__('Read more about Google Sitemap').'</a>.<br/><a href="http://www.google.com/webmasters/">'.__('Register here').'</a>.<br/>');
  $frm->addbreak(__('Configuration').' robots.txt');
 $frm->hidden('create', '1');
 $frm->addrow(__('Content of').' robots.txt',$frm->textarea('robots_txt', @file_get_contents(RCMS_ROOT_PATH. 'robots.txt'), 80, 10), 'top');
 $frm->addrow(__('Build new').' robots.txt. ', $frm->checkbox('robots', 'true', __('Check if you build robots.txt first time'), false));
  $frm->addbreak(__('Configuration'). ' sitemap');
 $frm->addrow(__('Content of').' sitemap.dat<br/><br/>'.__('Add new urls to').' sitemap.xml:<br/><br/>('.__('Example').')<br/> index.htm <br/> default.htm<br/> uploads/price.zip<br/>',$frm->textarea('sitemap_dat', @file_get_contents(DF_PATH. 'sitemap.dat'), 80, 10), 'top');

 $sitemap = new SitemapGenerator($directory);
//Include all sitemap modules in current directory, except 'module.php'&'sitemap.php'
$modules = rcms_scandir (dirname(__FILE__));
	foreach ($modules as $file){
		if ((basename($file) != 'module.php') AND (basename($file) != 'sitemap.php')) {
		$module = str_replace('.php','',$file);
			if (isset($config[$module]))	{
			$prio = $config[$module]['priority']; 
			$chfr = $config[$module]['changefreq'];
			} else {
			$prio = 0.5;
			$chfr = 'weekly';
			}
	include_once ($file);
 $frm->addrow($name_module,
 $frm->checkbox('names[]', $module, __('Add'), @$config[$module]).'&nbsp;&nbsp;&nbsp;'.
 $frm->select_tag('priority[]', $priority, array_search($prio, $priority)).__('Priority').'&nbsp;&nbsp;&nbsp;'.
 $frm->select_tag('changefreq[]', $changefreq, array_search($chfr, $changefreq)).__('Changefreq')); 
			}
	}
 $frm->addrow(__('Pack file to').'&nbsp;sitemap.xml.gz',$frm->checkbox('gz', 'true', __('Build new'), true));
 $frm->addrow(__('Send to search engines').'&nbsp;sitemap.xml.gz',$frm->checkbox('engines', 'true', __('Enable'), true));
 $frm->addrow(__('Filename'), $frm->text_box('filename', 'sitemap.xml'));
 $frm->show(); 
};
?>