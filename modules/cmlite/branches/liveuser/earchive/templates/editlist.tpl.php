<script language="JavaScript" type="text/JavaScript">
function deletelist(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.dellist.value=1;
        with(f){
        submit();
        }
        }
}
</script>
<form name="deluser" method="post" action="index.php?m=EARCHIVE&mf=edit_list">
<input name="lid" type="hidden" value="<?php echo $B->tpl_data['lid']; ?>">
<input name="dellist" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">      <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->form_error != FALSE): ?>
            <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->form_error; ?></td>
      </tr>
            <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit List (email account) </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="name" type="text" id="name" size="80" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['name'])); ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Email Account/Server</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="emailserver" type="text" id="emailserver" size="80" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['emailserver'])); ?>">
        </td>
      </tr>         
      <tr>
        <td align="left" valign="top" class="font10bold">Email to fetch</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="email" type="text" id="email" size="80" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['email'])); ?>">
        </td>
      </tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="description" cols="60" rows="4" wrap="VIRTUAL" id="description"><?php echo htmlspecialchars(stripslashes($B->tpl_data['description'])); ?></textarea></td>
      </tr>         
      <tr>
        <td align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9">
          
    <input name="status" type="radio" value="1" <?php if($B->tpl_data['status']==1) echo 'checked'; ?>>
    Inactif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="status" type="radio" value="2" <?php if($B->tpl_data['status']==2) echo 'checked'; ?>> 
    Actif&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="status" type="radio" value="3" <?php if($B->tpl_data['status']==3) echo 'checked'; ?>> Registered                        
    <div align="right"><input name="delete" type="button" id="delete" value="Delete this email list" onclick="deletelist(this.form, 'Delete this list?');">
                    </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="editlist" type="submit" id="editlist" value="Submit" onclick="subok(this.form.editlist);"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="index.php?m=EARCHIVE">back</a></td>
  </tr>
</table>
</form>