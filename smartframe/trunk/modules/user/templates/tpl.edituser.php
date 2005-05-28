<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="./modules/common/media/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
  // Notice: The simple theme does not use all options some of them are limited to the advanced theme
  tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",   
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,fontselect,fontsizeselect,styleselect",	 
    theme_advanced_buttons2 : "bullist, numlist,outdent,indent,separator,undo,redo,separator,link,unlink,cleanup,code,separator,table,hr,removeformat,sub,sup,forecolor",	 
    theme_advanced_buttons3 : "", 
	theme_advanced_styles : "test=test;test2=test2",
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
function dellogo(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deletelogo.value="1";
        with(f){
        submit();
        }
        }
}
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
}
function uploadlogofile(f)
{
        f.uploadlogo.value="true";
        with(f){
        submit();
        }
}
</script>
<form action="<?php echo SMART_CONTROLLER; ?>?mod=user&view=edituser" method="post" enctype="multipart/form-data" name="edituser">
<input name="id_user" type="hidden" id="id_user" value="<?php echo $tpl['id_user']; ?>">
<input name="deleteuser" type="hidden" id="deleteuser" value="">
<input name="canceledit" type="hidden" id="canceledit" value="">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold"> Edit User </td>
    </tr>
  <tr>
    <td width="79%" align="left" valign="top">    <table width="600" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
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
        <td height="28" align="left" valign="top" class="font10bold">Role</td>
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
        <td align="left" valign="top"><textarea name="description" rows="15" cols="80" style="width: 100%" wrap="VIRTUAL" id="description"><?php echo $tpl['user']['description']; ?></textarea> 
        </td>
      </tr>       
      <tr>
        <td align="left" valign="top">
		<input name="updatethisuser" type="hidden" value="1">
		<input name="update" type="submit" id="update" value="Submit">
	    <?php if($tpl['showButton']==TRUE): ?>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="button" name="Submit" value="delete" onclick="deluser(this.form, 'Delete this user?');">
        <?php endif; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <input type="button" name="Submit" value="cancel" onclick="cancel_edit(this.form);">
		</td>
      </tr>
    </table>
    </td>
    <td width="21%" align="center" valign="top" class="font10bold"><table width="200" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Logo Picture</td>
      </tr>
      <tr>
        <td align="center" valign="top">
		<?php if(empty($tpl['user']['logo'])): ?>
			<input type="file" name="logo" id="logo" size="10" class="fileform">
			<input name="uploadlogo" type="hidden" value="">
		    <input name="update" type="button" id="update" value="Submit" onclick="uploadlogofile(this.form);">
		    <?php else: ?>
			<img name="userlogo" src="<?php echo SMART_RELATIVE_PATH.'data/user/'.$tpl['user']['media_folder'].'/'.$tpl['user']['logo']; ?>" alt="User Logo">
		    <br>
		    <input name="deletelogo" type="hidden" value="">
		    <input type="button" name="eraselogo" value="delete" onclick="dellogo(this.form, 'Delete user logo Picture?');">
		<?php endif; ?>
</td>
      </tr>
    </table>      
      <table width="200" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Pictures</td>
        </tr>
        <tr>
          <td align="center" valign="top">
		     <input type="file" name="picture" id="picture" size="10" class="fileform">
		     <input name="update" type="submit" id="update" value="Submit">
          </td>
        </tr>
        <tr>
          <td height="28">&nbsp;</td>
        </tr>
      </table>      <p>&nbsp;</p>
      <p>&nbsp;        </p>
      <p>&nbsp;</p>
      <p>	  </p></td>
  </tr>
</table>
</form>