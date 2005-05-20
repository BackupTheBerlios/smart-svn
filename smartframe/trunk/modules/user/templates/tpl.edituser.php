<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="./modules/common/media/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
  // Notice: The simple theme does not use all options some of them are limited to the advanced theme
  tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    theme_advanced_toolbar_location : "top",
    plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu"
    
  });
</script>
<!-- /tinyMCE -->
<script language="JavaScript" type="text/JavaScript">
function deluser(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deleteuser.value="1";
        with(f){
        submit();
        }
        }
}
</script>
<form name="edituser" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=user&view=edituser">
<input name="id_user" type="hidden" id="id_user" value="<?php echo $tpl['id_user']; ?>">
<input name="deleteuser" type="hidden" id="deleteuser" value="">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="99%" align="left" valign="top">    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit User </td>
      </tr>
	  <?php if($tpl['showButton']==TRUE): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Status:</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><select name="status">
		  <option value="2"<?php if($tpl['user']['status']==2) echo ' selected="selected"'; ?>>Active</option>
          <option value="1"<?php if($tpl['user']['status']==1) echo ' selected="selected"'; ?>>Inactive</option>
        </select></td>
      </tr>
	  <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="login" type="text" id="login" size="40" maxlength="255" value="<?php echo $tpl['user']['login']; ?>"> 
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="passwd" type="text" id="passwd" size="40" maxlength="255" value=""> 
        </td>
      </tr>
	  <?php if($tpl['showButton']==TRUE): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Role</td>
      </tr>
      <tr>
        <td align="left" valign="top"><select name="role">
		  <?php foreach($tpl['form_roles'] as $key => $val): ?>
          <option value="<?php echo $key; ?>"<?php if($tpl['user']['role']==$key) echo ' selected="selected"'; ?>><?php echo $val; ?></option>
		  <?php endforeach; ?>
        </select> 
          </td>
      </tr>
	  <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="name" type="text" id="name" size="40" maxlength="255" value="<?php echo $tpl['user']['name']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="lastname" type="text" id="lastname" size="40" maxlength="255" value="<?php echo $tpl['user']['lastname']; ?>">        
          *</td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Email</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="email" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $tpl['user']['email']; ?>"> 
        *</td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="description" rows="15" cols="80" wrap="VIRTUAL" id="description"><?php echo $tpl['user']['description']; ?></textarea> 
        </td>
      </tr>       
      <tr>
        <td align="left" valign="top">
		<input name="updatethisuser" type="hidden" value="1">
		<input name="update" type="submit" id="update" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="1%" align="left" valign="top" class="font10bold"><p><a href="<?php echo SMART_CONTROLLER; ?>?mod=user">cancel</a></p>
      <p>&nbsp;        </p>
      <p>&nbsp;</p>
      <p>
	    <?php if($tpl['showButton']==TRUE): ?>
          <input type="button" name="Submit" value="delete" onclick="deluser(this.form, 'Delete this user?');">
        <?php endif; ?>
	  </p></td>
  </tr>
</table>
</form>