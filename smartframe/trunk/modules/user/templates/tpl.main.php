<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
	<?php if(isset($tpl['users']) && (count($tpl['users'])>0)): ?>
    <?php foreach($tpl['users'] as $usr): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
          <td width="60%" align="left" valign="top" class="itemnormal">
              <?php echo '<a href="'.SMART_CONTROLLER.'?mod=user&view=edituser&id_user='.$usr['id_user'].'">'.$usr['login'].'</a> ('.$usr['name'].' '.$usr['lastname'].')'; ?></td>
           <td width="38%" align="left" valign="top" class="itemsmall">
              <?php echo $usr['role_t']; ?>
           </td>
      </tr>
    </table>
    <?php endforeach; ?>
	<?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><?php if($tpl['showAddUserLink']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=user&view=adduser">add user </a><?php endif; ?></td>
  </tr>
</table>
