<form action="index.php?m=EARCHIVE&mf=show_mess&lid=<?php echo $B->tpl_list['lid']; ?>" method="post" name="delete">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
	<table width="100%"  border="0" cellspacing="4" cellpadding="4">
      <?php //show top pager links  ?>
      <?php if(!empty($B->tpl_messages_pager)): ?>
      <tr>
        <td colspan="2" align="left" valign="top" class="itemnormal">Messages of list <strong><?php echo $B->tpl_list['name']; ?></strong></td>
      </tr>
      <tr>
        <td width="3%" colspan="2" align="left" valign="top">
          <div class='font12'><?php echo $B->tpl_messages_pager; ?></div>
        </td>
        </tr>
      <tr>
        <td colspan="2" align="left" valign="top">
          <hr/>
        </td>
        </tr>
      <?php endif; ?>
      <?php //show messages  ?>
      <?php if (count($B->tpl_messages) > 0): ?>
      <?php foreach($B->tpl_messages as $msg): ?>	  
      <tr>
        <td width="3%" align="left" valign="top"><input type="checkbox" name="mid[]" value="<?php echo $msg['mid']; ?>"></td>
        <td width="97%" align="left" valign="top">
          <div class='font10'>DATE: <?php echo $msg['mdate']; ?></div>
          <div class='font10'>FROM: <?php echo $msg['sender']; ?></div>
          <a href="index.php?m=EARCHIVE&mf=edit_mess&lid=<?php echo $msg['lid']; ?>&pageID=<?php echo $_GET['pageID']; ?>" class="font12bold"><?php echo $msg['subject']; ?></a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr>
        <td align="left" valign="top"></td>
        <td align="left" valign="top">
          <div class='font10'>Currently no messages available</div>
        </td>
      </tr>	  
      <?php endif; ?>	  
      <?php //show down pager links  ?>
      <?php if(!empty($B->tpl_messages_pager)): ?>
      <tr>
        <td colspan="2" align="left" valign="top">
          <hr/>
        </td>
        </tr>
      <tr>
        <td colspan="2" align="left" valign="top">
          <div class='font12'><?php echo $B->tpl_messages_pager; ?></div>
        </td>
        </tr>
      <?php endif; ?>
    </table>      
    </td>
    <td width="11%" align="center" valign="top" class="itemnormal">
      <table width="100%" border="0" cellspacing="4" cellpadding="4">
        <tr>
          <td><a href="index.php?m=EARCHIVE">back to main</a></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="deletemess" type="submit" value="delete" onclick="subok(this.form.deletemess);"></td>
        </tr>
    </table>
	</td>
  </tr>
</table>
</form>
