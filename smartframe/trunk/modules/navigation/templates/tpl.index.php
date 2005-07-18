<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="23%" class="moduleheader">Navigation Management</td>
    <td width="61%" class="moduleheader">&nbsp;</td>
    <td width="9%" align="center" valign="middle" class="moduleheader"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=views"><font size="2">views</font></a></td>
    <td width="7%" align="center" class="moduleheader"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=options"><font size="2">options</font></a> </td>
  </tr>
  <?php if($tpl['show_options_link']==TRUE): ?>
  <?php endif; ?>
  <tr>
    <td colspan="4">
  <?php endif; ?>
  <?php $viewLoader->{$tpl['moduleChildView']}(); ?>
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>