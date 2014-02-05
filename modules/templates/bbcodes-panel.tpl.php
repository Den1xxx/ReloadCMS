<?$rand=rand(1,15000);?>
<?$config = parse_ini_file(CONFIG_PATH . 'comments.ini');
 	if (@$config['bbcodes']=='1') $bbcodes=true; else $bbcodes=false;
	if (@$config['links']=='1') $img=true; else $img=false;
 if ($bbcodes OR $img){?>
<? $dta = explode('.', $tpldata['textarea'])?>
<script language="javascript" type="text/javascript">
<!--
// Define the bbCode tags
bbcode = new Array();
bblast = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[img]','[/img]','[url]','[/url]','[hidden]','[/hidden]','[offtop]','[/offtop]');
//imageTag = false;
//-->
<?php if ($system->checkForRight('GENERAL')) {?>
$(document).ready(function() {
			var button = $('#uploadButton<?=$rand?>'), interval;
			$.ajax_upload(button, {
						action : '<?=RCMS_ROOT_PATH?>picloader.php',
						name : 'uploadfile',
						onSubmit : function(file, ext) {
							$("img#load<?=$rand?>").attr("src", "<?=RCMS_ROOT_PATH?>tools/js/images/load.gif");
							$("#uploadButton<?=$rand?> span").text('<?=__('Loading')?>...');
							this.disable();
						},
						onComplete : function(file, response) {
							$("img#load<?=$rand?>").attr("src", "<?=RCMS_ROOT_PATH?>tools/js/images/blank.gif");
							$("#uploadButton<?=$rand?> span").text('<?=__('Upload image')?>');
							this.enable();
if (response.substr(0,5)=='[img]') {//success
document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'].value += response;
$("#answer<?=$rand?>").text('<?=__('Uploaded')?> ' + file);
} else {
$("#answer<?=$rand?>").text(response);}
						}
					});
		});

<?php }?>
</script>
<table cellspacing="0" cellpadding="0" border="0" align="center" class="bb_editor">
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
        <input type="button" accesskey="w" name="addbbcode12" value="<?=__('URL')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 12, '<?=$tpldata['textarea']?>')" />
        <input type="button" accesskey="r" name="addbbcode16" value="<?=__('Offtop')?>" onclick="bbstyle(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], 16, '<?=$tpldata['textarea']?>')" />
		<?}?>
<input type="button" accesskey="Q" name="addbbcode17" value="<?=__('Clear')?>" onclick="document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'].value='';" />
<?php if ($system->checkForRight('GENERAL')) {?>
<button id="uploadButton<?=$rand?>" onclick="return false;">
<span><?=__('Upload image')?></span>
<img id="load<?=$rand?>" src="<?=RCMS_ROOT_PATH?>tools/js/images/blank.gif"/>
</button>
<span id="answer<?=$rand?>"></span>
<?} ?>
    </td>
</tr>
<tr align="center" valign="middle">
<td>
<? show_smiles($dta); ?> 
</td>
</tr>
</table>

<div class="popUpBox"><?=__('Quote')?></div>
<!-- Begin JavaScript -->
<script type="text/javascript">
jQuery(function($) {
 
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
            $('.popUpBox').css({'display':'block', 'left':e.pageX-70+'px', 'top':e.pageY+5+'px'});
        }
    });
     
    $(document).bind("mousedown", function(){
        $('.popUpBox').css({'display':'none'});
    });
     
    $('.popUpBox').bind("mousedown", function(){
			insert_text(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'], '[quote]' + $txt + '[/quote]');
    });
     
});
</script>

<?} ?>

