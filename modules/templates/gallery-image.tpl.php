<hr />
<? if(!empty($tpldata['previous'])){ ?>
<a href="?module=<?=$tpldata['module'].$tpldata['linkdata']?>&amp;id=<?=$tpldata['previous']?>">&larr;  <?=__('Back')?></a>&nbsp;&nbsp;
<? }?>
<? if(!empty($tpldata['next'])){ ?>
&nbsp;&nbsp;<a href="?module=<?=$tpldata['module'].$tpldata['linkdata']?>&amp;id=<?=$tpldata['next']?>"><?=__('Forward')?> &rarr; </a>
<? }?>
<hr />
<?=$tpldata['image']?>
