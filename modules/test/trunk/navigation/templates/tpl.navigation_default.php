<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php $num = count($B->tpl_nodes); for($x=1;$x<=$num;$x++): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="10%" align="left" valign="top" class="itemnormal"><font size="2">
    <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=up&node='.$B->tpl_nodes[$x]['node'].'">up</a>'; ?>
    <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=down&node='.$B->tpl_nodes[$x]['node'].'">down</a>'; ?>
    </font></td>
          <td width="90%" align="left" valign="top" class="itemnormal">
              <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&sec=editnode&node='.$B->tpl_nodes[$x]['node'].'">'.$B->tpl_nodes[$x]['title'].'</a>'; ?></td>
      </tr>
    </table>
    <?php endfor; ?>
    </td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=addnode">add node </a></td>
  </tr>
</table>
