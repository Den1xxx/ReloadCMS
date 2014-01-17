<table style="text-align: left; width: 100%;"  cellpadding="2" cellspacing="2" class="grayborder">
    <tbody>
    <tr>
      <td class="winheader">
    <h3><?=$tpldata['title']?></h3>
      </td>
    </tr>
    <tr>
      <td>
      <?=$tpldata['short']?>
      </td>
    </tr>
    <tr>
      <td>
     <?=$tpldata['date']?> <?=$tpldata['views']?> <?=$tpldata['printable']?>&nbsp;&nbsp;&nbsp;<a href="?module=fn&a=<?=$tpldata['id']?>"><?=__('Read more...')?></a>
     </td>
    </tr>
  </tbody>
</table>