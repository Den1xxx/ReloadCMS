<?php global $module ?>
<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;" class="grayborder">
<tr>
<?php if ((LOGGED_IN) && (empty($tpldata['linktext']) && empty($tpldata['linkurl']))) { 
          if (strstr($system->user['admin'], 'ARTICLES-EDITOR') || $system->user['admin'] == '*') {
?> 
    <th align="center" >
        <a href="<?=$tpldata['link']?>"><?=$tpldata["title"]?></a>
    </th>    
	<th align="center" >
		<form action="admin.php?show=module&id=articles.post" method="post">
        <input type="hidden" name="c" value="<?=$_GET['c']?>" />
        <input type="hidden" name="b" value="<?=$tpldata['id']?>" />
        <input type="hidden" name="save" value="0" />
        <input class="editbutton" type="submit" value="<?=__('Post article')?>">
        </form>
    </th>
    <?php }
      } else {?>
    <th align="center" colspan="2">
        <a href="<?=$tpldata['link']?>"><?=$tpldata["title"]?></a>
    </th>    
     <? }?>
</tr>

<tr>
    <td valign="top" class="row2" width="75%">
        <?=@$tpldata['description']?> <br />
		<?php if($tpldata['articles_clv'] != '0' && !empty($tpldata['last_article'])){?>
		<small><br/>
		<?=__('Last article')?>: 
		<a href="<?=$tpldata['link'] . '&amp;a=' . $tpldata['last_article']['id']?>"><?=$tpldata['last_article']['title']?></a>
		&nbsp;-&nbsp;<?=rcms_format_time('d F Y', $tpldata['last_article']['time'])?>
		</small>
		<?php }?>
    </td>
    <td class="row3" width="25%" >
&nbsp;&nbsp;&nbsp;<?=__('Articles count')?>: <a href="<?=$tpldata['link']?>"><?=$tpldata['articles_clv']?></a>
    </td>
</tr>

</table>