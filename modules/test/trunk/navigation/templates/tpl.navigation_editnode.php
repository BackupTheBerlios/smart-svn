<script language="JavaScript" type="text/JavaScript">
function deletenode(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delnode.value="1";
        with(f){
        submit();
        }
        }
}
</script>
<form name="edituser" method="post" action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=editnode">
<input name="node" type="hidden" value="<?php echo $_REQUEST['node']; ?>">
<input name="modifynodedata" type="hidden" value="true">
<input name="delnode" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">      <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit Navigation Node</td>
      </tr>      
      <tr>
        <td align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><select name="status" size="1" id="status">
          <option value="publish" <?php if($B->tpl_status == 'publish') echo 'selected="selected"'; ?>>Publish</option>
          <option value="drawt" <?php if($B->tpl_status == 'drawt') echo 'selected="selected"'; ?>>Drawt</option>
        </select></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $B->tpl_title; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Body</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="body" cols="90" rows="25" id="body"><?php echo $B->tpl_body; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this node" onclick="deletenode(this.form, 'Delete this node?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="editnode" type="submit" id="editnode" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation">back</a></td>
  </tr>
</table>
</form>