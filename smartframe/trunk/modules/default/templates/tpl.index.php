<?php /*
 ### Error template. It is loaded when a view class produce an error ### 
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC">&nbsp;&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Default Module</strong></font></td>
  </tr>
  <tr>
    <td><?php  echo $tpl['message']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
</table>
