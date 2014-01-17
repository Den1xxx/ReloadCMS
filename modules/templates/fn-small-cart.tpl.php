<?
if (!empty($tpldata)) {
$totalprice=0;
$config=fn_catconfig();
?>
<table width="100%" border="0">
<?    
    foreach ($tpldata as $io=>$eachitem) {
        $itemdata=fn_cat_get_itemdata($eachitem['itemid']);
?>
    <tr class="row3">
		<td><a href="?module=fncatalogue&showitem='.$itemdata['id'].'"><?=$itemdata['name']?></a></td>
        <td><?=$eachitem['count']?> x <?=empty($itemdata['price'])?'0':$itemdata['price']?></td>
        <td>
			<a href="?module=fncart&deletecartitem=<?=$eachitem['itemid']?>">
			<img border="0" title="<?=__('Delete')?>" src="skins/fastnews/trash_small.gif">
			</a>
		</td>
    </tr>
<? 
$totalprice=$totalprice+($itemdata['price']*$eachitem['count']);
    }
?>   
</table>
<?=__('Total').' '.$totalprice.' '.$config['currency'].'<br/>
<a href="?module=fncart">'.__('Show').'</a> <a href="?module=fncart&flushcart=true"  onClick="if(confirm(\''. __('Clear').'?\n\')) document.location.href = \'?module=fncart&flushcart=true\';return false;">'.__('Clear').'</a>';
} else {?>
<h3><?=__('Your shopping cart is empty')?></h3>
<?}?>