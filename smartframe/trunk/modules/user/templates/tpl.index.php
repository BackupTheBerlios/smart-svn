<?php /*
 ### Error template. It is loaded when a view class produce an error ### 
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="moduleheader">User Management</td>
  </tr>
  <tr>
    <td>
	<?php endif; ?>
	<?php $viewLoader->{$tpl['moduleChildView']}(); ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>	
	</td>
  </tr>
</table>
<?php endif; ?>