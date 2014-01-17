<table width="100%" border="0">
<tr>
<td valign="top" colspan="2" ><?=$tpldata['inheader']?><h2><?=$tpldata['title']?></h2></td>
</tr>
<tr>
<td valign="top"><?=$tpldata['images']?></td>
<td valign="top" width="100%">
<?=(empty($tpldata['text'])?$tpldata['short']:$tpldata['text'])?>
<?if ($tpldata['shop_face_price']=='2') {?>
<table>
<tr>
<td valign="top">
<h3 class="price"><?=number_format($tpldata['price'], 0, ',', ' ' ).' '.$tpldata['currency']?></h3>
<h3 class="price"
<?=(!empty($tpldata['avail_color'])?' style="color:'.$tpldata['avail_color'].'" ':'')?>
><?=$tpldata['avail']?></h3>
</td>
<td valign="top">
<h3 class="price"><?=$tpldata['curr_txt']?></h3>
<? foreach ($tpldata['currencies'] as $curr_name=>$curr_rate){
if (!empty($curr_rate)){?>
<?=ceil($tpldata['price']/$curr_rate)?> <?=$curr_name?><br/>
<?}?>
<?}?>
</td>
</tr>
</table>
<?}?>
</td>
</tr>
<? if (!empty($tpldata['price'])&&$tpldata['shop_face_price']=='1') {?>
<tr>
<td valign="top">
<h3 class="price"><?=number_format($tpldata['price'], 0, ',', ' ' ).' '.$tpldata['currency']?></h3>
<h3 class="price"
<?=(!empty($tpldata['avail_color'])?' style="color:'.$tpldata['avail_color'].'" ':'')?>
><?=$tpldata['avail']?></h3>
</td>
<td valign="top">
<h3 class="price"><?=$tpldata['curr_txt']?></h3>
<? foreach ($tpldata['currencies'] as $curr_name=>$curr_rate){
if (!empty($curr_rate)){?>
<?=ceil($tpldata['price']/$curr_rate)?> <?=$curr_name?><br/>
<?}?>
<?}?>
</td>
</tr>
<?}?>
<?if (!empty($tpldata['shop'])) {?>
<tr>
<td colspan="2"><?=$tpldata['shop']?>
</td>
</tr>
<? } ?>
</table>
