<div style="width:<?=($tpldata['width']+6);?>px;margin:3px;float:left;">
<h4><a href="<?=$tpldata['link'];?>"><?=$tpldata['name'];?></a></h4>
<p><a href="<?=$tpldata['link'];?>" style="<?=(empty($tpldata['height'])?'':'display:block; height:'.$tpldata['height'].'px; ');?>overflow:hidden;"  ><?=$tpldata['img'];?></a></p>
<? if (!empty($tpldata['description'])) {?><p><?=$tpldata['description'];?></p><?}?>
</div>