<form name="adduser" method="post" action="index.php?admin=1&m=user&sec=adduser">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">    <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add User </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Forename</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="forename" type="text" id="forename" size="40" maxlength="255" value="<?php echo $B->form_forename; ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="lastname" type="text" id="lastname" size="40" maxlength="255" value="<?php echo $B->form_lastname; ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="login" type="text" id="login" size="40" maxlength="255" value="<?php echo $B->form_login; ?>"></td>
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
        <td align="left" valign="top"><input name="email" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $B->form_email; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="adduser" type="submit" id="adduser" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="index.php?admin=1&m=user">back</a></td>
  </tr>
</table>
</form>