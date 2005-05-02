<?php /*
 ### Error template. It is loaded when a view class produce an error ### 
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Index</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#3399FF">&nbsp;&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif">Smart php Framework <font size="2">(Admin Interface)</font></font></td>
  </tr>
  <tr>
    <td align="left" valign="top"><?php $viewLoader->{$tpl['view']}(); ?></td>
  </tr>
  <tr>
    <td align="left" valign="middle" bgcolor="#3399FF">&nbsp;</td>
  </tr>
</table>
</body>
</html>
