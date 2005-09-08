<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="38%" class="moduleheader">Articles Management</td>
    <td width="20%" class="moduleheader">&nbsp;</td>
    <td width="18%" align="center" valign="middle" class="moduleheader">&nbsp;</td>
    <td width="24%" align="center" class="moduleheader">
	<?php if($tpl['disableMainMenu']!=TRUE): ?>
	   <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=options">article module options</a>
	<?php endif; ?>
	</td>
  </tr>
  <tr>
    <td colspan="4">
<?php endif; ?>
<?php endif; ?>
  <?php $viewLoader->{$tpl['moduleChildView']}(); ?>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>  
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>
<?php endif; ?>