<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$system->config['language']?>">
<head>
<title><?=rcms_show_element('title')?></title>
<link rel="stylesheet" type="text/css" href="<?=CUR_SKIN_PATH?>css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?=CUR_SKIN_PATH?>css/main.css" />
<link rel="stylesheet" type="text/css" href="<?=CUR_SKIN_PATH?>css/forms.css" />
<?=rcms_show_element('meta')?>
<script type="text/javascript" src="./tools/js/tiny_mce/tiny_mce.js"></script>
<!--[if lt IE 7]>
<style>
.png { behavior: url("<?=CUR_SKIN_PATH?>js/iepngfix.htc"); }
</style>
<script type="text/javascript" src="<?=CUR_SKIN_PATH?>js/iepngfix_tilebg.js"></script>
<![endif]--> 

</head>

<body>

<div class="container">

<div class="head">
 <div class="logo">
	<a href="<?=RCMS_ROOT_PATH?>">
	<img src="<?=CUR_SKIN_PATH?>/images/logo.png" alt="<?=rcms_show_element('title')?>" title="<?=rcms_show_element('title')?>">
	<br/>
	<span><?=rcms_show_element('slogan')?></span>
	</a>
 </div> 
</div><!--/head -->
 <div class="clear"></div>

<div class="top-menu-bg">
<div class="top-menu">

    <div class="up-menu">
	<div class="in-menu">
    <div class="center-menu"> 
    <ul>
	<div class="up-menu">
	<div class="in-menu">
    <div class="center-menu"> 
    <ul>
	<?=rcms_show_element('navigation', 
	'<li><a href="{link}" ><b></b><span>{title}</span><i></i></a></li>')?>
    </ul>
	</div>
    </div>
	</div>
    </ul>
	</div>
    </div>
	</div>

</div><!--/top-menu -->
</div>


<div class="wraper">

<div class="center">
	<div class="info-block">    
	<?=rcms_show_element('menu_point', 'up_center@window')?>
	<?=rcms_show_element('main_point', $module.'@window')?>
	<?=rcms_show_element('menu_point', 'down_center@window')?>
	</div><!--info-block-->
</div><!--/center -->

</div><!--/wraper -->


<div class="left">
	<?=rcms_show_element('menu_point', 'left@block')?>
</div><!--/left -->
  
  
<div class="right">
<div class="inr">


	<?=rcms_show_element('menu_point', 'right@block')?>

	
</div><!--/right inr -->
</div><!--/right -->
<div class="footer">
<div class="text redtext"><div class="pbox red"></div>
&copy;<?=$system->config['title']?> | <?php echo(!empty($system->config['copyryght'])?$system->config['copyryght'].' | ':'')?> | 
<?=rcms_show_element('navigation', 	'<a href="{link}" >{title}</a> / ')?>
</div>
</div><!--/footer -->
  
</div><!--/container -->
</body>
</html>