<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$system->config['language']?>">
<head>
<title><?=rcms_show_element('title')?></title>
<?=rcms_show_element('meta')?>
<link rel="stylesheet" type="text/css" href="<?=CUR_SKIN_PATH?>style.css" />
		<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
<div id="site_title_bar_wrapper">
	<div id="site_title_bar">
	    <div id="site_title">
            <h1><a href="<?=RCMS_ROOT_PATH?>"><?=rcms_show_element('title')?></a></h1>
			<?=@rcms_show_element('slogan')?><?=rcms_show_element('menu_point', 'Up@header')?>
        </div>
    </div>
</div>

<div id="menu_wrapper">
	<div id="menu">
	    <ul>
            <li><?=rcms_show_element('navigation', '<a href="{link}" target="{target}">{title}</a> ')?></li>
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
								<?=rcms_show_element('menu_point', 'up_center@window')?>
								<?=rcms_show_element('main_point', $module.'@window')?>
								<?=rcms_show_element('menu_point', 'down_center@window')?>      
						</div> 
                    </div> <!-- end of a post -->
				</div> <!-- end of main column -->        
				
                <div id="side_column">
					<div class="side_column_section">
					<?=rcms_show_element('menu_point', 'left@win')?>
					<?=rcms_show_element('menu_point', 'right@win')?>
                   	<?=rcms_show_element('menu_point', $module.'@win')?>
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
        <div class="section_w600">
        	<h4><?php print(__('Navigation'))?></h4>
        	<div class="footer_menu_list">
            	<? echo rcms_parse_menu('&nbsp;<a href="{link}" target="{target}" >{title}</a> &nbsp;/&nbsp;');?>
            </div>
        </div>
        <div class="cleaner_h20"></div>
        <div class="section_w860">
        	<?php
			// Page gentime end
			$mtime = explode(' ', microtime());
			$totaltime = $mtime[0] + $mtime[1] - $starttime;
			print(__('Generation time:').round($totaltime,2));
			?><br />Designed by <a href="http://www.templatemo.com" >Free CSS Templates</a>&nbsp;|
			<?=rcms_show_element('copyright')?>
			</div>   
    </div> <!-- end of footer -->
</div>
</body>
</html>