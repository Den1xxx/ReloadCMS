<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title><? rcms_show_element('title'); ?></title>
<? rcms_show_element('meta'); ?>
<link rel="stylesheet" href="<?=CUR_SKIN_PATH?>style.css.php" type="text/css" />
<?php  if($system->checkForRight('GENERAL')) { ?>
<script type="text/javascript" src="./tools/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
//<![CDATA[
    tinyMCE.init({
        mode : "exact",
        elements : "tmce1,tmce2",
        theme : "advanced",
        language : "ru",
        plugins : "paste,table,cyberim",
        theme_advanced_buttons2_add : "pastetext,pasteword,selectall",
        theme_advanced_buttons3_add : "tablecontrols",
        theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
        paste_auto_cleanup_on_paste : true,
        content_css: "/css/tinymce.css",
        extended_valid_elements : "script[type|language|src]"
        });
//]]>
</script>
<?php } ?>
<SCRIPT type="text/javascript">
//<![CDATA[
$(function() {
	var url=document.location.href;
	$.each($("#menu a"),function(){
	if(this.href==url){$(this).addClass('active');};
	});
    });
//]]>
</SCRIPT>
</head>
<body>
<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h1><a name="top" href="<?=RCMS_ROOT_PATH?>" ><?=$system->config['title'];?></a></h1>
            <p> <?=$system->config['slogan'];?></p>
        </div>
    </div>
    <!-- end #header -->
    <div id="menu" class="navigation">
        <ul>
            <?rcms_show_element('navigation', '<li><a href="{link}" target="{target}" >{title}</a></li>')?>
			<li id="last"><span></span></li>
        </ul>
    </div>
    <!-- end #menu -->
<a id="btn-scroll-top" class="btn-scroll-top" href="#top"></a>
    <div id="page">
        <div id="page-bgtop">
            <div id="page-bgbtm">
                <div id="content">
                <?=rcms_show_element('menu_point', 'up_center@window')?>
                <?=rcms_show_element('main_point', $module.'@window')?>
                <?=rcms_show_element('menu_point', 'down_center@window')?>
                    <div style="clear: both;">&nbsp;</div>
                </div>
                <!-- end #content -->
                <div id="sidebar">
                    <?rcms_show_element('menu_point', 'left@window')?>
                    <?rcms_show_element('menu_point', 'right@window')?>
                </div>
                <!-- end #sidebar -->
                <div style="clear: both;">&nbsp;</div>
            </div>
        </div>
    </div>
    <!-- end #page -->
</div>
<div id="footer">
    <p>Copyright &copy; 2012 <a href="<?=RCMS_ROOT_PATH?>"><?=$system->config['copyright'];?></a> | Developed by <a href="http://fromgomel.com">Den1xxx</a> </p>
</div>
<!-- end #footer -->
<SCRIPT type="text/javascript">
//<![CDATA[
$(function(){
	
	var menu = $('#menu'),
	pos = menu.offset();
		
		$(window).scroll(function(){
			if($(this).scrollTop() > pos.top+menu.height() && menu.hasClass('navigation')){
				menu.fadeOut('fast', function(){
					$(this).removeClass('navigation').addClass('fixed').fadeIn('fast');
					$('a.btn-scroll-top').removeClass('fixed').addClass('show').fadeIn('fast');
				});
			} else if($(this).scrollTop() <= pos.top && menu.hasClass('fixed')){
				menu.fadeOut('fast', function(){
					$(this).removeClass('fixed').addClass('navigation').fadeIn('fast');
					$('a.btn-scroll-top').removeClass('show').addClass('fixed').fadeOut('fast');
				});
			}
		});

});
//]]>
</SCRIPT>
</body>
</html>