<table width="100%"  border="0" cellspacing="6" cellpadding="6">
  <tr>
    <td align="left" valign="middle" bgcolor="#CCCCCC" class="optionmodtitle">User Module Options </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="middle" class="optiontitle">Allow register </td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="optiondesc">
          <input name="userallowregister" type="radio" value="1" <?php if($B->sys['option']['user']['allow_register']==TRUE) echo 'checked="checked"'; ?>/> yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="userallowregister" type="radio" value="0"  <?php if($B->sys['option']['user']['allow_register']==FALSE) echo 'checked="checked"'; ?>/> 
          no</td>
      </tr>
      <tr>
        <td align="left" valign="middle" class="optiontitle">Register type </td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="optiondesc">
          <input name="userregistertype" type="radio" value="auto" <?php if($B->sys['option']['user']['register_type']=='auto') echo 'checked="checked"'; ?>/> 
          auto          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="userregistertype" type="radio" value="manual" <?php if($B->sys['option']['user']['register_type']=='manual') echo 'checked="checked"'; ?>/> 
          manual     
</td>
      </tr>			
    </table></td>
  </tr>
</table>
