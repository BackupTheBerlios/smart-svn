<script language="JavaScript" type="text/JavaScript">
function deleteuser(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deluser.value=1;
        with(f){
        submit();
        }
        }
}
</script>
<form name="deluser" method="post" action="index.php?m=EARCHIVE&mf=edit_mess">
<input name="mid" type="hidden" value="<?php echo $B->tpl_data['mid']; ?>">
<input name="lid" type="hidden" value="<?php echo $B->tpl_data['lid']; ?>">
<input name="pageID" type="hidden" value="<?php echo $_REQUEST['pageID']; ?>">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">
      <table width="400" border="0" cellspacing="3" cellpadding="3">
        <?php if($B->form_error != FALSE): ?>
        <tr>
          <td align="left" valign="top" class="itemerror"><?php echo $B->form_error; ?></td>
        </tr>
        <?php endif; ?>
        <tr>
          <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit
            Message</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="font10bold">From</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="itemnormal"> <?php echo stripslashes($B->tpl_data['sender']); ?> </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="font10bold">Subject</td>
        </tr>
        <tr>
          <td align="left" valign="top">
            <input name="subject" type="text" id="subject" size="85" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['subject'])); ?>">
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="font10bold">Body</td>
        </tr>
        <tr>
          <td align="left" valign="top"><textarea name="body" cols="65" rows="10" wrap="VIRTUAL" id="body"><?php echo stripslashes($B->tpl_data['body']); ?></textarea>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="font9"><strong>Attachments of
              this message:</strong> (delete on check)<br />
            <?php if (count($B->tpl_attach) > 0): ?>
            <?php foreach($B->tpl_attach as $attach): ?>
            <input name="aid[]" type="checkbox" value="<?php echo $attach['aid']; ?>">&nbsp;<?php echo stripslashes($attach['file']); ?>
            <div>Type: <?php echo $attach['type']; ?></div>
            <div>Size: <?php echo $attach['size']; ?></div>
            <br />
            <?php endforeach; ?>
            <?php else: ?>
            <div>No attachments for this message</div>
            <?php endif; ?>
</td>
        </tr>
        <tr>
          <td align="left" valign="top"><input name="editmessage" type="submit" id="editmressage" value="Submit">
          </td>
        </tr>
      </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="index.php?m=EARCHIVE&mf=show_mess&lid=<?php echo $B->tpl_data['lid']; ?>&pageID=<?php echo $_REQUEST['pageID']; ?>">back</a></td>
  </tr>
</table>
</form>