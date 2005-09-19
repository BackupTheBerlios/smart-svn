<script language="JavaScript" type="text/JavaScript">
<!--
function deletelink(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_link.value="1";
        with(f){
        submit();
        }
        }
}
// unlock a node and forward to the node with id x. use this for links
function gotonode(f,x){
        f.gotonode.value=x;
        with(f){
        submit();
        }
}
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
}
//-->
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
.jj {
  font-family: "Courier New", Courier, mono;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 5px;
  padding-left: 0px;
  font-size: 100%;
}
-->
</style>
<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo SMART_CONTROLLER; ?>?mod=link&view=editLink" method="post" enctype="multipart/form-data" name="editlink" id="editlink">
<input name="gotonode" type="hidden" value="">
<input name="modifylinkdata" type="hidden" value="true">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="id_node" type="hidden" value="<?php echo $tpl['id_node']; ?>">
<input name="id_link" type="hidden" value="<?php echo $tpl['link']['id_link']; ?>">
<input name="delete_link" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" class="moduleheader2">Edit Link ID: <?php echo $tpl['link']['id_link']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
	  <?php foreach($tpl['error'] as $error): ?>
      <tr>
        <td width="312" align="left" valign="top" class="itemerror"><?php echo $error; ?></td>
      </tr>
	  <?php endforeach; ?>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" >
         <div class="font12 indent5">
            <a href="javascript:gotonode(document.forms['editlink'],0);">Top</a>
            <?php foreach($tpl['branch'] as $node): ?>
             / <a href="javascript:gotonode(document.forms['editlink'],<?php echo $node['id_node']; ?>);"><?php echo $node['title']; ?></a>
            <?php endforeach; ?></div>    
    </td>
        </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="10%" align="left" valign="top" class="font10bold">Status </td>
              <td width="80%" align="left" valign="top" class="font10bold">Navigation Node</td>
              <td width="10%" align="right" valign="top" class="font10bold"><input type="button" name="cancel" value="cancel" onclick="cancel_edit(this.form);"></td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="2" <?php if($tpl['link']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                <option value="1" <?php if($tpl['link']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
              </select></td>
              <td align="left" valign="top"><select name="link_id_node" size="1" id="node_id_node" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['id_node'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
              <td align="right" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
            </tr>
          </table></td>
      </tr>  
      <tr>
        <td align="left" valign="top" class="font10bold">Url</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="url" type="text" id="url" size="90" maxlength="1024" value="<?php echo $tpl['link']['url']; ?>"></td>
      </tr>	   
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['link']['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="description" cols="90" rows="5" id="description"><?php echo $tpl['link']['description']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this link" onclick="deletelink(this.form, 'Delete this link?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="20%" align="left" valign="top" class="font10bold">&nbsp;  </td>
  </tr>
</table>
</form>