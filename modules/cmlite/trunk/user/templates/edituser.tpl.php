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
<form name="edituser" method="post" action="index.php?admin=1&m=user&sec=edituser">
<input name="uid" type="hidden" value="<?php echo $B->tpl_data['uid']; ?>">
<input name="modifyuserdata" type="hidden" value="true">
<input name="rights_orig" type="hidden" value="<?php echo $B->tpl_data['rights']; ?>">
<input name="status_orig" type="hidden" value="<?php echo $B->tpl_data['status']; ?>">
<input name="deluser" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">      <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->form_error != FALSE): ?>
            <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->form_error; ?></td>
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
          <input name="_login" type="text" id="_login" size="40" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['login'])); ?>" disabled="disabled">
        </td>
      </tr>      
      <tr>
        <td align="left" valign="top" class="font10bold">Forename</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="forename" type="text" id="forename" size="40" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['forename'])); ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="lastname" type="text" id="lastname" size="40" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['lastname'])); ?>">
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
        <td align="left" valign="top"><input name="email" type="text" id="email" size="40" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($B->tpl_data['email'])); ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9">
    <input name="status" type="radio" value="1" <?php if($B->tpl_data['status']==1) echo 'checked="checked"'; ?>/>
    Inactif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="status" type="radio" value="2" <?php if($B->tpl_data['status']==2) echo 'checked="checked"'; ?>/> 
                    Actif   
                    <div align="right"><input name="delete" type="button" id="delete" value="Delete this user" onclick="deleteuser(this.form, 'Delete this user?');">
                    </div></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Rights</td>
      </tr>
      <tr>
        <td align="left" valign="top">
                <select name="rights">
          <option value="1" <?php if($B->tpl_data['rights']==1) echo 'selected'; ?>>Registered</option>
          <option value="2" <?php if($B->tpl_data['rights']==2) echo 'selected'; ?>>Contributor</option>
          <option value="3" <?php if($B->tpl_data['rights']==3) echo 'selected'; ?>>Author</option>
          <option value="4" <?php if($B->tpl_data['rights']==4) echo 'selected'; ?>>Editor</option>
          <option value="5" <?php if($B->tpl_data['rights']==5) echo 'selected'; ?>>Administrator</option>
        </select></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="edituser" type="submit" id="edituser" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="index.php?admin=1&m=user">back</a></td>
  </tr>
</table>
</form>