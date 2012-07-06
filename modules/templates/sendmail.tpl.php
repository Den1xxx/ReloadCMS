<form method="post" action="">
 <input type="hidden" name="new_letter" value="1" />
 <table>
 <? if (!empty($tpldata['comment'])) {?>
  <tr>
   <td style="text-align:left" colspan="2" style="width:96%;padding:2%">&nbsp;&nbsp;&nbsp;<?=$tpldata['comment']?></td>
  </tr>
 <?}?>
  <tr>
   <td style="text-align:left" width="45%"><?=$tpldata['sender_name']?>: </td>
   <td style="text-align:left"><input type="text" name="sender_name" value="<? if (!empty($_POST['sender_name'])) echo $_POST['sender_name'];?>" size="50" maxlength="50"/>
   </td>
  </tr>
  <tr>
   <td style="text-align:left" width="45%"><?=$tpldata['sender_email']?>: </td>
   <td style="text-align:left"><input type="text" name="sender_email" value="<? if (!empty($_POST['sender_email'])) echo $_POST['sender_email'];?>" size="30" maxlength="30"/></td>
  </tr>
  <tr>
   <td style="text-align:left" width="45%"><?=$tpldata['subject']?>: </td>
   <td style="text-align:left"><input type="text" name="subject" value="<? if (!empty($_POST['subject'])) echo $_POST['subject'];?>" size="50" maxlength="50"/>
   </td>
  </tr>
 </table>
 <div style="font-weight: bold; padding: 5px 0;"><?=$tpldata['letter']?></div>
 <textarea name="letter" cols="70" rows="15"><? if (!empty($_POST['letter'])) echo $_POST['letter'];?></textarea>
 <table>
  <tr>
   <td style="text-align:center; width:30%; color: red; font-weight: bold; font-style: italic;"><?=$tpldata['important']?></td>
   <td style="text-align:left" style="padding: 0 80px 0 0;"><?=$tpldata['important_text']?></td>
  </tr>
  <tr><td colspan="2" style="text-align:center"><hr/></td></tr>
  <tr>
   <td width="50%" style="text-align:right">
    <?php $rand=rand(0,777);?>
    <img src="captcha.php?ident=<?=$rand;?>" alt="captcha" />
   </td>
   <td width="50%" style="text-align:left">
    <input type="text" size="5" name="captcheckout" value="" />
    <input type="hidden" name="antispam" value="<?=$rand;?>" />
   </td>
  </tr>
 </table>
 <p align="center"><input type="submit" value="<?=__('Submit')?>"/></p>
</form>