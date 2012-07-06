<table border="0" cellpadding="1" cellspacing="1" width="100%" class="grayborder">
<tr style="height:10px">
    <td class="row2" rowspan="2" valign="top" align="center">
	<a href="javascript:insert_text(document.forms['form1'].elements['comtext'], ' [b]<?=str_replace('\'', '\\\'', $tpldata['author_nick'])?>[/b]')"><?=$tpldata['author_nick']?></a><br/>
	<?=show_avatar($tpldata['author_user'])?>
	</td>
    <td class="row2" align="left" width="100%">
        <?=rcms_format_time('H:i:s d\&\n\b\s\p\;F\&\n\b\s\p\;Y', $tpldata['time'], $system->user['tz'])?>
        <?php if ((isset($tpldata['author_ip'])) AND ($system->checkForRight('ARTICLES-MODERATOR'))) { ?>IP: <?=$tpldata['author_ip']?>   <?php }?>
    </td>
    <td class="row2" align="right" nowrap="nowrap">
        <?php if($system->checkForRight('ARTICLES-MODERATOR')){?>
			<form method="post" action="">
				<input type="hidden" name="cdelete" value="<?=$tpldata['id']?>" />
				<input type="submit" name="" value="X" />
			</form>
        <?php }?>
    </td>
</tr>
<tr>
<td class="row3" style="height: 60px;" valign="top" colspan="2">
<?=$tpldata['text']?></td>
</tr>
</table>