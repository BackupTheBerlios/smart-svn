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
function media_manager(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=550,height=450';
newwindow= window.open('<?php echo SF_CONTROLLER; ?>?admin=1&nodecoration=1&m=navigation&sec=media_manager','',mm); }
</script>
<style type="text/css">
<!--
.optsel {
	background-color: #CCCCCC;
}
-->
</style>

<form name="edituser" method="post" action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=editnode">
<input name="edit_node" type="hidden" value="<?php echo $_REQUEST['edit_node']; ?>">
<input name="node" type="hidden" value="<?php echo $_REQUEST['node']; ?>">
<input name="modifynodedata" type="hidden" value="true">
<input name="delnode" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">      <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td colspan="2" align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td width="360" align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Edit Navigation Node          </td>
        <td width="206" align="left" valign="top" bgcolor="#CCCCCC" class="font10bold"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="53%" align="center" valign="top"><a href="javascript:media_manager();">media manager</a></td>
            </tr>
        </table></td>
      </tr>      
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Status</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold"><select name="status" size="1" id="status">
          <option value="2" <?php if($B->tpl_node['status'] == 2) echo 'selected="selected"'; ?>>Publish</option>
          <option value="1" <?php if($B->tpl_node['status'] == 1) echo 'selected="selected"'; ?>>Drawt</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Parent Node</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold"><select name="parent_id" size="1" id="parent_id">
         <option value="0">Top</option>   
      <?php foreach($B->tpl_tree as $val):  ?>
          <option value="<?php echo $val['node']; ?>" <?php if($val['node'] == $B->tpl_node['parent_id'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
        <?php endforeach; ?>
    </select></td>
      </tr>   
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $B->tpl_node['title']; ?>"></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Body &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;( use <a href="http://wiki.ciaweb.net/yawiki/index.php?area=Text_Wiki&page=WikiRules" target="_blank">text_wikki</a> markdown language to format body text )</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><textarea name="body" cols="90" rows="15" id="body"><?php echo $B->tpl_node['body']; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this node" onclick="deletenode(this.form, 'Delete this node?');">
          </div></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><input name="editnode" type="submit" id="editnode" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&node=<?php echo $_REQUEST['node']; ?>">back</a></td>
  </tr>
</table>
</form>