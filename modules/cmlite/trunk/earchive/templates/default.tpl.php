<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->all_lists as $list): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="6%" align="left" valign="top"><a href="index.php?m=MAILARCHIVER&mf=edit_list&lid=<?php echo $list['lid']; ?>">edit</a>
        </td>
                <td width="94%" align="left" valign="top" class="itemnormal">
                                <strong><?php echo htmlentities($list['name']); ?></strong><br />
                                <?php echo $list['email']; ?>
                                <div><?php echo htmlentities($list['description']); ?></div>
                                </td>
        </tr>
    </table>
        <?php endforeach; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="index.php?m=MAILARCHIVER&mf=add_list">add list</a></td>
  </tr>
</table>
