<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->all_lists as $list): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="6%" align="left" valign="top"><a href="index.php?admin=1&m=earchive&sec=editlist&lid=<?php echo $list['lid']; ?>">edit</a>
        </td>
        <td width="94%" align="left" valign="top" class="itemnormal">
           <a href="index.php?admin=1&m=earchive&sec=showmessages&lid=<?php echo $list['lid']; ?>"><strong><?php echo htmlentities($list['name']); ?></strong></a><br />
           <?php echo $list['email']; ?>
           <div><?php echo htmlentities($list['description']); ?></div>
        </td>
      </tr>
    </table>
   <?php endforeach; ?>
   <?php if(count($B->all_lists) == 0): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="6%" align="left" valign="top">
        </td>
        <td width="94%" align="left" valign="top" class="itemnormal">
           There is not yet any list installed.<br />
        </td>
      </tr>
    </table>   
   <?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="index.php?admin=1&m=earchive&sec=addlist">add list</a></td>
  </tr>
</table>
