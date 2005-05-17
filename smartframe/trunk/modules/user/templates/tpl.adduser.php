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
<form name="adduser" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=user&view=adduser">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="99%" align="left" valign="top">    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add User </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="login" type="text" id="login" size="40" maxlength="255" value="<?php echo $tpl['form_login']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="passwd" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $tpl['form_passwd']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="name" type="text" id="name" size="40" maxlength="255" value="<?php echo $tpl['form_name']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="lastname" type="text" id="lastname" size="40" maxlength="255" value="<?php echo $tpl['form_lastname']; ?>"> 
        *</td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Email</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="email" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $tpl['form_email']; ?>"> 
        *</td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="description" rows="15" cols="80" wrap="VIRTUAL" id="description"><?php echo $tpl['form_description']; ?></textarea> 
        </td>
      </tr>       
      <tr>
        <td align="left" valign="top"><input name="adduser" type="submit" id="addthisuser" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="1%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=user">cancel</a></td>
  </tr>
</table>
</form>