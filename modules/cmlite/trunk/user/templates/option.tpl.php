<table width="100%"  border="0" cellspacing="6" cellpadding="6">
  <tr>
    <td align="left" valign="middle" bgcolor="#CCCCCC" class="optionmodtitle">User Module Options </td>
  </tr>
  <tr>
    <td>
  <form action="index.php?m=OPTION" method="post" name="allowreg" id="allowreg">
  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td colspan="2" align="left" valign="middle" class="optiontitle">Allow register </td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"  class="optiondesc">
          <input name="userallowregister" type="radio" value="1" <?php if($B->sys['option']['user']['allow_register']==TRUE) echo 'checked="checked"'; ?>/> yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="userallowregister" type="radio" value="0"  <?php if($B->sys['option']['user']['allow_register']==FALSE) echo 'checked="checked"'; ?>/> 
          no</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle" class="optiontitle">Register type </td>
      </tr>
      <tr>
        <td width="23%" align="left" valign="top"  class="optiondesc">
          <input name="userregistertype" type="radio" value="auto" <?php if($B->sys['option']['user']['register_type']=='auto') echo 'checked="checked"'; ?>/> 
          auto          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="userregistertype" type="radio" value="manual" <?php if($B->sys['option']['user']['register_type']=='manual') echo 'checked="checked"'; ?>/> 
          manual     
</td>
        <td width="77%" align="left" valign="top"  class="optiondesc"><input type="submit" name="update_user_options_allowreg" value="update" onclick="subok(this.form.update_user_options_allowreg);"></td>
      </tr>     
    </table>
  </form>
  </td>
  </tr>
</table>
