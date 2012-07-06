<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$system->config['language']?>">
<head>
<title><?=rcms_show_element('title')?></title>
<?=rcms_show_element('meta')?>
<link rel="stylesheet" type="text/css" href="<?=CURRENT_SKIN?>forum.css" media="screen, projection, tv" />
		<?php if ($system->config['pagination'] == 'Paginator3000') { ?>
           <script type="text/javascript" src="<?=TOOLS?>paginator3000.js"></script>
           <link rel="stylesheet" type="text/css" href="<?=TOOLS?>paginator3000.css" media="screen" />
 <?php } ?>
 <script type="text/javascript" src="<?=JSLIB?>limit.js"></script>
 <script type="text/javascript" src="<?=JSLIB?>jquery.js"></script>
 <script type="text/javascript" src="<?=JSLIB?>jquery.easing.js"></script>
 <script type="text/javascript" src="<?=TOOLS?>fancy/jquery.fancybox.js"></script>
 <link rel="stylesheet" type="text/css" href="<?=TOOLS?>fancy/fancybox.css" media="screen" />
 <script type="text/javascript">
$(document).ready(function(){
    $("a.single_image").fancybox({
        'centerOnScroll' : true,
		'hideOnContentClick': true,
		'overlayShow' : true,
		'titleShow'   : false,
		'titlePosition'  : 'over',
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
    });
});
 </script>
 <link rel="shortcut icon" href="favicon.ico"/>
</head>
<body>
<div id="site_title_bar_wrapper">
	<div id="site_title_bar">
	    <div id="site_title">
            <h1><a href="<?=htmlspecialchars('http://'.$_SERVER['HTTP_HOST'])?>"><?=rcms_show_element('title')?>
                <span><?php if (!empty($system->config['slogan'])) { ?><?=$system->config['slogan']?><?php } ?></span>
            </a></h1>
        </div>
    </div>
</div>

<div id="menu_wrapper">
	<div id="menu">
	    <ul>
            <li><?=rcms_show_element('navigation', '<a href="{link}" target="{target}">{icon}&nbsp;{title}</a> ')?></li>
        </ul>
    </div>
</div> 

<div id="content_wrapper_outer">
	<div id="content_wrapper_inner">
    	<div id="content_wrapper">
            <div id="content">
            	<div class="content_bottom"></div>
                <div id="main_column">
                    <div class="post_box">
                        <div class="post_body">
								<?=rcms_show_element('main_point', $module.'@window')?>      
						</div> 
                    </div> <!-- end of a post -->
				</div> <!-- end of main column -->        
				
                <div id="side_column">
					<div class="side_column_section">
					</div>        
                </div> <!-- end of side column -->
            	<div class="cleaner"></div>
            </div>
        	<div class="cleaner"></div>
        </div>
        <div class="cleaner"></div>        
    </div>
</div>

<div id="footer_wrapper">
	<div id="footer">
        <div class="section_w200">
        	<h4>Навигация</h4>
        	<ul class="footer_menu_list">
            	<li><a href="?module=index">Главная</a></li>
                <li><a href="?module=articles">Статьи</a></li>
                <li><a href="?module=filesdb">Файлы</a></li>
                <li><a href="?module=gallery">Галерея</a></li>
                <li><a href="?module=forum">Форум</a></li>               
            </ul>
        </div>
        <div class="section_w200">
	        <h4>Partners</h4>
        	<ul class="footer_menu_list">
                <li><a href="#">Partner 1</a></li>
                <li><a href="#">Partner 2</a></li>
                <li><a href="#">Partner 3</a></li> 
            	<li><a href="#">Partner 4</a></li>
                <li><a href="#">Partner 5</a></li>              
            </ul>
        </div>
        <div class="section_w200">
			<h4>Friends</h4>       
        	<ul class="footer_menu_list">
            	<li><a href="#" target="_blank">Friend 1</a></li>
                <li><a href="#" target="_blank">Friend 2</a></li>
                <li><a href="#" target="_blank">Friend 3</a></li>
              	<li><a href="#" target="_blank">Friend 4</a></li>
                <li><a href="#" target="_blank">Friend 5</a></li>               
          </ul>
        </div>
        <div class="section_w260">
        	<h4>Privacy Policy</h4>
        	Вся информация на этом сайте может воспроизводиться повторно без любых ограничений, если источник материалов явно указан как: <?=htmlspecialchars(''.$_SERVER['HTTP_HOST'])?>.<br />
			&nbsp;Copyright&nbsp;&copy;&nbsp;2024&nbsp;<a href="<?=htmlspecialchars('http://'.$_SERVER['HTTP_HOST'])?>"><?=htmlspecialchars(''.$_SERVER['HTTP_HOST'])?></a>
			<div class="cleaner_h10"></div>
        </div>
        <div class="cleaner_h20"></div>
        <div class="section_w860">
        	<?php
			// Page gentime end
			$mtime = explode(' ', microtime());
			$totaltime = $mtime[0] + $mtime[1] - $starttime;
			print(__('Generation time:').round($totaltime,2));
			?><br />Designed by <a href="http://www.templatemo.com" target="_blank" rel="nofolow">Free CSS Templates</a>&nbsp;|
			<?=rcms_show_element('copyright')?>
			<br /><a href="http://validator.w3.org/check?uri=referer" target="_blank" rel="nofolow"><img src="./skins/button-xhtml.png" alt="Valid XHTML 1.0!" /></a>
			<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank" rel="nofolow"><img src="./skins/button-css.png" alt="Valid CSS!" /></a>
			<a href="http://php.net" target="_blank" rel="nofolow"><img src="./skins/button-php.gif" alt="PHP powered" /></a>
			<img src="./skins/button-rss.png" alt="RSS Aggregation" />
        </div>   
    </div> <!-- end of footer -->
</div>
</body>
</html>