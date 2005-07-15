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
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
-->
</style>
<form name="edituser" method="post" action="<?php echo SMART_CONTROLLER; ?>&mod=navigation&view=editnode">
<input name="id_node" type="hidden" value="<?php echo $tpl['node']['id_node']; ?>">
<input name="modifynodedata" type="hidden" value="true">
<input name="id_parent" type="hidden" value="<?php echo $tpl['node']['id_parent']; ?>">
<input name="delnode" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" class="moduleheader2">Edit Node ID: <?php echo $tpl['node']['id_node']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td height="25" align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" >
	       <div class="font12 indent5">
	          <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation">Top</a>
	          <?php foreach($tpl['branch'] as $node): ?>
	           / <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
	          <?php endforeach; ?></div>		
		</td>
        </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="16%" align="left" valign="top" class="font10bold">Status </td>
              <td width="84%" align="left" valign="top" class="font10bold">Parent Node</td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status">
                <option value="2" <?php if($tpl['node']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                <option value="1" <?php if($tpl['node']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
              </select></td>
              <td align="left" valign="top"><select name="parent_id" size="1" id="parent_id">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['node']['id_parent'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['node']['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Short Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="short_text" cols="90" rows="3" id="short_text"><?php echo $tpl['node']['short_text']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Body</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="body" cols="90" rows="15" id="body"><?php echo $tpl['node']['body']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this node" onclick="deletenode(this.form, 'Delete this node?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="20%" align="left" valign="top" class="font10bold">&nbsp;</td>
  </tr>
</table>
</form>