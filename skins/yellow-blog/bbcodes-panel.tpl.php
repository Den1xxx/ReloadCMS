<?$rand=rand(1,15000);?>
<?$config = parse_ini_file(CONFIG_PATH . 'comments.ini');
 	if (@$config['bbcodes']=='1') $bbcodes=true; else $bbcodes=false;
	if (@$config['links']=='1') $img=true; else $img=false;
 if ($bbcodes OR $img){?>
<?$dta = explode('.', $tpldata['textarea'])?>
<script type="text/javascript">
<!--
// Define the bbCode tags
bbcode = new Array();
bblast = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[img]','[/img]','[url]','[/url]','[hidden]','[/hidden]','[spoiler]','[/spoiler]');
//imageTag = false;
//-->
<?php if ($system->checkForRight('GENERAL')) {?>
function createIFrame<?=$rand;?>() {
  var id = 'f<?=$rand;?>';
  var div = document.createElement('div');
  div.innerHTML = '<iframe style="display:none" id="'+id+'" name="'+id+'" onload="sendComplete<?=$rand;?>(\''+id+'\')"></iframe>';
  document.body.appendChild(div);
  return document.getElementById(id);
}

function clearInput<?=$rand;?>() {
clrInput = document.getElementById('uploadFile');
clrInput.setAttribute(id,"");
clrInput.setAttribute(name,"");
clrInput.setAttribute(value,"");
}
function createForm<?=$rand;?>(id) {
  var div = document.createElement('div');
  div.innerHTML = '  <form id="'+id+'" name="'+id+'" method="post" enctype="multipart/form-data" onsubmit="sendForm<?=$rand;?>(this, \'<?=RCMS_ROOT_PATH.'picloader.php'?>\', uploadComplete<?=$rand;?>, \'uploadFile\');clearInput<?=$rand;?>"></form>';
  document.body.appendChild(div);
}


function sendForm<?=$rand;?>(form, url, func, arg) {
  if (!document.createElement) return; // not supported
  if (typeof(form)=="string") form=document.getElementById(form);
  var frame=document.getElementById('f<?=$rand;?>');
  frame.onSendComplete<?=$rand;?> = function() { func(arg, getIFrameXML<?=$rand;?>(frame)); };
  form.setAttribute('target', frame.id);
  form.setAttribute('action', url);
  form.submit();
}

function sendComplete<?=$rand;?>(id) {
  var iframe=document.getElementById(id);
  if (iframe.onSendComplete<?=$rand;?> && typeof(iframe.onSendComplete<?=$rand;?>) == 'function') iframe.onSendComplete<?=$rand;?>();
}

function getIFrameXML<?=$rand;?>(iframe) {
  var doc=iframe.contentDocument;
  if (!doc && iframe.contentWindow) doc=iframe.contentWindow.document;
  if (!doc) doc=window.frames[iframe.id].document;
  if (!doc) return null;
  if (doc.location=="about:blank") return null;
  if (doc.XMLDocument) doc=doc.XMLDocument;
  return doc;
}

function uploadComplete<?=$rand;?>(element, doc) {
  if (!doc) return;
  if (typeof(element)=="string") element=document.getElementById(element);
insert_text(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], "\n" + doc.documentElement.firstChild.nodeValue+"\n");
element.value='';
}
//Initialization:)
createForm<?=$rand;?>('<?=$rand;?>');
<?php }?>
</script>
<script type="text/javascript" src="<?=RCMS_ROOT_PATH?>tools/js/editor.js"></script>
<table cellspacing="0" cellpadding="0" border="0" align="center">
<tr align="center" valign="middle">
    <td>
        <input type="button" accesskey="b" name="addbbcode0" value="B" style="font-weight:bold;" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 0, '<?=$tpldata['textarea']?>')"/>
        <input type="button" accesskey="i" name="addbbcode2" value="i" style="font-style:italic;" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 2, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="u" name="addbbcode4" value="u" style="text-decoration: underline;" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 4, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="q" name="addbbcode6" value="<?=__('Quote')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 6, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="c" name="addbbcode8" value="<?=__('Code')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 8, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="h" name="addbbcode14" value="<?=__('Hidden')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 14, '<?=$tpldata['textarea']?>')" />
		<?	if ($img) {//enable all bbcodes ?>
        <input type="button" accesskey="p" name="addbbcode10" value="<?=__('Image')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 10, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="w" name="addbbcode12" value="URL" style="text-decoration: underline;" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 12, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="r" name="addbbcode16" value="<?=__('Spoiler')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 16, '<?=$tpldata['textarea']?>')" />
		<?}?>
<input type="button" accesskey="Q" name="addbbcode17" value="<?=__('Clear')?>" onclick="document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'].value='';" />
    </td>
</tr>
<tr align="center" valign="middle">
<td>
<? show_smiles($dta); ?> 
</td>
</tr>
<?php if ($system->checkForRight('GENERAL')) {?>
<tr align="center" valign="middle">
<td>
<?=__('Select images to upload')?>: 
<iframe style="display:none" id="f<?=$rand;?>" name="f<?=$rand;?>" onload="sendComplete<?=$rand;?>('f<?=$rand;?>')"></iframe>
<input type="file"   form="<?=$rand;?>" onFocus ='this.id="uploadFile";this.name="uploadFile"' />
<input type="submit" form="<?=$rand;?>" value="<?=__('Upload')?>" />
</td>
</tr>
<?} ?>
</table>

<div class="popUpBox"><?=__('Quote')?></div>
<!-- Begin JavaScript -->
<script type="text/javascript">
$(function($) {
    var $txt = '';
    $('.window-main').bind("mouseup", function(e){
        if (window.getSelection){
            $txt = window.getSelection();
        }
        else if (document.getSelection){
            $txt = document.getSelection();
        }
        else if (document.selection){
            $txt = document.selection.createRange().text;
        }
        else return;
        if    ($txt!=''){
            $('.popUpBox').css({'display':'block', 'left':e.pageX-70+'px', 'top':e.pageY-170+'px'});
        }
    });
     
    $(document).bind("mousedown", function(){
        $('.popUpBox').css({'display':'none'});
    });
     
    $('.popUpBox').bind("mousedown", function(){
			insert_text(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], '[quote]' + $txt + '[/quote]')
    });
     
});
</script>












<?} ?>

