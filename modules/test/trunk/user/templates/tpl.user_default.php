<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->tpl_users as $usr): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
          <td width="60%" align="left" valign="top" class="itemnormal">
              <?php echo '<a href="'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1&m=user&sec=edituser&user='.urlencode($usr['user']).'">'.$usr['user'].'</a>'; ?></td>
           <td width="38%" align="left" valign="top" class="itemsmall">
              <?php echo $usr['role']; ?>
           </td>
      </tr>
    </table>
    <?php endforeach; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m=user&sec=adduser">add user </a></td>
  </tr>
</table>
