<form name="addnode" method="post" action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=addnode">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="57%" align="left" valign="top">    <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($B->tpl_error != FALSE): ?>
      <tr>
        <td align="left" valign="top" class="itemerror"><?php echo $B->tpl_error; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add Navigation Node
         </td>
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
        <td align="left" valign="top"><textarea name="body" cols="90" rows="20" wrap="VIRTUAL" id="body"><?php echo $B->tpl_body; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="addnode" type="submit" id="addnode" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation">back</a></td>
  </tr>
</table>
</form>