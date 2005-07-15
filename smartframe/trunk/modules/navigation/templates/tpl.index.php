<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="92%" class="moduleheader">Navigation Management</td>
    <td width="8%" align="center" class="moduleheader"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=options"><font size="2">options</font></a> </td>
  </tr>
  <?php if($tpl['show_options_link']==TRUE): ?>
  <?php endif; ?>
  <tr>
    <td colspan="2">
  <?php endif; ?>
  <?php $viewLoader->{$tpl['moduleChildView']}(); ?>
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>