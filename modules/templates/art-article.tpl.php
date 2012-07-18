<table  cellpadding="1" cellspacing="1" class="article-main" style="width:100%">
<?php
global $articles_config;
 if(!empty($tpldata['showtitle'])) {?>
<tr>
<?php if(!empty($tpldata['linktext']) && !empty($tpldata['linkurl'])) {?>
    <td align="center" colspan="3">
        <h4 style="margin:11px;"><a style="color:#2D2BA0;" href="<?=$tpldata['linkurl']?>"><?=$tpldata['title']?></a></h4>
   	</td>
<?php } else {?>
    <td align="center" colspan="3">
        <b><?=$tpldata['title']?></b>
    </td>
<?php }?>
</tr>
<?php }?>
<tr>
<?php 
$rating='';
if (!empty($articles_config['code_rating'])) {
if(!empty($tpldata['linkurl'])) $url='/' . $tpldata['linkurl'];
else $url=$_SERVER['REQUEST_URI'];
$urlcode = crc32(str_replace('&amp;','&',$url));
$RW_UID = html_entity_decode($articles_config['code_rating']); 
// replace number of widjet to unique ID 
$RW_UID = str_replace('rw-urid-1','rw-urid-'.$urlcode,$RW_UID);
$rating='<br/><br/>
'. $RW_UID . '
';
}
$social = (!empty($articles_config['social'])?html_entity_decode($articles_config['social']):'');
?>
	<td colspan="3" style="width: 100%;padding-left:20px;padding-right:7px;">
        <?php echo ((empty($tpldata['text'])) ? $tpldata['desc'] : $tpldata['text'].$rating.$social);?>
		</td>
</tr>

<tr style="font-size:small;">
    <td  align="left" style="white-space: nowrap;padding-left:20px;">
        <?=rcms_format_time('d.m.Y H:i', $tpldata['time'])?>
    </td>
    <td align="left" style="padding-left:20px;">
        <?=__('Author/source')?>: <?=rcms_parse_text($tpldata['src'], false, false, false, false, false)?>
	</td>
	<td align="right"style="padding-right:20px;">
		<?php if(!empty($tpldata['linkurl'])) {?>
   	    <a href="<?=$tpldata['linkurl']?>"><?=$tpldata['linktext']?></a>
		<?php }?>
    </td>
</tr>


<?php if ((LOGGED_IN) && (empty($tpldata['linktext']) && empty($tpldata['linkurl']))) { 
          if (strstr($system->user['admin'], 'ARTICLES-EDITOR') || $system->user['admin'] == '*') {
?> 
	<tr>  
			   <td class="row2">
                <form action="admin.php?show=module&id=articles.post" method="post">
                 <input type="hidden" name="c" value="<?=$tpldata['container']?>" />
                 <input type="hidden" name="b" value="<?=$tpldata['catid']?>" />
                 <input type="hidden" name="save" value="0" />
                 <input class="editbutton" type="submit" value="<?=__('Post article')?>">
                </form>
				</td>
				<td class="row2">
                <form action="admin.php?show=module&id=articles.articles" method="post">
                 <input type="hidden" name="c" value="<?=$tpldata['container']?>" />
                 <input type="hidden" name="b" value="<?=$tpldata['catid']?>" />
                 <input type="hidden" name="save" value="0" />
                 <input class="editbutton" type="submit" value="<?=__('Manage articles')?>">
                </form>
				</td>
				<td class="row2">
                <form action="admin.php?show=module&id=articles.articles" method="post">
                 <input type="hidden" name="c" value="<?=$tpldata['container']?>" />
                 <input type="hidden" name="b" value="<?=$tpldata['catid']?>" />
                 <input type="hidden" name="a" value="<?=$tpldata['id']?>" />
                 <input type="hidden" name="save" value="0" />
                 <input class="editbutton" type="submit" value="<?=__('Edit article')?>">
                </form>
               </td>
    </tr>
    <?php }
      }?>

</table>

