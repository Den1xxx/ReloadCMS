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
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[img]','[/img]','[url]','[/url]','[hidden]','[/hidden]','[offtop]','[/offtop]');
//imageTag = false;
//-->
<?php if ($system->checkForRight('GENERAL')) {?>
$(document).ready(function() {
			var button = $('#uploadButton<?=$rand?>'), interval;
			$.ajax_upload(button, {
						action : 'picloader.php',
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
if (response.substr(0,5)=='[img]') {//sucess
insert_text(document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'],  response);
$("#answer<?=$rand?>").text('<?=__('Uploaded')?> ' + file);
} else {
$("#answer<?=$rand?>").text(response);}
						}
					});
		});

<?php }?>
</script>
<table cellspacing="0" class="bb_editor" cellpadding="0" border="0" align="center">
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
        <?}?>
<input type="button" accesskey="Q" name="addbbcode17" value="<?=__('Clear')?>" onclick="document.forms['<?=$dta[0]?>'].elements['<?=$dta[1]?>'].value='';" />
<?php if ($system->checkForRight('GENERAL')) {?>
<button id="uploadButton<?=$rand?>" >
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
<?} ?>

