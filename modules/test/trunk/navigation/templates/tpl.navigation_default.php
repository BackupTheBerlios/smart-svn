<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->tpl_nodes as $node): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="7%" align="left" valign="top" class="itemnormal"><font size="2">
		<?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=up&node='.$node['node'].'">up</a>'; ?>
		<?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=down&node='.$node['node'].'">down</a>'; ?>
		</font></td>
          <td width="93%" align="left" valign="top" class="itemnormal">
              <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&sec=editnode&node='.$node['node'].'">'.$node['title'].'</a>'; ?></td>
      </tr>
    </table>
    <?php endforeach; ?>
    </td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=addnode">add node </a></td>
  </tr>
</table>
