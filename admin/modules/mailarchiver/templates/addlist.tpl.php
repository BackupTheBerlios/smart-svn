<form name="form1" method="post" action="index.php?m=MAILARCHIVER&mf=add_list">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">		<table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->form_error != FALSE): ?>
			<tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->form_error; ?></td>
      </tr>
			<?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add List (email account to archiv) </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="name" type="text" id="name" size="40" maxlength="255" value="<?php echo $B->form_name; ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Email Server</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="emailserver" type="text" id="emailserver" size="40" maxlength="255" value="<?php echo $B->form_emailserver; ?>">
        </td>
      </tr>			
      <tr>
        <td align="left" valign="top" class="font10bold">Email to fetch</td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <input name="email" type="text" id="email" size="40" maxlength="255" value="<?php echo $B->form_email; ?>">
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Email account user</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="emailuser" type="text" id="emailuser" size="40" maxlength="255" value="<?php echo $B->form_emailuser; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Email account password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="emailpasswd" type="text" id="emailpasswd" size="40" maxlength="255" value="<?php echo $B->form_emailpasswd; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="description" cols="40" rows="4" wrap="VIRTUAL" id="description"><?php echo $B->form_description; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9">
				<input name="status" type="radio" value="1" checked> 
				Inactif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="status" type="radio" value="2"> Actif			
					 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="status" type="radio" value="3"> Registered</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="addlist" type="submit" id="addlist" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="index.php?m=MAILARCHIVER">back</a></td>
  </tr>
</table>
</form>