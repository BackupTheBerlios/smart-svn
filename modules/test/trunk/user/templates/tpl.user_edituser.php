<script language="JavaScript" type="text/JavaScript">
function deleteuser(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deluser.value="1";
        with(f){
        submit();
        }
        }
}
</script>
<form name="edituser" method="post" action="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=user&sec=edituser">
<input name="user" type="hidden" value="<?php echo $_REQUEST['user']; ?>">
<input name="modifyuserdata" type="hidden" value="true">
<input name="deluser" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">      <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit User </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="_login" type="text" id="_login" size="40" maxlength="255" value="<?php echo $B->tpl_data['login']; ?>" disabled="disabled">
        </td>
      </tr>      
      <tr>
        <td align="left" valign="top" class="font10bold">Password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="passwd" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $B->form_passwd; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Email</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="email" type="text" id="email" size="40" maxlength="255" value="<?php echo $B->tpl_data['email']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this user" onclick="deleteuser(this.form, 'Delete this user?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="edituser" type="submit" id="edituser" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=user">back</a></td>
  </tr>
</table>
</form>