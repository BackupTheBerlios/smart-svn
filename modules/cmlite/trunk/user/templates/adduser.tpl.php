<form name="adduser" method="post" action="index.php?admin=1&m=user&sec=adduser">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">    <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->form_error != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->form_error; ?></td>
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
        <td align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9">
        <input name="status" type="radio" value="1" checked> 
        Inactif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="status" type="radio" value="2"> Actif        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Rights</td>
      </tr>
      <tr>
        <td align="left" valign="top">
        <select name="rights">
          <option value="1" selected>Registered</option>
          <option value="2">Contributor</option>
          <option value="3">Author</option>
          <option value="4">Editor</option>
          <option value="5">Administrator</option>
        </select></td>
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