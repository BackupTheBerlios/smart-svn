<script language="JavaScript" type="text/JavaScript">
function media_manager(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=550,height=450';
newwindow= window.open('<?php echo SF_CONTROLLER; ?>?admin=1&nodecoration=1&m=navigation&sec=media_manager','',mm); }
</script>
<form name="addnode" method="post" action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=addnode&node=<?php echo $_REQUEST['node']; ?>">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">    <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td colspan="2" align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td width="312" align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add Navigation Node
         </td>
        <td width="254" align="center" valign="top" bgcolor="#CCCCCC" class="font10bold"><table width="100%" border="0" cellspacing="0" cellpadding="2">
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
        <td colspan="2" align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $B->tpl_title; ?>"></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Body &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;( use <a href="http://wiki.ciaweb.net/yawiki/index.php?area=Text_Wiki&page=WikiRules" target="_blank">text_wikki</a> markdown language to format body text )</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><textarea name="body" cols="90" rows="15" wrap="VIRTUAL" id="body"><?php echo $B->tpl_body; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><input name="addnode" type="submit" id="addnode" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation">back</a></td>
  </tr>
</table>
</form>