<?php if($B->tpl_error != FALSE): ?>
<?php echo $B->tpl_error; ?>
<?php endif; ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->all_users as $usr): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="2%" align="left" valign="top">
                <?php if($usr['status']==1): ?>
                <img src="<?php echo SF_MEDIA_FOLDER."/inactif.png"; ?>" width="21" height="21">
                <?php elseif($usr['status']==2): ?>
                 <img src="<?php echo SF_MEDIA_FOLDER."/actif.png"; ?>" width="21" height="21">              
                <?php elseif($usr['status']==0): ?>
                 <img src="<?php echo SF_MEDIA_FOLDER."/demande.png"; ?>" width="21" height="21"> 
                <?php endif; ?>
                </td>
                <td width="60%" align="left" valign="top" class="itemnormal">
                 <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=user&sec=edituser&uid='.$usr['uid'].'">'.$usr['lastname'].' '.$usr['forename'].'</a> (<a href="mailto:'.$usr['email'].'">'.$usr['login'].'</a>)'; ?></td>
                <td width="38%" align="left" valign="top" class="itemsmall">
                 <?php if($usr['rights']==1): ?>
                 Registered
                 <?php elseif($usr['rights']==2): ?>
                 Contributor
                 <?php elseif($usr['rights']==3): ?>
                 Author
                 <?php elseif($usr['rights']==4): ?>
                 Editor
                 <?php elseif($usr['rights']==5): ?>
                 Administrator
                 <?php endif; ?>
                </td>
      </tr>
    </table>
        <?php endforeach; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=user&sec=adduser">add user </a></td>
  </tr>
</table>
