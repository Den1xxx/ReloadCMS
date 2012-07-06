<table border="0" cellpadding="1" cellspacing="1" style="width: 100%; text-align: left " >
<tr><td colspan="2"><?=$tpldata['text']?></td></tr>
<tr><td><?=rcms_format_time('d/m/Y H:i',$tpldata['date'])?></td><td><?=__('Posted by') . ': ' . user_create_link($tpldata['author_name'],$tpldata['author_nick'],'_blank')?></td></tr>
</table>
<br />
